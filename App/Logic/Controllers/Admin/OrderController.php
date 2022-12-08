<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\ChangeInvoiceStatus;
use App\Logic\Forms\Admin\ChangeSendStatus;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\SMSUtil;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class OrderController extends AbstractAdminController implements IDatatableController
{
    /**
     * @param $status
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function view($status = null)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_ORDER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        if (!empty($status)) {
            /**
             * @var OrderBadgeModel $badgeModel
             */
            $badgeModel = container()->get(OrderBadgeModel::class);
            $badge = $badgeModel->getFirst(['title'], 'code=:code', ['code' => $status]);
        }

        $this->setLayout($this->main_layout)->setTemplate('view/order/view');
        return $this->render([
            'status' => $badge['title'] ?? '',
        ]);
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
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\SMS\Exceptions\SMSException
     */
    public function detail($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $currId = $auth->getCurrentUser()['id'] ?? null;
        if (empty($currId) || $id != $currId) {
            if (!$auth->isAllow(RESOURCE_ORDER, IAuth::PERMISSION_READ)) {
                show_403();
            }
        }

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        if (0 === $orderModel->count('id=:id', ['id' => $id])) {
            return $this->show404();
        }

        $data = [];
        if (is_post()) {
            session()->setFlash('curr_order_detail_id', $id);

            if (!is_null(input()->post('changeInvoiceStatusBtn')->getValue())) {
                $formHandler = new GeneralFormHandler();
                $data = $formHandler->handle(ChangeInvoiceStatus::class, 'invoice_change');
            } elseif (!is_null(input()->post('changeSendStatusBtn')->getValue())) {
                $formHandler = new GeneralFormHandler();
                $data = $formHandler->handle(ChangeSendStatus::class, 'send_change');

                $username = session()->getFlash('send.status.username');

                if (!count($data['send_change_errors'] ?? []) && !is_null($username)) {
                    // send sms
                    $body = replaced_sms_body(SMS_TYPE_ORDER_STATUS, [
                        SMS_REPLACEMENTS['status'] => session()->getFlash('send.status.title', ''),
                        SMS_REPLACEMENTS['orderCode'] => session()->getFlash('send.status.order_code', ''),
                    ]);
                    $smsRes = SMSUtil::send([$username], $body);
                    SMSUtil::logSMS([$username], $body, $smsRes, SMS_LOG_TYPE_ORDER_STATUS, SMS_LOG_SENDER_USER);
                }
            }
        }

        /**
         * @var OrderBadgeModel $badgeModel
         */
        $badgeModel = container()->get(OrderBadgeModel::class);
        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);

        $order = $orderModel->getOrders('o.id=:id', ['id' => $id], ['o.id DESC'], 1)[0];
        $orderItems = $orderModel->getOrderItems([
            'oi.*', 'p.image AS product_image'
        ], 'oi.order_code=:code', ['code' => $order['code']]);
        $order['payment_code'] = $gatewayModel->getFirst(['payment_code'], 'order_code=:oc AND method_type=:mt', [
            'oc' => $order['code'],
            'mt' => $order['method_type'],
        ])['payment_code'] ?? null;

        $badges = $badgeModel->get(['code', 'title', 'color'], 'is_deleted=:del', ['del' => DB_NO]);

        $this->setLayout($this->main_layout)->setTemplate('view/order/detail');
        return $this->render(array_merge($data, [
            'order' => $order,
            'order_items' => $orderItems,
            'badges' => $badges,
            'order_id' => $id,
            'sub_title' => 'جزيیات سفارش' . '-' . $order['code'],
        ]));
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getInfo($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_ORDER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var OrderModel $orderModel
             */
            $orderModel = container()->get(OrderModel::class);
            $info = $orderModel->getFirst([
                'user_national_number', 'receiver_name', 'receiver_mobile', 'province', 'city', 'postal_code', 'address'
            ], 'id=:id', ['id' => $id]);

            if (count($info)) {
                $resourceHandler->data($info);
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('هیچ اظلاعاتی برای شناسه سفارش وارد شده وجود ندارد.');
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

                    $cols[] = 'u.id AS main_user_id';
                    $cols[] = 'o.send_status_code';
                    $cols[] = 'o.send_status_color';

                    $data = $orderModel->getOrders($where, $bindValues, $order, $limit, $offset, $cols);
                    //-----
                    $recordsFiltered = $orderModel->getOrdersCount($where, $bindValues);
                    $recordsTotal = $orderModel->getOrdersCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'o.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'o.code', 'db_alias' => 'code', 'dt' => 'factor_code'],
                    [
                        'db' => '(CASE WHEN (u.id IS NOT NULL) THEN CONCAT(u.first_name, " ", u.last_name) ELSE CONCAT(o.first_name, " ", o.last_name) END)',
                        'db_alias' => 'full_name',
                        'dt' => 'user',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['main_user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['main_user_id']])->getRelativeUrl() .
                                    '">' .
                                    $d .
                                    '</a>';
                            }
                            return $d;
                        }
                    ],
                    [
                        'db' => '(CASE WHEN (u.id IS NOT NULL) THEN u.username ELSE o.mobile END)',
                        'db_alias' => 'user_mobile',
                        'dt' => 'user_mobile',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['main_user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['main_user_id']])->getRelativeUrl() .
                                    '">' .
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
                        'db' => 'o.payment_status',
                        'db_alias' => 'payment_status',
                        'dt' => 'order_status',
                        'search_on' => PAYMENT_STATUSES,
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
                        'db' => 'o.ordered_at',
                        'db_alias' => 'ordered_at',
                        'dt' => 'order_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'db' => 'send_status_title',
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
}