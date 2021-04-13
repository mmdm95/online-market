<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\User\AddUserForm;
use App\Logic\Forms\Admin\User\EditUserForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\RoleModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\Jdf;
use function DI\get;
use Jenssegers\Agent\Agent;
use ReflectionException;
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
use Sim\Utils\ArrayUtil;
use Tests\FakeData;

class UserController extends AbstractAdminController implements IDatatableController
{
    /**
     * @param null $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws IDBException
     */
    public function view($id = null)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $data = [];

        if (!is_null($id)) {
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);

            $user = $userModel->getFirst(['*'], 'id=:id', ['id' => $id]);

            if (!count($user)) {
                return $this->show404();
            }

            $userRoles = $userModel->getUserRoles($user['id'], null, [], ['r.description']);
            $userRoles = array_map(function ($value) {
                return $value['description'];
            }, $userRoles);
            $user['roles'] = $userRoles;

            // send needed data to page
            $data['user'] = $user;

            $this->setLayout($this->main_layout)->setTemplate('view/user/user-profile');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/user/view');
        }

        return $this->render($data);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws IDBException
     */
    public function add()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $data = [];

        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddUserForm::class, 'user_add');
        }

        /**
         * @var RoleModel $roleModel
         */
        $roleModel = container()->get(RoleModel::class);
        $roles = $roleModel->get(['name', 'description', 'is_admin'], 'show_to_user=:stu', ['stu' => DB_YES]);

        $this->setLayout($this->main_layout)->setTemplate('view/user/add');
        return $this->render(array_merge($data, [
            'roles' => $roles,
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
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws IDBException
     */
    public function edit($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $currId = $auth->getCurrentUser()['id'] ?? null;
        if (empty($currId) || $id != $currId) {
            if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_UPDATE)) {
                show_403();
            }
        }

        $data = [];

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $user = $userModel->get(['username'], 'id=:id', ['id' => $id]);

        if (0 === count($user)) {
            return $this->show404();
        }

        // store previous username to check for duplicate
        session()->setFlash('prev-username', $user[0]['username']);

        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditUserForm::class, 'user_edit');
        }

        $user = $userModel->getFirst(['*'], 'id=:id', ['id' => $id]);
        $userRoles = $userModel->getUserRoles($user['id'], null, [], ['r.name']);
        $userRoles = array_map(function ($value) {
            return $value['name'];
        }, $userRoles);
        $user['roles'] = $userRoles;

        /**
         * @var RoleModel $roleModel
         */
        $roleModel = container()->get(RoleModel::class);
        $roles = $roleModel->get(['name', 'description', 'is_admin'], 'show_to_user=:stu', ['stu' => DB_YES]);

        $this->setLayout($this->main_layout)->setTemplate('view/user/edit');
        return $this->render(array_merge($data, [
            'user' => $user,
            'roles' => $roles,
        ]));
    }

    /**
     * @param $id
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ReflectionException
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
        if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_USERS, $id);
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function getPaginatedDatatable(...$_): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_READ)) {
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
                     * @var UserModel $userModel
                     */
                    $userModel = container()->get(UserModel::class);
                    /**
                     * @var DBAuth $auth
                     */
                    $auth = container()->get('auth_admin');

                    if (!empty($where)) {
                        $where .= ' AND u.is_deleted<>:del';
                        if (!$auth->hasRole(ROLE_DEVELOPER)) {
                            $where .= ' AND u.is_hidden<>:hidden';
                        }
                    } else {
                        $where = 'u.is_deleted<>:del';
                        if (!$auth->hasRole(ROLE_DEVELOPER)) {
                            $where .= ' AND u.is_hidden<>:hidden';
                        }
                    }
                    $bindValues = array_merge($bindValues, [
                        'del' => DB_YES,
                    ]);
                    if (!$auth->hasRole(ROLE_DEVELOPER)) {
                        $bindValues = array_merge($bindValues, [
                            'hidden' => DB_YES,
                        ]);
                    }

                    $data = $userModel->getUsers($cols, $where, $bindValues, $limit, $offset, $order);
                    // get user roles and put it to user object
                    foreach ($data as $k => &$user) {
                        $user['role_name'] = $userModel->getUserRoles($user['id'], 'r.show_to_user=:stu', [
                            'stu' => DB_YES,
                        ], ['r.description']);
                    }
                    //-----
                    $recordsFiltered = $userModel->getUsersCount($where, $bindValues);
                    $recordsTotal = $userModel->getUsersCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'u.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'u.first_name', 'db_alias' => 'first_name', 'dt' => 'first_name'],
                    ['db' => 'u.last_name', 'db_alias' => 'last_name', 'dt' => 'last_name'],
                    [
                        'db' => 'r.name',
                        'db_alias' => 'role_name',
                        'dt' => 'roles',
                        'formatter' => function ($d) {
                            $roles = '';
                            while ($role = array_shift($d)) {
                                $roles .= $role['description'] . (count($d) >= 1 ? ',' : '');
                            }
                            return $roles;
                        }
                    ],
                    ['db' => 'u.username', 'db_alias' => 'username', 'dt' => 'mobile'],
                    [
                        'db' => 'u.created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'created_at',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'db' => 'u.is_activated',
                        'db_alias' => 'is_activated',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this
                                ->setTemplate('partial/admin/parser/active-status')
                                ->render([
                                    'status' => $d,
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $options = $this
                                ->setTemplate('partial/admin/datatable/actions-user')
                                ->render([
                                    'row' => $row,
                                ]);
                            return $options;
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws IDBException
     */
    public function removeOrder($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_ORDERS, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $user_id
     * @return void
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function getOrderPaginatedDatatable($user_id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_USER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($user_id) {
                    $event->stopPropagation();

                    /**
                     * @var OrderModel $orderModel
                     */
                    $orderModel = container()->get(OrderModel::class);

                    if (!empty($where)) {
                        $where .= ' AND (user_id=:uId)';
                    } else {
                        $where = 'user_id=:uId';
                    }
                    $bindValues = array_merge($bindValues, [
                        'uId' => $user_id,
                    ]);

                    $cols[] = 'send_status_color';
                    $cols[] = 'total_price';

                    $data = $orderModel->get($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $orderModel->count($where, $bindValues);
                    $recordsTotal = $orderModel->count('user_id=:uId', ['uId' => $user_id]);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'code', 'db_alias' => 'code', 'dt' => 'code'],
                    ['db' => 'receiver_name', 'db_alias' => 'receiver_name', 'dt' => 'name'],
                    ['db' => 'receiver_mobile', 'db_alias' => 'receiver_mobile', 'dt' => 'mobile'],
                    ['db' => 'CONCAT(province, ' - ', city)', 'db_alias' => 'place', 'dt' => 'place'],
                    [
                        'db' => 'payment_status',
                        'db_alias' => 'payment_status',
                        'dt' => 'pay_status',
                        'formatter' => function ($d) {
                            $status = $this
                                ->setTemplate('partial/admin/parser/active-status')
                                ->render([
                                    'status' => $d,
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'final_price',
                        'db_alias' => 'final_price',
                        'dt' => 'price',
                        'formatter' => function ($d, $row) {
                            $status = '<del class="text-grey m-2">' . local_number(number_format($row['total_price'])) . '</del>';
                            $status .= '<span class="text-success">' . local_number(number_format($d)) . '</span>';
                            return $status;
                        }
                    ],
                    [
                        'db' => 'send_status_title',
                        'db_alias' => 'send_status_title',
                        'dt' => 'send_status',
                        'formatter' => function ($d, $row) {
                            $status = '<span style="background-color: ' . $row['send_status_color'] . ';">' . $d . '</span>';
                            return $status;
                        }
                    ],
                    [
                        'db' => 'ordered_at',
                        'db_alias' => 'ordered_at',
                        'dt' => 'order_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $options = $this
                                ->setTemplate('partial/admin/datatable/actions-user-order')
                                ->render([
                                    'row' => $row,
                                ]);
                            return $options;
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