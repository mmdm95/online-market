<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Wallet\WalletCharge;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\DepositTypeModel;
use App\Logic\Models\UserModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Models\WalletModel;
use App\Logic\Utils\Jdf;
use App\Logic\Utils\LogUtil;
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
use Sim\Utils\StringUtil;

class WalletController extends AbstractAdminController implements IDatatableController
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
    public function view()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/wallet/view');
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
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function detail($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);

        if (0 === $walletModel->count('id=:id', ['id' => $id])) {
            return $this->show404();
        }

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $wallet = $walletModel->getFirst(['username'], 'id=:id', ['id' => $id]);
        $currUser = $userModel->getFirst(['id', 'username', 'first_name', 'last_name'], 'username=:username', ['username' => $wallet['username']]);

        $this->setLayout($this->main_layout)->setTemplate('view/wallet/user-wallet');
        return $this->render([
            'username' => $wallet['username'],
            'sub_title' => 'جزئیات کیف پول' .
                '-' .
                ((!empty($currUser['first_name']) || !empty($currUser['last_name']))
                    ? (isset($currUser['id'])
                        ? '<a href="' . url('admin.user.view', ['id' => $currUser['id']])->getRelativeUrl() . '">' . trim($currUser['first_name'] . ' ' . $currUser['last_name']) . '</a>'
                        : trim($currUser['first_name'] . ' ' . $currUser['last_name']))
                    : (isset($currUser['id'])
                        ? '<a href="' . url('admin.user.view', ['id' => $currUser['id']])->getRelativeUrl() . '">' . $currUser['username'] . '</a>'
                        : $wallet['username'])),
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
     */
    public function charge($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);

        $wallet = $walletModel->getFirst(['username'], 'id=:id', ['id' => $id]);
        if (0 === count($wallet)) {
            return $this->show404();
        }

        $data = [];
        if (is_post()) {
            session()->setFlash('wallet_charge_curr_id', $id);
            session()->setFlash('wallet_charge_curr_username', $wallet['username']);

            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(WalletCharge::class, 'wallet_charge');
        }

        /**
         * @var DepositTypeModel $depositModel
         */
        $depositModel = container()->get(DepositTypeModel::class);
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $currUser = $userModel->getFirst(['id', 'username', 'first_name', 'last_name'], 'username=:username', ['username' => $wallet['username']]);
        $depositTypes = $depositModel->get(['id', 'title'], 'selectable=:sel', ['sel' => DB_YES]);

        $this->setLayout($this->main_layout)->setTemplate('view/wallet/charge');
        return $this->render(array_merge($data, [
            'deposit_types' => $depositTypes,
            'wallet_id' => $id,
            'sub_title' => 'شارژ کیف پول' .
                '-' .
                ((!empty($currUser['first_name']) || !empty($currUser['last_name']))
                    ? (isset($currUser['id'])
                        ? '<a href="' . url('admin.user.view', ['id' => $currUser['id']])->getRelativeUrl() . '">' . trim($currUser['first_name'] . ' ' . $currUser['last_name']) . '</a>'
                        : trim($currUser['first_name'] . ' ' . $currUser['last_name']))
                    : (isset($currUser['id'])
                        ? '<a href="' . url('admin.user.view', ['id' => $currUser['id']])->getRelativeUrl() . '">' . $currUser['username'] . '</a>'
                        : $wallet['username'])),
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
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var WalletModel $walletModel
             */
            $walletModel = container()->get(WalletModel::class);

            $username = $walletModel->getFirst(['username'], 'id=:id', ['id' => $id]);
            if (0 === count($username)) {
                $resourceHandler->errorMessage('شناسه آیتم کیف پول نامعتبر است.');
            } else {
                /**
                 * @var UserModel $userModel
                 */
                $userModel = container()->get(UserModel::class);

                $username = $username['username'];
                if (0 === $userModel->count('username=:username', ['username' => $username])) {
                    $handler = new GeneralAjaxRemoveHandler();
                    $resourceHandler = $handler->handle(BaseModel::TBL_WALLET, $id);
                } else {
                    $resourceHandler->errorMessage('امکان حذف آیتم کیف پول وجود ندارد، برای حذف کیف پول ابتدا باید کاربر را حذف کنید.');
                }
            }
        } else {
            response()->httpCode(403);
            $resourceHandler->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
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
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_READ)) {
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
                     * @var WalletModel $walletModel
                     */
                    $walletModel = container()->get(WalletModel::class);

                    $cols[] = 'u.id AS user_id';

                    $data = $walletModel->getWalletInfo($where, $bindValues, $order, $limit, $offset, $cols);
                    //-----
                    $recordsFiltered = $walletModel->getWalletInfoCount($where, $bindValues);
                    $recordsTotal = $walletModel->getWalletInfoCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'w.id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => '(CASE WHEN (u.id IS NOT NULL) THEN CONCAT(u.first_name, " ", u.last_name) ELSE w.username END)',
                        'db_alias' => 'full_name',
                        'dt' => 'user',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() .
                                    '">' .
                                    $d .
                                    '</a>';
                            }
                            return $d;
                        }
                    ],
                    [
                        'db' => 'balance',
                        'db_alias' => 'balance',
                        'dt' => 'balance',
                        'formatter' => function ($d) {
                            return number_format((int)StringUtil::toEnglish($d));
                        }
                    ],
                    [
                        'db' => 'is_available',
                        'db_alias' => 'is_available',
                        'dt' => 'is_available',
                        'formatter' => function ($d) {
                            return $this->setTemplate('partial/admin/parser/active-status')->render([
                                'status' => $d,
                            ]);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            return $this->setTemplate('partial/admin/datatable/actions-wallet')
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
        } catch (\Exception $e) {
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }

    /**
     * @param $username
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getDetailPaginatedDatatable($username)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($username) {
                    $event->stopPropagation();

                    /**
                     * @var WalletFlowModel $flowModel
                     */
                    $flowModel = container()->get(WalletFlowModel::class);

                    $cols[] = 'u.id AS user_id';

                    if (!empty($where)) {
                        $where .= ' AND wf.username=:username';
                    } else {
                        $where = 'wf.username=:username';
                    }
                    $bindValues = array_merge($bindValues, [
                        'username' => $username,
                    ]);

                    $data = $flowModel->getWalletFlowInfo($where, $bindValues, $order, $limit, $offset, $cols);
                    //-----
                    $recordsFiltered = $flowModel->getWalletFlowInfoCount($where, $bindValues);
                    $recordsTotal = $flowModel->getWalletFlowInfoCount('wf.username=:username', ['username' => $username]);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'wf.id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => 'wf.deposit_price',
                        'db_alias' => 'deposit_price',
                        'dt' => 'price',
                        'formatter' => function ($d) {
                            return number_format((int)StringUtil::toEnglish($d));
                        }
                    ],
                    ['db' => 'wf.deposit_type_title', 'db_alias' => 'deposit_type_title', 'dt' => 'description'],
                    [
                        'db' => 'wf.deposit_at',
                        'db_alias' => 'deposit_at',
                        'dt' => 'deposit_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'db' => '(CASE WHEN (u.id IS NOT NULL) THEN CONCAT(u.first_name, " ", u.last_name) ELSE wf.username END)',
                        'db_alias' => 'full_name',
                        'dt' => 'deposit_by',
                        'formatter' => function ($d, $row) {
                            if (!empty($row['user_id'])) {
                                return '<a href="' .
                                    url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() .
                                    '">' .
                                    $d .
                                    '</a>';
                            }
                            return $d;
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
