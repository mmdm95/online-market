<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\ContactUsModel;
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

class ContactUsController extends AbstractAdminController implements IDatatableController
{
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
    public function view($id = null)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_CONTACT_US, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $data = [];

        if (!is_null($id)) {
            /**
             * @var ContactUsModel $contactModel
             */
            $contactModel = container()->get(ContactUsModel::class);

            if (0 === $contactModel->count('id=:id', ['id' => $id])) {
                return $this->show404();
            }

            // change status to read
            $contactModel->update([
                'status' => CONTACT_STATUS_READ,
                'changed_status_at' => time(),
            ], 'id=:id AND status=:status', ['id' => $id, 'status' => CONTACT_STATUS_UNREAD]);

            // get contact info with join to users table
            $data['contact'] = $contactModel->getSingleContact('id=:id', ['id' => $id]);

            $this->setLayout($this->main_layout)->setTemplate('view/contact-us/message');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/contact-us/view');
        }

        return $this->render($data);
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
        if (!$auth->isAllow(RESOURCE_CONTACT_US, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_CONTACT_US, $id);
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
        if (!$auth->isAllow(RESOURCE_CONTACT_US, IAuth::PERMISSION_READ)) {
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
                     * @var ContactUsModel $contactModel
                     */
                    $contactModel = container()->get(ContactUsModel::class);

                    $cols[] = 'u.first_name AS creator_name';
                    $cols[] = 'u.last_name AS creator_family';
                    $cols[] = 'u.id AS creator_id';

                    $data = $contactModel->getContacts($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $contactModel->getContactsCount($where, $bindValues);
                    $recordsTotal = $contactModel->getContactsCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'cu.id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => 'cu.name',
                        'db_alias' => 'name',
                        'dt' => 'name',
                        'formatter' => function ($d, $row) {
                            if (!is_null($row['creator_id'])) {
                                return "<a href='"
                                    . url('admin.user.view', ['id' => $row['id']])->getRelativeUrl()
                                    . "'>"
                                    . ($row['creator_name'] ?? '') . ' ' . ($row['creator_family'] ?? '')
                                    . "</a>";
                            } else {
                                return $d;
                            }
                        }
                    ],
                    ['db' => 'cu.title', 'db_alias' => 'title', 'dt' => 'title'],
                    [
                        'db' => 'cu.created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'sent_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'db' => 'cu.status',
                        'db_alias' => 'status',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/parser/status-creation')
                                ->render([
                                    'status' => $d,
                                    'switch' => [
                                        [
                                            'status' => CONTACT_STATUS_READ,
                                            'text' => 'خوانده شده',
                                            'badge' => 'badge-success',
                                        ],
                                        [
                                            'status' => CONTACT_STATUS_UNREAD,
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
                            $operations = $this->setTemplate('partial/admin/datatable/actions-contact-us')
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
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}