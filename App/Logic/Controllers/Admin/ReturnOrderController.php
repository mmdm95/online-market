<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\ReturnOrder\ReturnOrderRespondForm as AdminReturnOrderRespondForm;
use App\Logic\Forms\Admin\ReturnOrder\ReturnOrderStatusForm as AdminReturnOrderStatusForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ReturnOrderModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
use App\Logic\Utils\SMSUtil;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\SMS\Exceptions\SMSException;

class ReturnOrderController extends AbstractAdminController implements IDatatableController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws DependencyException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws NotFoundException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     */
    public function view()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/order/return-order/view');
        return $this->render();
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function detail($id)
    {
        /**
         * @var ReturnOrderModel $returnModel
         */
        $returnModel = container()->get(ReturnOrderModel::class);

        $returnOrder = $returnModel->getReturnOrders(
            'ro.id=:id',
            ['id' => $id],
            ['ro.id DESC'],
            1,
            0,
            [
                'ro.*',
                'u.id AS user_id',
                'u.username',
                'u.first_name AS user_first_name',
                'u.last_name AS user_last_name',
            ]
        );

        if (!count($returnOrder)) {
            return $this->show404();
        }
        $returnOrder = $returnOrder[0];

        $data = [];
        if (is_post()) {
            session()->setFlash('curr_return_order_detail_id', $id);
            session()->setFlash('curr_return_order_detail_code', $returnOrder['code']);
            session()->setFlash('curr_return_order_detail_order_code', $returnOrder['order_code']);

            $formHandler = new GeneralFormHandler();
            if (!is_null(input()->post('inp-return-order-status')->getValue())) {
                $data = $formHandler->handle(AdminReturnOrderStatusForm::class, 'return_order_status_handling');

                emitter()->addListener('form.general:success', function (IEvent $event, $data) use ($returnOrder) {
                    $event->stopPropagation();

                    $username = $returnOrder['username'];
                    $status = input()->post('inp-return-order-status', '')->getValue();

                    // send success order sms
                    $body = replaced_sms_body(SMS_TYPE_RETURN_ORDER_STATUS, [
                        SMS_REPLACEMENTS['code'] => $returnOrder['code'] ?? 'نامشخص',
                        SMS_REPLACEMENTS['status'] => RETURN_ORDER_STATUSES[$status] ?? 'نامشخص',
                    ]);

                    try {
                        $smsRes = SMSUtil::send([$username], $body);
                        SMSUtil::logSMS([$username], $body, $smsRes, SMS_LOG_TYPE_RETURN_ORDER_STATUS, SMS_LOG_SENDER_SYSTEM);
                    } catch (DependencyException|NotFoundException|SMSException $e) {
                        // do nothing
                    }
                });
            } elseif (!is_null(input()->post('inp-return-order-respond')->getValue())) {
                $data = $formHandler->handle(AdminReturnOrderRespondForm::class, 'return_order_respond_handling');
            }
        }

        $returnOrderItems = $returnModel->getReturnOrderItems(
            [
                'oi.*', 'roi.id AS return_id', 'roi.order_item_id', 'roi.is_accepted', 'roi.product_count AS return_count',
                'pa.image AS product_image', 'pa.code AS main_product_code',
            ],
            'ro.id=:id',
            ['id' => $id]
        );

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        $orderId = $orderModel->getFirst(['id'], 'code=:code', ['code' => $returnOrder['order_code']])['id'];

        $this->setLayout($this->main_layout)->setTemplate('view/order/return-order/detail');
        return $this->render(array_merge($data, [
            'sub_title' => 'جزيیات سفارش مرجوعی' . '-' . $returnOrder['code'],
            //
            'order_id' => $orderId,
            'return_order' => $returnOrder,
            'return_order_items' => $returnOrderItems,
        ]));
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
                     * @var ReturnOrderModel $returnModel
                     */
                    $returnModel = container()->get(ReturnOrderModel::class);

                    $cols[] = 'u.id AS main_user_id';

                    $data = $returnModel->getReturnOrders($where, $bindValues, $order, $limit, $offset, $cols);
                    //-----
                    $recordsFiltered = $returnModel->getReturnOrdersCount($where, $bindValues);
                    $recordsTotal = $returnModel->getReturnOrdersCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'ro.id', 'db_alias' => 'id', 'dt' => 'id'],
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
                    ['db' => 'ro.code', 'db_alias' => 'code', 'dt' => 'code'],
                    [
                        'dt' => 'count',
                        'formatter' => function ($row) {
                            /**
                             * @var ReturnOrderModel $returnOrder
                             */
                            $returnOrder = container()->get(ReturnOrderModel::class);
                            return $returnOrder->getReturnOrderItemsCount('return_code=:rc', ['rc' => $row['code']]);
                        }
                    ],
                    [
                        'db' => 'ro.status',
                        'db_alias' => 'status',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            return $this->setTemplate('partial/admin/parser/return-order-status')->render(['type' => $d]);
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
                        'db' => 'ro.requested_at',
                        'db_alias' => 'requested_at',
                        'dt' => 'req_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'db' => '(CASE WHEN (ro.status_changed_by IS NOT NULL) THEN CONCAT(uc.first_name, " ", uc.last_name) ELSE NULL END)',
                        'db_alias' => 'status_changer',
                        'dt' => 'status_changed_by',
                        'formatter' => function ($d) {
                            return $d ?: $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            return $this->setTemplate('partial/admin/datatable/actions-return-order')
                                ->render([
                                    'row' => $row,
                                ]);
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
        } catch (Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}
