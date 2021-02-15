<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\ReturnOrderModel;
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

class ReturnOrderController extends AbstractAdminController implements IDatatableController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function detail($id)
    {
        /**
         * @var ReturnOrderModel $returnModel
         */
        $returnModel = container()->get(ReturnOrderModel::class);

//        if (0 === $returnModel->count('id=:id', ['id' => $id])) {
//            return $this->show404();
//        }

        $data = [];
//        if (is_post()) {
//            session()->setFlash('curr_return_order_detail_id', $id);

//            if (!is_null(input()->post('')->getValue())) {
//                $formHandler = new GeneralFormHandler();
//                $data = $formHandler->handle(::class, '');
//            }
//        }

        $return = [];

        $returnItems = [];

        $this->setLayout($this->main_layout)->setTemplate('view/order/return-order/detail');
        return $this->render(array_merge($data, [
            'return_order' => $return,
            'return_order_items' => $returnItems,
            'return_order_id' => $id,
            'sub_title' => 'جزيیات سفارش مرجوعی',// . '-' . $return['code'],
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
                                    url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() .
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
                            $this->setTemplate('partial/admin/parser/status-creation');
                            return $this->render([
                                'status' => $d,
                                'switch' => [
                                    [
                                        'status' => RETURN_ORDER_STATUS_CHECKING,
                                        'text' => 'در حال بررسی',
                                        'badge' => 'badge-primary',
                                    ],
                                    [
                                        'status' => RETURN_ORDER_STATUS_ACCEPT,
                                        'text' => 'تایید شده',
                                        'badge' => 'badge-success',
                                    ],
                                    [
                                        'status' => RETURN_ORDER_STATUS_DENIED,
                                        'text' => 'تایید نشده',
                                        'badge' => 'badge-danger',
                                    ],
                                    [
                                        'status' => RETURN_ORDER_STATUS_SENDING,
                                        'text' => 'در حال ارسال مرسوله',
                                        'badge' => 'bg-indigo-400',
                                    ],
                                    [
                                        'status' => RETURN_ORDER_STATUS_RECEIVED,
                                        'text' => 'مرسوله دریافت شد',
                                        'badge' => 'bg-info',
                                    ],
                                    [
                                        'status' => RETURN_ORDER_STATUS_MONEY_RETURNED,
                                        'text' => 'مبلغ برگشت داده شد',
                                        'badge' => 'bg-success',
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
                            return $d ?: $this->setTemplate('admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-return-order')
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