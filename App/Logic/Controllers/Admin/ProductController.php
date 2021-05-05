<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Product\AddProductForm;
use App\Logic\Forms\Admin\Product\EditProductForm;
use App\Logic\Handlers\DatatableHandler;
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
use App\Logic\Models\ProductModel;
use App\Logic\Models\UnitModel;
use App\Logic\Utils\Jdf;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ProductController extends AbstractAdminController implements IDatatableController, ISelect2Controller
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws IDBException
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
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws IDBException
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
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws IDBException
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
            'colors' => $colorModel->get(['hex', 'name']),
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
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws IDBException
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

        $product = $productModel->get(['title'], 'id=:id', ['id' => $id]);

        if (0 == count($product)) {
            return $this->show404();
        }

        // store previous hex to check for duplicate
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
            'colors' => $colorModel->get(['hex', 'name']),
            'units' => $unitModel->get(['id', 'title', 'sign']),
            'brands' => $brandModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
            'categories' => $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
        ]));
    }

    /**
     * @param $id
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws IDBException
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

                if ($auth->hasRole(ROLE_DEVELOPER) || $auth->hasRole(ROLE_SUPER_USER)) {
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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

                    $data = $productModel->getLimitedProduct($where, $bindValues, $order, $limit, $offset, [], $cols);
                    //-----
                    $recordsFiltered = $productModel->getLimitedProductCount($where, $bindValues);
                    $recordsTotal = $productModel->getLimitedProductCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
                emitter()->addListener('select2.ajax:load', function (IEvent $event, $cols, $limit, $offset) {
                    $event->stopPropagation();

                    /**
                     * @var ProductModel $productModel
                     */
                    $productModel = container()->get(ProductModel::class);

                    $where = 'publish=:pub';
                    $bindValues = ['pub' => DB_YES];

                    $data = $productModel->get($cols, $where, $bindValues, ['id DESC'], $limit, $offset);
                    //-----
                    $recordsTotal = $productModel->count();

                    return [$data, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 's2' => 'id'],
                    ['db' => 'title', 'db_alias' => 'title', 's2' => 'text'],
                    ['db' => 'image', 'db_alias' => 'image', 's2' => 'image'],
                ];

                $response = Select2Handler::handle($_GET, $columns);
            } else {
                response()->httpCode(403);
                $response = [
                    'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
                ];
            }
        } catch (\Exception $e) {
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
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws IDBException
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}