<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\User\AddForm;
use App\Logic\Forms\Admin\User\EditForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\RoleModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\Jdf;
use function DI\get;
use Jenssegers\Agent\Agent;
use ReflectionException;
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
     * @throws ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view($id = null)
    {
        $data = [];

        if (!is_null($id)) {
            // get user's stuffs

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
     */
    public function add()
    {
        $data = [];

        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddForm::class, 'user_add');
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
     */
    public function edit($id)
    {
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
            $data = $formHandler->handle(EditForm::class, 'user_edit');
        }

        $user = $userModel->getSingleUser('u.id=:id', ['id' => $id], ['u.*', 'r.id AS role_id']);
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
     */
    public function remove($id)
    {
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
     * @return void
     */
    public function getPaginatedDatatable(): void
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
                     * @var UserModel $userModel
                     */
                    $userModel = container()->get(UserModel::class);

                    if (!empty($where)) {
                        $where .= ' AND (u.is_deleted<>:del AND u.is_hidden<>:hidden)';
                    } else {
                        $where = 'u.is_deleted<>:del AND u.is_hidden<>:hidden';
                    }
                    $bindValues = array_merge($bindValues, [
                        'del' => DB_YES,
                        'hidden' => DB_YES,
                    ]);

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
                            return Jdf::jdate('j F Y', $d);
                        }
                    ],
                    [
                        'db' => 'u.is_activated',
                        'db_alias' => 'is_activated',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = '<span class="badge badge-danger">غیر فعال</span>';
                            if (DB_YES == $d) {
                                $status = '<span class="badge badge-success">فعال</span>';
                            }
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
}