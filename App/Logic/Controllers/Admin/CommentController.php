<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxMultiStatusHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralAjaxStatusHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CommentModel;
use App\Logic\Models\ProductModel;
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
     * @param $p_id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function view($p_id)
    {
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->getFirst(['title'], 'id=:id', ['id' => $p_id]);

        if (0 === count($product)) {
            return $this->show404();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/product/comment/view');
        return $this->render([
            'sub_title' => 'نظرات محصول' . '-' . $product['title'],
            'product_id' => $p_id,
        ]);
    }

    /**
     * @param $p_id
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function detail($p_id, $id)
    {
        /**
         * @var ProductModel $productModel
         */
//        $productModel = container()->get(ProductModel::class);
//
//        $product = $productModel->getSingleProduct('id=:id', ['id' => $p_id], [
//            'p.id', 'p.title', 'p.image', 'b.name AS brand_name', 'c.name AS category_name',
//        ]);
//
//        if (0 === count($product)) {
//            return $this->show404();
//        }

        /**
         * @var CommentModel $commentModel
         */
//        $commentModel = container()->get(CommentModel::class);
//
//        $comment = $commentModel->getComments('id=:id AND product_id=:pId', ['id' => $id, 'pId' => $p_id], 1, 0, [], [
//            'c.*', 'u.username', 'u.first_name', 'u.last_name'
//        ]);
//
//        if (0 === count($comment)) {
//            return $this->show404();
//        }
//        $comment = $comment[0];
//
//        // change comment status to read if it is not
//        if ($comment['status'] == COMMENT_STATUS_NOT_READ) {
//            $commentModel->update([
//                'status' => COMMENT_STATUS_READ,
//            ], 'id=:id AND product_id=:pId', ['id' => $id, 'pId' => $p_id]);
//        }

        $this->setLayout($this->main_layout)->setTemplate('view/product/comment/message');
        return $this->render([
            'product_id' => $p_id,
            'sub_title' => 'جزئیات نظر',// . '-' . $product['title'],
            'breadcrumb' => [
                [
                    'url' => url('admin.index')->getRelativeUrl(),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.product.view', '')->getRelativeUrl(),
                    'text' => 'مدیریت محصولات',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.comment.view', ['p_id' => $p_id])->getRelativeUrl(),
                    'text' => 'مدیریت نظرات محصولات',
                    'is_active' => false,
                ],
                [
                    'text' => 'مدیریت نظرات',
                    'is_active' => true,
                ],
            ],
        ]);
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
     * @param $p_id
     * @param $id
     */
    public function statusChange($p_id, $id)
    {
        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                $handler = new GeneralAjaxMultiStatusHandler();
                $resourceHandler = $handler
                    ->setStatusArray([
                        COMMENT_CONDITION_NOT_SET,
                        COMMENT_CONDITION_REJECT,
                        COMMENT_CONDITION_ACCEPT,
                    ])
                    ->setStatusMessage([
                        COMMENT_CONDITION_NOT_SET => 'وضعیت نظر به نامشخص تغییر یافت.',
                        COMMENT_CONDITION_REJECT => 'وضعیت نظر به تایید نشده تغییر یافت.',
                        COMMENT_CONDITION_ACCEPT => 'وضعیت نظر به تایید شده تغییر یافت.',
                    ])
                    ->handle(
                        BaseModel::TBL_COMMENTS,
                        $id,
                        'status',
                        input()->post('status')->getValue(),
                        'product_id=:pId',
                        ['pId' => $p_id]
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

                    $cols[] = 'c.user_id';

                    $data = $commentModel->getComments($where, $bindValues, $limit, $offset, $order, $cols);
                    //-----
                    $recordsFiltered = $commentModel->getCommentsCount($where, $bindValues);
                    $recordsTotal = $commentModel->getCommentsCount('product_id=:pId', ['pId' => $product_id]);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => 'u.username',
                        'db_alias' => 'username',
                        'dt' => 'username',
                        'formatter' => function ($d, $row) {
                            return "<a href='" . url('admin.user.view', ['id' => $row['user_id']])->getRelativeUrl() . "'> " . $d . "</a>";
                        }
                    ],
                    [
                        'db' => 'c.status',
                        'db_alias' => 'status',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/parser/active-status')
                                ->render([
                                    'status' => $d,
                                    'active' => 'مشاهده شده',
                                    'deactive' => 'مشاهده نشده',
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'c.condition',
                        'db_alias' => 'condition',
                        'dt' => 'accept_status',
                        'formatter' => function ($d, $row) use ($product_id) {
                            $status = $this->setTemplate('partial/admin/parser/multi-status-changer')
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
                                    'url' => url('ajax.comment.status', ['p_id' => $product_id, 'id' => $row['id']])->getRelativeUrlTrimmed(),
                                ]);
                            return $status;
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
                            $operations = $this->setTemplate('partial/admin/datatable/actions-comment')
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