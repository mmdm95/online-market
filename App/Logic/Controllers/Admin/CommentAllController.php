<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\CommentModel;
use App\Logic\Utils\Jdf;
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

class CommentAllController extends AbstractAdminController implements IDatatableController
{
    /**
     * @param $p_id
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
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/product/comment/all');
        return $this->render();
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
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
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
                     * @var CommentModel $commentModel
                     */
                    $commentModel = container()->get(CommentModel::class);

                    $cols[] = 'c.user_id';
                    $cols[] = 'c.product_id';
                    $cols[] = 'u.first_name';
                    $cols[] = 'u.last_name';

                    $data = $commentModel->getCommentsSummaryAllInfo($where, $bindValues, $limit, $offset, $order, ['c.id'], $cols);
                    //-----
                    $recordsFiltered = $commentModel->getCommentsSummaryAllInfoCount($where, $bindValues, ['c.id']);
                    $recordsTotal = $commentModel->getCommentsSummaryAllInfoCount(null, [], ['c.id']);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'c.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'p.title', 'db_alias' => 'title', 'dt' => 'title'],
                    [
                        'db' => 'p.image',
                        'db_alias' => 'image',
                        'dt' => 'image',
                        'formatter' => function ($d, $row) {
                            return $this->setTemplate('partial/admin/parser/image-placeholder')
                                ->render([
                                    'img' => $d,
                                    'alt' => $row['title'],
                                ]);
                        }
                    ],
                    [
                        'db' => 'u.username',
                        'db_alias' => 'username',
                        'dt' => 'username',
                        'formatter' => function ($d, $row) {
                            $user = $d;
                            if (!empty(trim($row['first_name'])) || !empty(trim($row['last_name']))) {
                                $user = trim(trim($row['first_name']) . ' ' . trim($row['last_name']));
                            }
                            return "<a href='" . url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() . "' target='__blank'> " . $user . "</a>";
                        }
                    ],
                    [
                        'db' => 'c.status',
                        'db_alias' => 'status',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/parser/status-creation')
                                ->render([
                                    'status' => $d,
                                    'switch' => [
                                        [
                                            'status' => COMMENT_STATUS_READ,
                                            'text' => 'خوانده شده',
                                            'badge' => 'badge-primary',
                                        ],
                                        [
                                            'status' => COMMENT_STATUS_NOT_READ,
                                            'text' => 'خوانده نشده',
                                            'badge' => 'badge-danger',
                                        ],
                                        [
                                            'status' => COMMENT_STATUS_REPLIED,
                                            'text' => 'پاسخ داده شده',
                                            'badge' => 'badge-success',
                                        ],
                                    ],
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'c.the_condition',
                        'db_alias' => 'the_condition',
                        'dt' => 'accept_status',
                        'formatter' => function ($d, $row) {
                            return $this->setTemplate('partial/admin/parser/multi-status-changer')
                                ->render([
                                    'status' => $d,
                                    'id' => $row['id'],
                                    'min' => COMMENT_CONDITION_NOT_SET,
                                    'max' => COMMENT_CONDITION_ACCEPT,
                                    'labels' => [
                                        COMMENT_CONDITION_NOT_SET => 'نامشخص',
                                        COMMENT_CONDITION_REJECT => 'عدم تایید',
                                        COMMENT_CONDITION_ACCEPT => 'تایید شده',
                                    ],
                                    'url' => url('ajax.comment.condition', ['p_id' => $row['product_id'], 'id' => $row['id']])->getRelativeUrlTrimmed(),
                                ]);
                        }
                    ],
                    [
                        'db' => 'c.sent_at',
                        'db_alias' => 'sent_at',
                        'dt' => 'sent_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            return $this->setTemplate('partial/admin/datatable/actions-comment')
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
