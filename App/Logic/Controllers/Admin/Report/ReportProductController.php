<?php

namespace App\Logic\Controllers\Admin\Report;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Interfaces\Report\IReporter;
use App\Logic\Interfaces\Report\IReportExcel;
use App\Logic\Interfaces\Report\IReportFilter;
use App\Logic\Interfaces\Report\IReportPdf;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\ReportQBUtil;
use App\Logic\Utils\ReportUtil;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ReportProductController extends AbstractAdminController implements
    IDatatableController,
    IReporter,
    IReportFilter,
    IReportExcel,
    IReportPdf
{
    /**
     * @var string
     */
    protected $report_layout = 'admin-report';

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    public function report()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/report/product');
        return $this->render([
            'query_builder' => ReportQBUtil::getProductQB(),
        ]);
    }

    /**
     * Store filter in a session to fetch it when want to show in table
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    public function filterReport()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        $qb = input()->post('filtered_qb')->getValue();
        $qb = json_decode($qb, true);
        if (!empty($qb)) {
            session()->set(SESSION_QUERY_BUILDER_PRODUCT, $qb);
        } else {
            session()->remove(SESSION_QUERY_BUILDER_PRODUCT);
        }

        $resourceHandler->type(RESPONSE_TYPE_SUCCESS);
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * Remove stored filter from session
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    public function filterClear()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();
        session()->remove(SESSION_QUERY_BUILDER_PRODUCT);
        $resourceHandler->type(RESPONSE_TYPE_SUCCESS);
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Auth\Interfaces\IDBException
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\File\Interfaces\IFileException
     */
    public function exportExcel()
    {
        $auth = auth_admin();
        if (!$auth->isAllow(RESOURCE_REPORT_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        [$where, $bindValues] = $this->getQBFiltered();
        ReportUtil::exportProductsExcel($where, $bindValues);
    }

    public function exportPdfOne($id)
    {
        not_implemented_yet();
    }

    /**
     * @param array $_
     * @return void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
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

                    if (!empty($where)) {
                        $where .= ' AND ';
                    }

                    [$newWhere, $newBindValues] = $this->getQBFiltered();
                    $where .= $newWhere;
                    $bindValues = array_merge($bindValues, $newBindValues);

                    $where = trim(trim($where), 'AND');

                    $data = $productModel->getLimitedProduct($where, $bindValues, $order, $limit, $offset, ['pa.product_id'], $cols);
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
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }

    /**
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    private function getQBFiltered(): array
    {
        $auth = auth_admin();

        $where = '';
        $bindValues = [];

        if (!$auth->userHasRole(ROLE_DEVELOPER)) {
            $where .= ' pa.is_deleted<>:del';
            $bindValues = array_merge($bindValues, [
                'del' => DB_YES,
            ]);
        }

        // use query builder sql and params
        $qb = session()->get(SESSION_QUERY_BUILDER_PRODUCT);
        [$newWhere, $newBind] = ReportQBUtil::getNormalizedQBStatement($qb);

        if (!empty(trim($where))) {
            $where .= ' AND ';
        }
        $where .= $newWhere;
        $bindValues = array_merge($bindValues, $newBind);

        return [$where, $bindValues];
    }
}