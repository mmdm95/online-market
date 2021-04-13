<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Festival\AddFestivalForm;
use App\Logic\Forms\Admin\Festival\EditFestivalForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralAjaxStatusHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\FestivalModel;
use App\Logic\Utils\Jdf;
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

class FestivalController extends AbstractAdminController implements IDatatableController
{
    /**
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
    public function view()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FESTIVAL, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/festival/view');
        return $this->render();
    }

    /**
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
    public function add()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FESTIVAL, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddFestivalForm::class, 'festival_add');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/festival/add');
        return $this->render($data);
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
        if (!$auth->isAllow(RESOURCE_FESTIVAL, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        /**
         * @var FestivalModel $festivalModel
         */
        $festivalModel = container()->get(FestivalModel::class);

        $festival = $festivalModel->get(['title'], 'id=:id', ['id' => $id]);

        if (0 == count($festival)) {
            return $this->show404();
        }

        // store previous hex to check for duplicate
        session()->setFlash('festival-prev-title', $festival[0]['title']);
        session()->setFlash('festival-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditFestivalForm::class, 'festival_edit');
        }

        $festival = $festivalModel->getFirst(['*'], 'id=:id', ['id' => $id]);

        $this->setLayout($this->main_layout)->setTemplate('view/festival/edit');
        return $this->render(array_merge($data, [
            'festival' => $festival,
        ]));
    }

    /**
     * @param $id
     * @throws ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
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
        if (!$auth->isAllow(RESOURCE_FESTIVAL, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_FESTIVALS, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function pubStatusChange($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FESTIVAL, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                $handler = new GeneralAjaxStatusHandler();
                $resourceHandler = $handler
                    ->setStatusCheckedMessage('نمایش جشنواره فعال شد.')
                    ->setStatusUncheckedMessage('نمایش جشنواره غیر فعال شد.')
                    ->handle(
                        BaseModel::TBL_FESTIVALS,
                        $id,
                        'publish',
                        input()->post('status')->getValue()
                    );
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function mainStatusChange($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_FESTIVAL, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                $handler = new GeneralAjaxStatusHandler();
                emitter()->addListener('status.general.ajax:before_update', function (IEvent $event, ResourceHandler $rHandler) {
                    $event->stopPropagation();

                    /**
                     * @var FestivalModel $festivalModel
                     */
                    $festivalModel = container()->get(FestivalModel::class);

                    // reset all main festivals' flag and then got for other things
                    // ...
                });
                $resourceHandler = $handler
                    ->setStatusCheckedMessage('به عنوان جشنواره اصلی انتخاب شد.')
                    ->setStatusUncheckedMessage('از جشنواره اصلی برداشته شد.')
                    ->handle(
                        BaseModel::TBL_FESTIVALS,
                        $id,
                        'is_main',
                        input()->post('status')->getValue()
                    );
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
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
        if (!$auth->isAllow(RESOURCE_FESTIVAL, IAuth::PERMISSION_READ)) {
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
                     * @var FestivalModel $festivalModel
                     */
                    $festivalModel = container()->get(FestivalModel::class);

                    $data = $festivalModel->get($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $festivalModel->count($where, $bindValues);
                    $recordsTotal = $festivalModel->count();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'title', 'db_alias' => 'title', 'dt' => 'title'],
                    [
                        'db' => 'start_at',
                        'db_alias' => 'start_at',
                        'dt' => 'start_date',
                        'formatter' => function ($d) {
                            if (!empty($d)) {
                                $date = Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                                if ($d > time()) {
                                    $date = "<span class='text-success'>" . $date . "</span>";
                                }
                            } else {
                                $date = $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                            return $date;
                        }
                    ],
                    [
                        'db' => 'expire_at',
                        'db_alias' => 'expire_at',
                        'dt' => 'end_date',
                        'formatter' => function ($d) {
                            if (!empty($d)) {
                                $date = Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                                if ($d < time()) {
                                    $date = "<span class='text-danger'>" . $date . "</span>";
                                }
                            } else {
                                $date = $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                            return $date;
                        }
                    ],
                    [
                        'db' => 'publish',
                        'db_alias' => 'publish',
                        'dt' => 'status',
                        'formatter' => function ($d, $row) {
                            $status = $this->setTemplate('partial/admin/parser/status-changer')
                                ->render([
                                    'status' => $d,
                                    'row' => $row,
                                    'url' => url('ajax.festival.pub.status')->getRelativeUrl(),
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'is_main',
                        'db_alias' => 'is_main',
                        'dt' => 'main_status',
                        'formatter' => function ($d, $row) {
                            $status = $this->setTemplate('partial/admin/parser/status-changer')
                                ->render([
                                    'status' => $d,
                                    'row' => $row,
                                    'url' => url('ajax.festival.main.status')->getRelativeUrl(),
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'created_at',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-festival')
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