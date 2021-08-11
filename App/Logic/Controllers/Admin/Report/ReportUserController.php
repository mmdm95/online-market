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
use App\Logic\Models\UserModel;
use App\Logic\Utils\Jdf;
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

class ReportUserController extends AbstractAdminController implements
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
     * @var string
     */
    protected $report_excel_layout = 'admin-report-excel';

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
        if (!$auth->isAllow(RESOURCE_REPORT_USER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/report/user');
        return $this->render([
            'query_builder' => ReportQBUtil::getUserQB(),
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
        if (!$auth->isAllow(RESOURCE_REPORT_USER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        $qb = input()->post('filtered_qb')->getValue();
        $qb = json_decode($qb, true);
        if (!empty($qb)) {
            session()->set(SESSION_QUERY_BUILDER_USER, $qb);
        } else {
            session()->remove(SESSION_QUERY_BUILDER_USER);
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
        if (!$auth->isAllow(RESOURCE_REPORT_USER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();
        session()->remove(SESSION_QUERY_BUILDER_USER);
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
        if (!$auth->isAllow(RESOURCE_REPORT_USER, IAuth::PERMISSION_READ)) {
            show_403();
        }

        [$where, $bindValues] = $this->getQBFiltered();
        ReportUtil::exportUsersExcel($where, $bindValues);
    }

    public function exportPdfOne()
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
        if (!$auth->isAllow(RESOURCE_REPORT_USER, IAuth::PERMISSION_READ)) {
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

                    if (!empty($where)) {
                        $where .= ' AND ';
                    }

                    [$newWhere, $newBindValues] = $this->getQBFiltered();
                    $where .= $newWhere;
                    $bindValues = array_merge($bindValues, $newBindValues);

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
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Auth\Interfaces\IDBException
     */
    private function getQBFiltered(): array
    {
        $where = '';
        $bindValues = [];

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        if (!$auth->hasRole(ROLE_DEVELOPER)) {
            $where .= ' u.is_deleted<>:del';
            $where .= ' AND u.is_hidden<>:hidden';
            $bindValues = array_merge($bindValues, [
                'del' => DB_YES,
            ]);
            $bindValues = array_merge($bindValues, [
                'hidden' => DB_YES,
            ]);
        }

        $where = trim(trim($where), 'AND');

        // use query builder sql and params
        $qb = session()->get(SESSION_QUERY_BUILDER_USER);
        if (
            (isset($qb['sql']) && !empty($qb['sql'])) &&
            (isset($qb['params']) && !empty($qb['params']) && !is_null(json_decode($qb['params'], true)))) {
            $newBind = [];
            foreach (json_decode($qb['params'], true) as $k => $p) {
                $newK = str_replace('.', '_', $k);
                $qb['sql'] = str_replace($k, $newK, $qb['sql']);
                $newBind[$newK] = $p;
            }

            if (!empty(trim($where))) {
                $where .= ' AND ';
            }
            $where .= $qb['sql'];
            $bindValues = array_merge($bindValues, $newBind);
        }

        return [$where, $bindValues];
    }
}