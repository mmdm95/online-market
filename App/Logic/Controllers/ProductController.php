<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Middlewares\Logic\NeedLoginResponseMiddleware;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CommentModel;
use App\Logic\Models\Model;
use App\Logic\Models\ProductModel;
use App\Logic\Utils\ProductUtil;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ProductController extends AbstractHomeController
{
    /**
     * @param null $category
     * @param null $category_slug
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function index($category = null, $category_slug = null)
    {
        /**
         * @var Model $model
         */
        $model = container()->get(Model::class);

        // load side categories
        if (is_numeric($category)) {
            $select = $model->select();
            $select
                ->from(BaseModel::TBL_CATEGORIES)
                ->cols(['id', 'name'])
                ->where('publish=:pub')
                ->bindValues([
                    'pub' => DB_YES,
                ])
                ->orderBy([
                    'priority DESC',
                    'name DESC'
                ]);
            $categories = $model->get($select);
        } else {
            $select = $model->select();
            $select
                ->from(BaseModel::TBL_CATEGORIES)
                ->cols(['id', 'name',])
                ->where('publish=:pub')
                ->where('level=:lvl')
                ->bindValues([
                    'pub' => DB_YES,
                    'lvl' => 1,
                ]);
            $categories = $model->get($select);
        }

        // load brands
        $select = $model->select();
        $select
            ->from(BaseModel::TBL_BRANDS)
            ->cols(['id', 'name'])
            ->where('publish=:pub')
            ->bindValues([
                'pub' => DB_YES,
            ]);
        $brands = $model->get($select);

        // load colors
        $select = $model->select();
        $select
            ->from(BaseModel::TBL_COLORS)
            ->cols(['name', 'hex'])
            ->where('publish=:pub')
            ->bindValues([
                'pub' => DB_YES,
            ]);
        $colors = $model->get($select);

        // load sizes and models
        $select = $model->select();
        $select
            ->from(BaseModel::TBL_PRODUCT_PROPERTY)
            ->cols(['distinct(size)']);
        $sizesNModels = $model->get($select);
        $sizes = [];
        foreach ($sizesNModels as $item) {
            $sizes[] = $item['size'];
        }

        // get max price
        $select = $model->select();
        $select
            ->from(BaseModel::TBL_PRODUCT_PROPERTY)
            ->cols(['MAX(price) AS max_price']);
        $maxPrice = $model->get($select);
        if (count($maxPrice)) {
            $maxPrice = $maxPrice[0]['max_price'];
        } else {
            $maxPrice = 0;
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/product/index');
        return $this->render([
            'category' => $category,
            'side_categories' => $categories,
            'max_price' => $maxPrice,
            'brands' => $brands,
            'sizes' => $sizes,
            'colors' => $colors,
        ]);
    }

    /**
     * @param $id
     * @param null $slug
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function show($id, $slug = null)
    {
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->getLimitedProduct(
            'pa.product_id=:p_id AND pa.publish=:pub AND pa.is_deleted<>:del',
            [
                'p_id' => (int)$id,
                'pub' => DB_YES,
                'del' => DB_YES,
            ],
            ['pa.product_id DESC'],
            null,
            0,
            [],
            ['*']
        );
        if (!count($product)) {
            return $this->show404();
        }
        $product = $product[0];

        /**
         * @var Model $model
         */
        $model = container()->get(Model::class);

        // get gallery
        $select = $model->select();
        $select
            ->from(BaseModel::TBL_PRODUCT_GALLERY)
            ->cols(['image'])
            ->where('product_id=:pId')
            ->bindValues([
                'pId' => $product['product_id'],
            ]);
        $gallery = $model->get($select);

        //-----
        $select = $model->select();
        $select
            ->from(BaseModel::TBL_PRODUCT_PROPERTY)
            ->cols(['code', 'color_hex', 'color_name', 'size', 'guarantee'])
            ->where('product_id=:p_id')
            ->bindValue('p_id', $product['product_id']);
        $colorsNSizes = $model->get($select);

        if (!count($colorsNSizes)) {
            return $this->show404();
        }

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');

        // is bookmarked
        $isWishListed = false;
        if ($auth->isLoggedIn()) {
            $isWishListed = $productModel->isUserFavoriteProduct($auth->getCurrentUser()['id'] ?? 0, $product['product_id']);
        }

        /**
         * @var ProductUtil $productUtil
         */
        $productUtil = container()->get(ProductUtil::class);

        // get related products
        $related_products = $productUtil->getRelatedProducts($product['product_id']);

        // get comments count
        $select = $model->select();
        $select
            ->from(BaseModel::TBL_COMMENTS)
            ->cols(['COUNT(*) AS count'])
            ->where('product_id=:p_id AND the_condition=:condition')
            ->bindValues([
                'p_id' => $product['product_id'],
                'condition' => COMMENT_CONDITION_ACCEPT,
            ]);
        $commentsCount = $model->get($select);
        $commentsCount = count($commentsCount) ? (int)$commentsCount[0]['count'] : 0;

        $this->setLayout($this->main_layout)->setTemplate('view/main/product/detail');
        return $this->render([
            'title' => title_concat(\config()->get('settings.title.value'), 'محصولات', $product['title']),
            'sub_title' => $product['title'],
            //-----
            'product' => $product,
            'gallery' => $gallery,
            'colors_and_sizes' => $colorsNSizes,
            'is_in_wishlist' => $isWishListed,
            'related_products' => $related_products,
            'comments_count' => $commentsCount,
            'is_commenting_allowed' => $auth->isLoggedIn(),
            'user' => get_current_authenticated_user(auth_home()),
        ]);
    }

    /**
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function search()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var ProductUtil $productUtil
             */
            $productUtil = container()->get(ProductUtil::class);
            $productInfo = $productUtil->paginatedProduct();
            $resourceHandler->data($this->setTemplate('partial/main/product/filtered')->render([
                'products' => $productInfo['items'],
                'pagination' => $productInfo['pagination'],
            ]));
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $product_code
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function getPrice($product_code)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var ProductModel $productModel
             */
            $productModel = container()->get(ProductModel::class);
            $product = $productModel->getLimitedProduct(
                'pa.code=:p_code',
                ['p_code' => $product_code],
                [],
                1,
                0,
                [],
                [
                    'pa.product_availability',
                    'pa.is_available',
                    'pa.price',
                    'pa.festival_discount',
                    'pa.discount_until',
                    'pa.discounted_price',
                    'pa.max_cart_count',
                    'pa.stock_count',
                ]
            );

            if (count($product)) $product = $product[0];

            $isAvailable = get_product_availability($product);

            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data([
                    'html' => $this->setTemplate('partial/main/product/price')->render([
                        'product' => $product,
                    ]),
                    'max_cart_count' => $isAvailable ? ($product['max_cart_count'] ?? 0) : 0,
                    'is_really_available' => $isAvailable,
                ]);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $product_id
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function wishlistToggle($product_id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var NeedLoginResponseMiddleware $checker
             */
            $checker = container()->get(NeedLoginResponseMiddleware::class);
            $res = $checker->handle();
            if ($res) {
                /**
                 * @var DBAuth $auth
                 */
                $auth = container()->get('auth_home');
                /**
                 * @var ProductModel $productModel
                 */
                $productModel = container()->get(ProductModel::class);
                [$res, $type, $message] = $productModel->toggleUserFavoriteProduct($auth->getCurrentUser()['id'] ?? 0, $product_id);
                $resourceHandler->type($type)->data($message);
            }
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $product_id
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function wishlistRemove($product_id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var NeedLoginResponseMiddleware $checker
             */
            $checker = container()->get(NeedLoginResponseMiddleware::class);
            $res = $checker->handle();
            if ($res) {
                /**
                 * @var DBAuth $auth
                 */
                $auth = container()->get('auth_home');
                /**
                 * @var ProductModel $productModel
                 */
                $productModel = container()->get(ProductModel::class);
                $res = $productModel->removeUserFavoriteProduct($auth->getCurrentUser()['id'] ?? 0, $product_id);
                if ($res) {
                    $type = RESPONSE_TYPE_INFO;
                    $message = 'محصول از لیست علاقه‌مندی‌ها حذف شد.';
                } else {
                    $type = RESPONSE_TYPE_WARNING;
                    $message = 'خطا در حذف محصول از لیست علاقه‌مندی‌ها!';
                }
                $resourceHandler->type($type)->data($message);
            }
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }
}