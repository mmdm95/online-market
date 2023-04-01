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
use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\ReportQBUtil;
use App\Logic\Utils\ReportUtil;
use http\Client\Curl\User;
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

class ReportOrderController extends AbstractAdminController implements
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

        $this->setLayout($this->main_layout)->setTemplate('view/report/order');
        return $this->render([
            'query_builder' => ReportQBUtil::getOrderQB(),
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
        if (!$auth->isAllow(RESOURCE_REPORT_ORDER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        $qb = input()->post('filtered_qb')->getValue();
        $qb = json_decode($qb, true);
        if (!empty($qb)) {
            session()->set(SESSION_QUERY_BUILDER_ORDER, $qb);
        } else {
            session()->remove(SESSION_QUERY_BUILDER_ORDER);
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
        if (!$auth->isAllow(RESOURCE_REPORT_ORDER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();
        session()->remove(SESSION_QUERY_BUILDER_ORDER);
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
        ReportUtil::exportOrdersExcel($where, $bindValues);
    }

    /**
     * @param $id
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Mpdf\MpdfException
     */
    public function exportPdfOne($id)
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);

        $order = $orderModel->getFirst(['*'], 'id=:id', ['id' => $id]);

        if (count($order)) {
            $gatewayInfo = $gatewayModel->get(['*'], 'order_code=:code', ['code' => $order['code']], ['issue_date DESC']);
            $orderItems = $orderModel->getOrderItems(['oi.*'], 'order_code=:code', ['code' => $order['code']]);

            $order['info']['successful'] = [];
            $order['info']['failed'] = [];

            // filter successful order info first then if there is nothing, find last failed info
            foreach ($gatewayInfo as $info) {
                if (DB_YES == $info['is_success']) {
                    $order['info']['successful'] = $info;
                } else {
                    $order['info']['failed'] = $info;
                }

                if (!empty($order['info']['successful']) && !empty($order['info']['failed'])) break;
            }

            if (count($orderItems)) {
                $html = $this
                    ->setLayout($this->report_layout)
                    ->setTemplate('partial/admin/report-templates/order-pdf')
                    ->render([
                        'title' => 'گزارش آیتم‌های سفارش به شماره ' . $order['code'],
                        'order' => $order,
                        'items' => $orderItems,
                    ]);

                $filename = 'آیتم‌های سفارش شماره ';
                $filename .= $order['code'] . ' ';
                $filename .= Jdf::jdate(REPORT_TIME_FORMAT);

                ReportUtil::exportPdf($filename, $html, config()->get('settings.title.value'));
            } else {
                show_500('هیچ سفارشی پیدا نشد!');
            }
        } else {
            show_500('هیچ سفارشی پیدا نشد!');
        }
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
        if (!$auth->isAllow(RESOURCE_ORDER, IAuth::PERMISSION_READ)) {
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
                     * @var OrderModel $orderModel
                     */
                    $orderModel = container()->get(OrderModel::class);

                    $cols[] = 'oa.user_id AS main_user_id';
                    $cols[] = 'oa.send_status_code';
                    $cols[] = 'oa.send_status_color';

                    if (!empty($where)) {
                        $where .= ' AND ';
                    }

                    [$newWhere, $newBindValues] = $this->getQBFiltered();
                    $where .= $newWhere;
                    $bindValues = array_merge($bindValues, $newBindValues);

                    $where = trim(trim($where), 'AND');

                    $data = $orderModel->getLimitedOrder($where, $bindValues, $order, $limit, $offset, ['oa.code'], $cols);
                    //-----
                    $recordsFiltered = $orderModel->getLimitedOrderCount($where, $bindValues);
                    $recordsTotal = $orderModel->getLimitedOrderCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'oa.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'oa.code', 'db_alias' => 'code', 'dt' => 'factor_code'],
                    [
                        'db' => '(CASE WHEN (oa.main_user_id IS NOT NULL) THEN CONCAT(oa.main_first_name, " ", oa.main_last_name) ELSE CONCAT(oa.first_name, " ", oa.last_name) END)',
                        'db_alias' => 'full_name',
                        'dt' => 'user',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['main_user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['main_user_id']])->getRelativeUrl() .
                                    '" target="__blank">' .
                                    $d .
                                    '</a>';
                            }
                            return $d;
                        }
                    ],
                    [
                        'db' => '(CASE WHEN (oa.main_user_id IS NOT NULL) THEN oa.main_username ELSE oa.mobile END)',
                        'db_alias' => 'user_mobile',
                        'dt' => 'user_mobile',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['main_user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['main_user_id']])->getRelativeUrl() .
                                    '" target="__blank">' .
                                    $d .
                                    '</a>';
                            }
                            return $d;
                        }
                    ],
                    [
                        'dt' => 'info',
                        'formatter' => function ($row) {
                            return '<button type="button" class="btn btn-dark __item_order_info_btn" data-toggle="modal" ' .
                                ' data-target="#modal_form_receiver_detail" data-ajax-order-info="' . $row['id'] . '">' .
                                'مشاهده' .
                                '</button>';
                        }
                    ],
                    [
                        'db' => 'oa.payment_status',
                        'db_alias' => 'payment_status',
                        'dt' => 'order_status',
                        'formatter' => function ($d) {
                            $this->setTemplate('partial/admin/parser/status-creation');
                            return $this->render([
                                'status' => $d,
                                'switch' => [
                                    [
                                        'status' => PAYMENT_STATUS_SUCCESS,
                                        'text' => 'پرداخت شده',
                                        'badge' => 'badge-success',
                                    ],
                                    [
                                        'status' => PAYMENT_STATUS_FAILED,
                                        'text' => 'پرداخت ناموفق',
                                        'badge' => 'badge-danger',
                                    ],
                                    [
                                        'status' => PAYMENT_STATUS_WAIT_VERIFY,
                                        'text' => 'در انتظار تایید',
                                        'badge' => 'badge-warning',
                                    ],
                                    [
                                        'status' => PAYMENT_STATUS_WAIT,
                                        'text' => 'در انتظار پرداخت',
                                        'badge' => 'badge-primary',
                                    ],
                                    [
                                        'status' => PAYMENT_STATUS_NOT_PAYED,
                                        'text' => 'پرداخت نشده',
                                        'badge' => 'badge-danger',
                                    ],
                                ],
                            ]);
                        }
                    ],
                    [
                        'db' => 'oa.ordered_at',
                        'db_alias' => 'ordered_at',
                        'dt' => 'order_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'db' => 'oa.send_status_title',
                        'db_alias' => 'send_status_title',
                        'dt' => 'status',
                        'formatter' => function ($d, $row) {
                            /**
                             * @var OrderBadgeModel $badgeModel
                             */
                            $badgeModel = container()->get(OrderBadgeModel::class);

                            $badges = $badgeModel->get(['code', 'color', 'title'], 'is_deleted=:del', ['del' => DB_NO]);
                            $badges = array_map(function ($value) {
                                $arr = [];
                                $arr['status'] = $value['code'];
                                $arr['text'] = $value['title'];
                                $arr['style'] = 'background-color: ' . $value['color'] . '; color:' . get_color_from_bg($value['color']);
                                return $arr;
                            }, $badges);

                            $this->setTemplate('partial/admin/parser/status-creation');
                            return $this->render([
                                'status' => $row['send_status_code'],
                                'switch' => [$badges],
                                'default' => [
                                    'text' => $d,
                                    'style' => 'background-color: ' . $row['send_status_color'] . '; color:' . get_color_from_bg($row['send_status_color']),
                                ],
                            ]);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-order')
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
     */
    private function getQBFiltered(): array
    {
        $where = '';
        $bindValues = [];

        // use query builder sql and params
        $qb = session()->get(SESSION_QUERY_BUILDER_ORDER);
        [$newWhere, $newBind] = ReportQBUtil::getNormalizedQBStatement($qb);

        if (!empty(trim($where))) {
            $where .= ' AND ';
        }
        $where .= $newWhere;
        $bindValues = array_merge($bindValues, $newBind);

        return [$where, $bindValues];
    }
}
