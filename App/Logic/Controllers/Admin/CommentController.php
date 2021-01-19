<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CommentModel;
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

class CommentController extends AbstractAdminController implements IDatatableController
{
    /**
     * @param null $id
     * @return string
     * @throws \ReflectionException
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
            $this->setLayout($this->main_layout)->setTemplate('view/comment/message');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/comment/view');
        }

        return $this->render($data);
    }

    /**
     * @param $id
     * @throws \ReflectionException
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
            $resourceHandler = $handler->handle(BaseModel::TBL_COMMENTS, $id);
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
            [$product_id] = $_;

            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($product_id) {
                    $event->stopPropagation();

                    /**
                     * @var CommentModel $commentModel
                     */
                    $commentModel = container()->get(CommentModel::class);

                    if (!empty($where)) {
                        $where .= ' AND (product_id=:pId)';
                    } else {
                        $where = 'product_id=:pId';
                    }
                    $bindValues = array_merge($bindValues, [
                        'pId' => $product_id,
                    ]);

                    // add needed product info to show to user
                    // ...

                    $data = $commentModel->getComments($where, $bindValues, $limit, $offset, $order, $cols);
                    //-----
                    $recordsFiltered = $commentModel->getCommentsCount($where, $bindValues);
                    $recordsTotal = $commentModel->getCommentsCount('product_id=:pId', ['pId' => $product_id]);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'name', 'db_alias' => 'name', 'dt' => 'name'],
                    ['db' => 'title', 'db_alias' => 'title', 'dt' => 'title'],
                    [
                        'db' => 'created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'sent_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'db' => 'status',
                        'db_alias' => 'status',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/parser/status-creation')
                                ->render([
                                    'status' => $d,
                                    'switch' => [
                                        [
                                            'status' => COMPLAINT_STATUS_READ,
                                            'text' => 'خوانده شده',
                                            'badge' => 'badge-success',
                                        ],
                                        [
                                            'status' => COMPLAINT_STATUS_UNREAD,
                                            'text' => 'خوانده نشده',
                                            'badge' => 'badge-danger',
                                        ],
                                    ],
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-complaint')
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