<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Middlewares\Logic\NeedLoginResponseMiddleware;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\FestivalModel;
use App\Logic\Models\Model;
use App\Logic\Models\ProductModel;
use App\Logic\Utils\ProductAttributeUtil;
use App\Logic\Utils\ProductUtil;
use App\Logic\Utils\TorobUtil;
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
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var FestivalModel $festivalModel
         */
        $festivalModel = container()->get(FestivalModel::class);

        // get it from GET if parameter is not sent in url
        if (empty($category)) {
            $category = input()->get('category')->getValue();
        }

        $festivalWhere = '';
        $festivalBindValues = [];
        // festival checks
        $festivalInfo = [];
        $festival = input()->get('festival', -1)->getValue();
        if (is_numeric($festival) && $festival != -1) {
            $festivalInfo = $festivalModel->getFirst(
                ['title'],
                'id=:id AND publish=:pub',
                ['id' => $festival, 'pub' => DB_YES]
            );
            if (count($festivalInfo)) {
                $festivalWhere = 'pa.festival_id=:fid AND pa.festival_publish=:fpub AND pa.festival_start<=:fstr AND pa.festival_expire>=:fexp';
                $festivalBindValues = [
                    'fid' => $festival,
                    'fpub' => DB_YES,
                    'fstr' => time(),
                    'fexp' => time(),
                ];
            }
        }

        $categoryWhere = '';
        $categoryBindValues = [];
        // category checks
        $categoryInfo = [];
        if (is_numeric($category)) {
            $categoryInfo = $categoryModel->getFirst(
                ['name'],
                'id=:id AND publish=:pub',
                ['id' => $category, 'pub' => DB_YES]
            );
            if (count($categoryInfo)) {
                $categoryWhere = '(pa.category_id=:cid OR pa.category_all_parents_id REGEXP :capi)';
                $categoryBindValues = [
                    'cid' => $category,
                    'capi' => getDBCommaRegexString($category),
                ];
            }
        }

        // load side categories
        $categorySelect = $model->select();
        $categorySelect
            ->from(BaseModel::TBL_CATEGORIES)
            ->cols(['id', 'name'])
            ->where('publish=:pub')
            ->bindValue('pub', DB_YES)->orderBy([
                'priority DESC',
                'name DESC'
            ]);

        //-----
        $attrs = [];
        $globalWhere = '';
        $globalBindValues = [];
        $priceWhere = '';
        $priceBindValues = [];
        if (count($categoryInfo)) {
            // set global where
            $globalWhere .= $categoryWhere;
            $globalBindValues = array_merge($globalBindValues, $categoryBindValues);

            // get max price
            $priceWhere .= 'pa.category_id=:cId';
            $priceBindValues['cId'] = $category;

            // get dynamic attributes
            $attrs = ProductAttributeUtil::getRefinedProductAttributes($category);
        } else {
            // load side categories (cont.)
            $categorySelect
                ->where('level=:lvl')
                ->bindValue('lvl', 1);
        }
        if (count($festivalInfo)) {
            if (empty(trim($priceWhere))) {
                $priceWhere .= $festivalWhere;
                $globalWhere .= $festivalWhere;
            } else {
                $priceWhere = $priceWhere . ' AND ' . $festivalWhere;
                $globalWhere = $globalWhere . ' AND (' . $festivalWhere . ')';
            }
            $priceBindValues = array_merge($priceBindValues, $festivalBindValues);
            $globalBindValues = array_merge($globalBindValues, $festivalBindValues);
        }

        $categories = $model->get($categorySelect);
        //-----

        // load brands
        $brands = $productModel->getLimitedProduct(
            $globalWhere,
            $globalBindValues,
            ['pa.product_availability DESC', 'pa.is_available DESC', 'pa.stock_count DESC', 'pa.product_id DESC'],
            null,
            0,
            ['pa.product_id'],
            ['DISTINCT(pa.brand_id) AS id', 'pa.brand_name AS name']
        );

        // load colors
        $colors = $productModel->getLimitedProduct(
            $globalWhere,
            $globalBindValues,
            ['pa.product_availability DESC', 'pa.is_available DESC', 'pa.stock_count DESC', 'pa.product_id DESC'],
            null,
            0,
            ['pa.product_id'],
            ['DISTINCT(pa.color_name) AS name', 'pa.color_hex AS hex']
        );

        // load sizes and models
        $sizesNModels = $productModel->getLimitedProduct(
            $globalWhere,
            $globalBindValues,
            ['pa.product_availability DESC', 'pa.is_available DESC', 'pa.stock_count DESC', 'pa.product_id DESC'],
            null,
            0,
            ['pa.product_id'],
            ['DISTINCT(pa.size)']
        );
        $sizes = [];
        foreach ($sizesNModels as $item) {
            if (trim($item['size']) != '') $sizes[] = $item['size'];
        }

        // get max price (cont.)
        $maxPrice = $productModel->getLimitedProduct(
            $priceWhere,
            $priceBindValues,
            ['max_price DESC'],
            1,
            0,
            ['pa.product_id'],
            ['MAX(price) AS max_price']
        );
        if (count($maxPrice)) {
            $maxPrice = $maxPrice[0]['max_price'];
        } else {
            $maxPrice = 0;
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/product/index');
        return $this->render([
            'sub_title' => 'محصولات'
                . (
                !empty($festivalInfo)
                    ? '<small>'
                    . (' «جشنواره - ' . $festivalInfo['title'] . '»')
                    . (
                    !empty($categoryInfo)
                        ? '<small>' . (' «در دسته - ' . $categoryInfo['name'] . '»') . '</small>'
                        : ''
                    )
                    . '</small>'
                    : (
                !empty($categoryInfo)
                    ? '<small>' . (' «در دسته - ' . $categoryInfo['name'] . '»') . '</small>'
                    : ''
                )
                ),
            //
            'category' => $category,
            'festival' => $festival,
            'side_categories' => $categories,
            'max_price' => $maxPrice,
            'brands' => $brands,
            'sizes' => $sizes,
            'colors' => $colors,
            'dynamicAttrs' => $attrs,
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
            [],
            1,
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
            ->bindValue('p_id', $product['product_id'])
            ->orderBy(['is_available DESC', 'stock_count DESC']);
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
            'meta' => TorobUtil::getMetaOfProduct($product),
            //
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
                    'pa.festival_publish',
                    'pa.festival_expire',
                    'pa.festival_start',
                    'pa.festival_discount',
                    'pa.show_coming_soon',
                    'pa.call_for_more',
                ]
            );

            if (count($product)) {
                $product = $product[0];
                //
                $stepped = get_stepped_price(1, $product_code);
                if (!is_null($stepped)) {
                    $product['stepped_price'] = $stepped['price'];
                    $product['stepped_discounted_price'] = $stepped['discounted_price'];
                }
            }

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
