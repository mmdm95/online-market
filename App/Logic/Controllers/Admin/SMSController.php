<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Models\SMSLogsModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class SMSController extends AbstractAdminController
{
    /**
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
    public function logs()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->userHasRole(ROLE_DEVELOPER) && !$auth->userHasRole(ROLE_SUPER_USER)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/sms/logs');
        return $this->render();
    }

    /**
     * @param array $_
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getLogsPaginatedDatatable(...$_): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->userHasRole(ROLE_DEVELOPER) && !$auth->userHasRole(ROLE_SUPER_USER)) {
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
                     * @var SMSLogsModel $smsLogsModel
                     */
                    $smsLogsModel = container()->get(SMSLogsModel::class);

                    $cols[] = 'sl.sent_by';
                    $cols[] = 'sl.sender';
                    $cols[] = 'u.id AS user_id';

                    $data = $smsLogsModel->getLogsDetailed($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $smsLogsModel->getLogsDetailedCount($where, $bindValues);
                    $recordsTotal = $smsLogsModel->getLogsDetailedCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'sl.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'sl.sms_panel_number', 'db_alias' => 'panel_number', 'dt' => 'number'],
                    ['db' => 'sl.sms_panel_name', 'db_alias' => 'panel_name', 'dt' => 'name'],
                    [
                        'db' => 'sl.type',
                        'db_alias' => 'type',
                        'dt' => 'type',
                        'formatter' => function ($d) {
                            return $this->setTemplate('partial/admin/parser/status-creation')
                                ->render([
                                    'status' => $d,
                                    'switch' => [
                                        [
                                            'status' => SMS_LOG_TYPE_REGISTER,
                                            'text' => 'ثبت نام',
                                            'badge' => 'badge-success',
                                        ],
                                        [
                                            'status' => SMS_LOG_TYPE_RECOVER_PASS,
                                            'text' => 'بازیابی کلمه عبور',
                                            'badge' => 'badge-warning',
                                        ],
                                        [
                                            'status' => SMS_LOG_TYPE_BUY,
                                            'text' => 'خرید',
                                            'badge' => 'badge-info',
                                        ],
                                        [
                                            'status' => SMS_LOG_TYPE_ORDER_STATUS,
                                            'text' => 'وضعیت سفارش',
                                            'badge' => 'badge-dark',
                                        ],
                                        [
                                            'status' => SMS_LOG_TYPE_WALLET_CHARGE,
                                            'text' => 'شارژ کیف پول',
                                            'badge' => 'badge-light',
                                        ],
                                        [
                                            'status' => SMS_LOG_TYPE_ORDER_SUCCESS,
                                            'text' => 'موفقیت ثبت سفارش',
                                            'badge' => 'badge-success',
                                        ],
                                        [
                                            'status' => SMS_LOG_TYPE_ORDER_NOTIFY,
                                            'text' => 'اطلاع رسانی از سفارش',
                                            'badge' => 'badge-danger',
                                        ],
                                        [
                                            'status' => SMS_LOG_TYPE_OTHERS,
                                            'text' => 'سایر موارد',
                                            'badge' => 'badge-light',
                                        ],
                                    ],
                                ]);
                        },
                    ],
                    [
                        'db' => 'sl.status',
                        'db_alias' => 'status',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            return $this->setTemplate('partial/admin/parser/status-creation')
                                ->render([
                                    'status' => $d,
                                    'switch' => [
                                        [
                                            'status' => DB_YES,
                                            'text' => 'موفق',
                                            'badge' => 'badge-success',
                                        ],
                                        [
                                            'status' => DB_NO,
                                            'text' => 'ناموفق',
                                            'badge' => 'badge-danger',
                                        ],
                                    ],
                                ]);
                        },
                    ],
                    ['db' => 'sl.message', 'db_alias' => 'message', 'dt' => 'body'],
                    ['db' => 'sl.numbers', 'db_alias' => 'numbers', 'dt' => 'numbers'],
                    ['db' => 'sl.code', 'db_alias' => 'code', 'dt' => 'code'],
                    ['db' => 'sl.result_msg', 'db_alias' => 'msg', 'dt' => 'res_msg'],
                    [
                        'db' => '(CASE WHEN (sl.sent_by IS NOT NULL) THEN CONCAT(u.first_name, " ", u.last_name) ELSE "" END)',
                        'db_alias' => 'full_name',
                        'dt' => 'sender',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['sent_by']) && !empty($row['user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() .
                                    '" target="__blank">' .
                                    $d .
                                    '</a>';
                            } elseif ($row['sender'] == SMS_LOG_SENDER_SYSTEM) {
                                return '<span class="text-teal">سیستم</span>';
                            }

                            return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        },
                    ],
                    [
                        'db' => 'sl.sent_at',
                        'db_alias' => 'sent_at',
                        'dt' => 'sent_at',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
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
