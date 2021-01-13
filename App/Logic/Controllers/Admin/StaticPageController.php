<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\StaticPage\AddStaticPageForm;
use App\Logic\Forms\Admin\StaticPage\EditStaticPageForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\StaticPageModel;
use App\Logic\Utils\Jdf;
use Jenssegers\Agent\Agent;
use ReflectionException as ReflectionExceptionAlias;
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

class StaticPageController extends AbstractAdminController implements IDatatableController
{
    /**
     * @return string
     * @throws ReflectionExceptionAlias
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/static-page/view');
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
     * @throws ReflectionExceptionAlias
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function add()
    {
        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddStaticPageForm::class, 'static_page_add');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/static-page/add');
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
     * @throws ReflectionExceptionAlias
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function edit($id)
    {
        /**
         * @var StaticPageModel $pageModel
         */
        $pageModel = container()->get(StaticPageModel::class);

        $page = $pageModel->get(['url'], 'id=:id', ['id' => $id]);

        if (0 == count($page)) {
            return $this->show404();
        }

        // store previous title to check for duplicate
        session()->setFlash('static-page-prev-url', $page[0]['url']);
        session()->setFlash('static-page-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditStaticPageForm::class, 'static_page_edit');
        }

        $page = $pageModel->getFirst(['*'], 'id=:id', ['id' => $id]);

        $this->setLayout($this->main_layout)->setTemplate('view/static-page/edit');
        return $this->render(array_merge($data, [
            'page' => $page,
        ]));
    }

    /**
     * @param $id
     * @throws ReflectionExceptionAlias
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
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
            $resourceHandler = $handler->handle(BaseModel::TBL_STATIC_PAGES, $id, 'deletable=:del', ['del' => DB_YES]);
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
                     * @var StaticPageModel $pageModel
                     */
                    $pageModel = container()->get(StaticPageModel::class);

                    $cols[] = 'sp.deletable';

                    $data = $pageModel->getPages($cols, $where, $bindValues, $limit, $offset, $order);
                    //-----
                    $recordsFiltered = $pageModel->getPagesCount($where, $bindValues);
                    $recordsTotal = $pageModel->getPagesCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'sp.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'sp.title', 'db_alias' => 'title', 'dt' => 'title'],
                    [
                        'db' => 'sp.publish',
                        'db_alias' => 'publish',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/parser/active-status')
                                ->render([
                                    'status' => $d,
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'CONCAT(u.first_name, " ", u.last_name)',
                        'db_alias' => 'creator',
                        'dt' => 'writer',
                        'formatter' => function ($d) {
                            if (trim($d) != '') {
                                return $d;
                            } else {
                                return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                        }
                    ],
                    [
                        'db' => 'sp.created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'created_at',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-static-page')
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