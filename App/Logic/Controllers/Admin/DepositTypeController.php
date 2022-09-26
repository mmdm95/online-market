<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Ajax\DepositType\AddDepositTypeForm;
use App\Logic\Forms\Ajax\DepositType\EditDepositTypeForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IAjaxController;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\DepositTypeModel;
use App\Logic\Utils\LogUtil;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class DepositTypeController extends AbstractAdminController implements IAjaxController, IDatatableController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
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

        $this->setLayout($this->main_layout)->setTemplate('view/wallet/deposit-type');
        return $this->render();
    }

    /**
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function add(): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('نوع تراکنش کیف پول با موفقیت اضافه شد.')
                ->handle(AddDepositTypeForm::class);
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
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function edit($id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            session()->setFlash('deposit-type-edit-id', $id);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('نوع تراکنش کیف پول با موفقیت ویرایش شد.')
                ->handle(EditDepositTypeForm::class);
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
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($id): void
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
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_DEPOSIT_TYPES, $id, 'deletable=:del', ['del' => DB_YES]);
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
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get($id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_WALLET, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var DepositTypeModel $depositModel
             */
            $depositModel = container()->get(DepositTypeModel::class);
            $res = $depositModel->getFirst(['*'], 'id=:id', ['id' => $id]);
            if (count($res)) {
                $resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->data($res);
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('نوع تراکنش کیف پول درخواست شده نامعتبر است!');
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
                     * @var DepositTypeModel $depositModel
                     */
                    $depositModel = container()->get(DepositTypeModel::class);

                    $cols[] = 'deletable';

                    $data = $depositModel->get($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $depositModel->count($where, $bindValues);
                    $recordsTotal = $depositModel->count();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'title', 'db_alias' => 'title', 'dt' => 'title'],
                    ['db' => 'desc', 'db_alias' => 'desc', 'dt' => 'description'],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            return $this->setTemplate('partial/admin/datatable/actions-deposit-type')
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
}