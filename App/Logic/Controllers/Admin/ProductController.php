<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Product\AddProductForm;
use App\Logic\Forms\Admin\Product\Attribute\Value\AssignProductAttrValueForm;
use App\Logic\Forms\Admin\Product\BatchEditProductForm;
use App\Logic\Forms\Admin\Product\BatchEditProductPriceForm;
use App\Logic\Forms\Admin\Product\EditProductForm;
use App\Logic\Forms\Ajax\Product\QuickEditProductForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralAjaxStatusHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Handlers\Select2Handler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Interfaces\ISelect2Controller;
use App\Logic\Models\BaseModel;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ColorModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UnitModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\ProductAttributeUtil;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;

class ProductController extends AbstractAdminController implements IDatatableController, ISelect2Controller
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function view()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/product/view');
        return $this->render();
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function detail($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->get(['title'], 'id=:id', ['id' => $id]);

        if (0 == count($product)) {
            return $this->show404();
        }

        $product = $productModel->getSingleProduct('p.id=:id', ['id' => $id], [
            'p.id',
            'p.slug',
            'p.title',
            'p.image',
            'p.category_id',
            'p.keywords',
            'p.is_available',
            'p.is_special',
            'p.unit_title',
            'p.unit_sign',
            'p.body',
            'p.properties',
            'p.baby_property',
            'p.brand_id',
            'p.allow_commenting',
            'p.show_coming_soon',
            'p.call_for_more',
            'p.min_product_alert',
            'p.publish',
            'p.is_returnable',
            'b.name AS brand_name',
            'b.slug AS brand_slug',
            'b.keywords AS brand_keywords',
            'c.name AS category_name',
            'c.keywords AS category_keywords',
        ]);
        $related = $productModel->getRelatedProductsWithInfo($id, ['title', 'image', 'brand_name', 'category_name']);
        $gallery = $productModel->getImageGallery($id);
        $productProperty = $productModel->getProductProperty($id);

        $this->setLayout($this->main_layout)->setTemplate('view/product/detail');
        return $this->render([
            'product' => $product,
            'related' => $related,
            'gallery' => $gallery,
            'product_property' => $productProperty,
            'sub_title' => 'جزئیات محصول' . ' - ' . $product['title'],
        ]);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function add()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddProductForm::class, 'product_add');
        }

        /**
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);
        /**
         * @var ColorModel $colorModel
         */
        $colorModel = container()->get(ColorModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);

        $this->setLayout($this->main_layout)->setTemplate('view/product/add');
        return $this->render(array_merge($data, [
            'colors' => $colorModel->get(['id', 'hex', 'name']),
            'units' => $unitModel->get(['id', 'title', 'sign']),
            'brands' => $brandModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
            'categories' => $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
        ]));
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function edit($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->get(['title', 'category_id'], 'id=:id', ['id' => $id]);

        if (0 == count($product)) {
            return $this->show404();
        }

        // store previous hex to check for duplicate
        session()->setFlash('product-prev-category', $product[0]['category_id']);
        session()->setFlash('product-prev-title', $product[0]['title']);
        session()->setFlash('product-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditProductForm::class, 'product_edit');
        }

        $product = $productModel->getFirst(['*'], 'id=:id', ['id' => $id]);

        $productProperties = $productModel->getProductProperty($id);
        $related = $productModel->getRelatedProductsWithInfo($id);
        $related = array_map(function ($r) {
            return $r['product_id'];
        }, $related);
        $gallery = $productModel->getImageGallery($id);

        /**
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);
        /**
         * @var ColorModel $colorModel
         */
        $colorModel = container()->get(ColorModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);

        $this->setLayout($this->main_layout)->setTemplate('view/product/edit');

        return $this->render(array_merge($data, [
            'product' => $product,
            'product_properties' => $productProperties,
            'related' => $related,
            'gallery' => $gallery,
            'colors' => $colorModel->get(['id', 'hex', 'name']),
            'units' => $unitModel->get(['id', 'title', 'sign']),
            'brands' => $brandModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
            'categories' => $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
        ]));
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function editValue($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->get(['title', 'category_id'], 'id=:id', ['id' => $id]);

        if (0 == count($product)) {
            return $this->show404();
        }

        // store previous hex to check for duplicate
        session()->setFlash('product-prev-category', $product[0]['category_id']);
        session()->setFlash('product-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AssignProductAttrValueForm::class, 'product_value_edit');
        }

        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        //
        $refinedAttrs = ProductAttributeUtil::getRefinedProductAttributes($product[0]['category_id']);
        //-----
        $refinedProductAttrs = [];
        $productAttrs = ArrayUtil::arrayGroupBy(
            'p_attr_id',
            $attrModel->getProductAttrValues($id, ['pav.id AS val_id', 'pav.p_attr_id', 'pav.attr_val'])
        );
        foreach ($productAttrs as $attr => $values) {
            $vals = [];
            foreach ($values as $v) {
                $vals[$v['val_id']] = $v['attr_val'];
            }
            $refinedProductAttrs[$attr] = $vals;
        }
        //

        $this->setLayout($this->main_layout)->setTemplate('view/product/attribute/value/product-value');
        return $this->render(array_merge($data, [
            'sub_title' => 'ویژگی‌های جستجوی محصول' . '-' . $product[0]['title'],
            'attr_values' => $refinedAttrs,
            'product_attr_values' => $refinedProductAttrs,
        ]));
    }

    /**
     * @param $ids
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function batchEdit($ids)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        // use session to store ids to use in form action
        session()->setFlash('product-curr-ids', $ids);

        $products = $this->getBatchEditProducts($ids);
        if (!count($products)) {
            return $this->show404();
        }

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(BatchEditProductForm::class, 'product_batch_edit');
        }

        /**
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);
        /**
         * @var ColorModel $colorModel
         */
        $colorModel = container()->get(ColorModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);

        $this->setLayout($this->main_layout)->setTemplate('view/product/batch-edit');

        return $this->render(array_merge($data, [
            'ids' => $ids,
            'products' => $products,
            'colors' => $colorModel->get(['id', 'hex', 'name']),
            'units' => $unitModel->get(['id', 'title', 'sign']),
            'brands' => $brandModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
            'categories' => $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
        ]));
    }

    /**
     * @param $ids
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function batchEditPrice($ids)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        // use session to store ids to use in form action
        session()->setFlash('product-curr-ids', $ids);

        $products = $this->getBatchEditProducts($ids);
        if (!count($products)) {
            return $this->show404();
        }

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(BatchEditProductPriceForm::class, 'product_batch_edit');
        }

        /**
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);
        /**
         * @var ColorModel $colorModel
         */
        $colorModel = container()->get(ColorModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);

        $this->setLayout($this->main_layout)->setTemplate('view/product/batch-edit-price');

        return $this->render(array_merge($data, [
            'ids' => $ids,
            'products' => $products,
            'colors' => $colorModel->get(['id', 'hex', 'name']),
            'units' => $unitModel->get(['id', 'title', 'sign']),
            'brands' => $brandModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
            'categories' => $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
        ]));
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            emitter()->addListener('remove.general.ajax:custom_handler', function (IEvent $event, ResourceHandler $resourceHandler) use ($id) {
                $event->stopPropagation();

                /**
                 * @var DBAuth $auth
                 */
                $auth = container()->get('auth_admin');

                if (!$auth->isLoggedIn()) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('عملیات غیر مجاز است!');
                    return false;
                }

                if ($auth->userHasRole(ROLE_DEVELOPER) || $auth->userHasRole(ROLE_SUPER_USER)) {
                    return true;
                }

                /**
                 * @var ProductModel $productModel
                 */
                $productModel = container()->get(ProductModel::class);
                $productModel->update([
                    'is_deleted' => DB_YES,
                ], 'id=:id', ['id' => $id]);

                return false;
            });

            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_PRODUCTS, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function pubStatusChange($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                $handler = new GeneralAjaxStatusHandler();
                $resourceHandler = $handler
                    ->setStatusCheckedMessage('نمایش محصول فعال شد.')
                    ->setStatusUncheckedMessage('نمایش محصول غیر فعال شد.')
                    ->handle(
                        BaseModel::TBL_PRODUCTS,
                        $id,
                        'publish',
                        input()->post('status')->getValue()
                    );
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function availabilityStatusChange($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                $handler = new GeneralAjaxStatusHandler();
                $resourceHandler = $handler
                    ->setStatusCheckedMessage('موجودی محصول فعال شد.')
                    ->setStatusUncheckedMessage('موجودی محصول غیر فعال شد.')
                    ->handle(
                        BaseModel::TBL_PRODUCTS,
                        $id,
                        'is_available',
                        input()->post('status')->getValue()
                    );
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getEditProductVariants($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                /**
                 * @var ProductModel $productModel
                 */
                $productModel = container()->get(ProductModel::class);
                /**
                 * @var ColorModel $colorModel
                 */
                $colorModel = container()->get(ColorModel::class);

                $productProperty = $productModel->getProductProperty($id);

                $resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->data([
                        'content' => $this->setTemplate('partial/admin/product/quick-edit')
                            ->render([
                                'product_properties' => $productProperty,
                                'colors' => $colorModel->get(['id', 'hex', 'name']),
                            ])
                    ]);
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function editProductVariants($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            // store previous info to update with
            session()->setFlash('product-quick-edit-curr-id', $id);

            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('تغییرات با موفقیت اعمال شد.')
                ->handle(QuickEditProductForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param array $_
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getPaginatedDatatable(...$_): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) {
                    $event->stopPropagation();

                    /**
                     * @var ProductModel $productModel
                     */
                    $productModel = container()->get(ProductModel::class);

                    $cols[] = 'pa.is_special';

                    $data = $productModel->getLimitedProduct($where, $bindValues, $order, $limit, $offset, ['pa.product_id'], $cols);
                    //-----
                    $recordsFiltered = $productModel->getLimitedProductCount($where, $bindValues);
                    $recordsTotal = $productModel->getLimitedProductCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    [
                        'db' => 'pa.product_id',
                        'db_alias' => 'chk_id',
                        'dt' => 'chkId',
                        'formatter' => function ($d) {
                            return $this->setTemplate('partial/admin/parser/product-checkbox')
                                ->render([
                                    'id' => $d,
                                ]);
                        }
                    ],
                    ['db' => 'pa.product_id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => 'pa.title',
                        'db_alias' => 'title',
                        'dt' => 'title',
                        'formatter' => function ($d, $row) {
                            if ($row['is_special'] == DB_YES) {
                                return $d . "<span class='badge badge-warning'>ویژه</span>";
                            }
                            return $d;
                        }
                    ],
                    ['db' => 'pa.brand_name', 'db_alias' => 'brand_name', 'dt' => 'brand_name'],
                    ['db' => 'pa.category_name', 'db_alias' => 'category_name', 'dt' => 'category_name'],
                    [
                        'db' => 'pa.stock_count',
                        'db_alias' => 'stock_count',
                        'dt' => 'in_stock',
                        'formatter' => function ($d) {
                            if ($d < MINIMUM_WARNING_STOCK_VALUE) {
                                return "<span class='text-danger'>{$d}</span>";
                            }
                            return "<span class='text-success'>{$d}</span>";
                        }
                    ],
                    [
                        'db' => 'pa.image',
                        'db_alias' => 'image',
                        'dt' => 'image',
                        'formatter' => function ($d, $row) {
                            return $this->setTemplate('partial/admin/parser/image-placeholder')
                                ->render([
                                    'img' => $d,
                                    'alt' => $row['title'],
                                ]);
                        }
                    ],
                    [
                        'db' => 'pa.publish',
                        'db_alias' => 'publish',
                        'dt' => 'status',
                        'formatter' => function ($d, $row) {
                            $status = $this->setTemplate('partial/admin/parser/status-changer')
                                ->render([
                                    'status' => $d,
                                    'row' => $row,
                                    'url' => url('ajax.product.status')->getRelativeUrl(),
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'pa.product_availability',
                        'db_alias' => 'product_availability',
                        'dt' => 'is_available',
                        'formatter' => function ($d, $row) {
                            $status = $this->setTemplate('partial/admin/parser/status-changer')
                                ->render([
                                    'status' => $d,
                                    'row' => $row,
                                    'url' => url('ajax.product.availability.status')->getRelativeUrl(),
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'pa.created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'created_at',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'dt' => 'quick_edit',
                        'formatter' => function ($row) {
                            return '<button type="button" class="btn btn-dark __item_product_quick_edit_btn" data-toggle="modal" ' .
                                ' data-target="#modal_form_quick_edit" data-ajax-quick-edit="' . $row['id'] . '">' .
                                'ویرایش سریع' .
                                '</button>';
                        },
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-product')
                                ->render([
                                    'row' => $row,
                                ]);
                            return $operations;
                        }
                    ],
                ];

                $response = DatatableHandler::handle($_POST, $columns);
            } else {
                response()->httpCode(403);
                $response = [
                    'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
                ];
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }

    /**
     * @param array $_
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getPaginatedSelect2(...$_): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('select2.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset) {
                    $event->stopPropagation();

                    /**
                     * @var ProductModel $productModel
                     */
                    $productModel = container()->get(ProductModel::class);

                    if (!empty($where)) {
                        $where .= ' AND ';
                    }

                    $where .= 'publish=:pub';
                    $bindValues['pub'] = DB_YES;

                    $data = $productModel->get($cols, $where, $bindValues, ['id DESC'], $limit, $offset);
                    //-----
                    $recordsTotal = $productModel->count($where, $bindValues);

                    return [$data, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 's2' => 'id'],
                    ['db' => 'title', 'db_alias' => 'title', 's2' => 'text', 'searchable' => true],
                    [
                        'db' => 'image',
                        'db_alias' => 'image',
                        's2' => 'image',
                        'formatter' => function ($d) {
                            return url('image.show', ['filename' => $d])->getRelativeUrl();
                        }
                    ],
                ];

                $response = Select2Handler::handle($_GET, $columns);
            } else {
                response()->httpCode(403);
                $response = [
                    'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
                ];
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function buyer($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        $product = $productModel->getFirst(['title'], 'id=:id', ['id' => $id]);

        if (0 === count($product)) {
            return $this->show404();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/product/buyer/view');
        return $this->render([
            'sub_title' => 'جزئیات محصول' . ' - ' . $product['title'],
            'product_id' => $id,
        ]);
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getBuyerUsersPaginatedDatatable($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($id) {
                    $event->stopPropagation();

                    /**
                     * @var OrderModel $orderModel
                     */
                    $orderModel = container()->get(OrderModel::class);

                    $cols[] = 'u.id AS user_id';

                    $data = $orderModel->getUsersFromProductId($id, $cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $orderModel->getUsersFromProductIdCount($id, $where, $bindValues);
                    $recordsTotal = $orderModel->getUsersFromProductIdCount($id);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'o.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'o.first_name', 'db_alias' => 'first_name', 'dt' => 'first_name'],
                    ['db' => 'o.last_name', 'db_alias' => 'last_name', 'dt' => 'last_name'],
                    ['db' => 'o.mobile', 'db_alias' => 'mobile', 'dt' => 'mobile'],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            if (!empty($row['user_id'])) {
                                $operations = $this->setTemplate('partial/admin/datatable/actions-product-user')
                                    ->render([
                                        'row' => [
                                            'id' => $row['user_id'],
                                        ],
                                    ]);
                            } else {
                                $operations = $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                            return $operations;
                        }
                    ],
                ];

                $response = DatatableHandler::handle($_POST, $columns);
            } else {
                response()->httpCode(403);
                $response = [
                    'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
                ];
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getBuyerOrdersPaginatedDatatable($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($id) {
                    $event->stopPropagation();

                    /**
                     * @var OrderModel $orderModel
                     */
                    $orderModel = container()->get(OrderModel::class);

                    $data = $orderModel->getUsersFromProductId($id, $cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $orderModel->getUsersFromProductIdCount($id, $where, $bindValues);
                    $recordsTotal = $orderModel->getUsersFromProductIdCount($id);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'o.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'o.code', 'db_alias' => 'code', 'dt' => 'code'],
                    ['db' => 'o.province', 'db_alias' => 'province', 'dt' => 'province'],
                    ['db' => 'o.city', 'db_alias' => 'city', 'dt' => 'city'],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-product-order')
                                ->render([
                                    'row' => $row,
                                ]);
                            return $operations;
                        }
                    ],
                ];

                $response = DatatableHandler::handle($_POST, $columns);
            } else {
                response()->httpCode(403);
                $response = [
                    'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
                ];
            }
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }

    /**
     * @param $ids
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function getBatchEditProducts($ids)
    {
        /**
         * @var $productModel ProductModel
         */
        $productModel = container()->get(ProductModel::class);

        $idsArr = explode('/', str_replace('\\', '/', $ids));
        $idInClause = '';
        $idBindParams = [];
        foreach ($idsArr as $k => $id) {
            $idInClause .= ":id{$k},";
            $idBindParams["id{$k}"] = $id;
        }
        $idInClause = rtrim($idInClause, ',');
        $products = $productModel->get([
            'title', 'image',
        ], 'id IN (' . $idInClause . ')', $idBindParams);

        return $products;
    }
}
