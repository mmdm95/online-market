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
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ColorModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UnitModel;
use App\Logic\Utils\Jdf;
use Jenssegers\Agent\Agent;
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

class ProductController extends AbstractAdminController implements IDatatableController
{
    /**
     * @return string
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/product/view');
        return $this->render();
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
     */
    public function add()
    {
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
            'colors' => $colorModel->get(['hex', 'name'], 'publish=:pub', ['pub' => DB_YES]),
            'units' => $unitModel->get(['id', 'title', 'sign']),
            'brands' => $brandModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]),
            'categories' => $categoryModel->get(['id', 'name'], 'publish=:pub AND level=:lvl', ['pub' => DB_YES, 'lvl' => 3]),
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
     */
    public function edit($id)
    {
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

        // get all products with its values and add to product array
        // ...

        $this->setLayout($this->main_layout)->setTemplate('view/product/edit');
        return $this->render(array_merge($data, [
            'product' => $product,
        ]));
    }

    /**
     * @param $id
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function remove($id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            // add custom handler event functionality
            // ...

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
     */
    public function pubStatusChange($id)
    {
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
     */
    public function availabilityStatusChange($id)
    {
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
     */
    public function getPaginatedDatatable(...$_): void
    {
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
                        'db' => 'pa.is_available',
                        'db_alias' => 'is_available',
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
}