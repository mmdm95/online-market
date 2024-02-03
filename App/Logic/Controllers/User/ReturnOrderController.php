<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Forms\User\ReturnOrder\ReturnOrderForm as UserReturnOrderForm;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\BaseModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ReturnOrderModel;
use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ReturnOrderController extends AbstractUserController
{
    /**
     * @var string
     */
    private $returnOrderCheckedStr = 'return-order-checked';

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function index()
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var ReturnOrderModel $returnModel
         */
        $returnModel = container()->get(ReturnOrderModel::class);

        $user = $this->getDefaultArguments()['user'];

        $returnOrders = $returnModel->getReturnOrders(
            'ro.user_id=:id',
            ['id' => $user['id']],
            ['ro.id DESC'],
            null,
            0,
            [
                'ro.id',
                'ro.code',
                'ro.order_code',
                'ro.status',
                'ro.requested_at',
                'o.final_price',
                'o.id AS order_id',
            ]
        );
        foreach ($returnOrders as &$order) {
            $order['items_count'] = $returnModel->getReturnOrderItemsCount('return_code=:code', ['code' => $order['code']]);
        }

        $orders = $orderModel->getOrders(
            'u.id=:id AND o.payment_status=:status AND ordered_at>=:date',
            [
                'id' => $user['id'],
                'status' => PAYMENT_STATUS_SUCCESS,
                'date' => time() - RETURN_ORDER_DURATION,
            ],
            ['o.id DESC'],
            null,
            0,
            [
                'o.code',
            ]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/return-order/index');
        return $this->render([
            'return_orders' => $returnOrders,
            'orders' => $orders,
        ]);
    }

    /**
     * @return string|void
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function addTmp()
    {
        $code = input()->post('return-order-code-inp', null)->getValue();
        if (is_null($code) || DEFAULT_OPTION_VALUE == $code) {
            session()->setFlash('err_return_order_sess', 'کد سفارش برای مرجوع نمودن کالا نامعتبر می‌باشد!');
            redirect(url('user.return-order'));
        }

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        if (!$orderModel->getOrdersCount('code=:code', ['code' => $code])) {
            return $this->show404();
        }

        /**
         * @var ReturnOrderModel $returnOrderModel
         */
        $returnOrderModel = container()->get(ReturnOrderModel::class);
        $returned = $returnOrderModel->getFirst(['status'], 'order_code=:oc', ['oc' => $code]);
        if (count($returned) && !in_array($returned['status'], [RETURN_ORDER_STATUS_CHECKING, RETURN_ORDER_STATUS_DENIED_BY_USER])) {
            session()->setFlash('err_return_order_sess', 'وضعیت سفارش مرجوعی مشخص شده و درخواست مجدد قابل انجام نمی‌باشد.');
            redirect(url('user.return-order'));
        }

        session()->setTimed('is_return_order_checked', $this->returnOrderCheckedStr, 600);
        redirect(url('user.return-order.add', ['code' => $code]));
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     * @var $code
     */
    public function add($code = null)
    {
        if (session()->getTimed('is_return_order_checked') !== $this->returnOrderCheckedStr) {
            session()->setFlash('err_return_order_sess', 'عدم مجوز برای عملیات درخواست شده');
            redirect(url('user.return-order'));
        }

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var ReturnOrderModel $returnOrderModel
         */
        $returnOrderModel = container()->get(ReturnOrderModel::class);

        $data = [];
        if (is_post()) {
            session()->setFlash('return-order-submit-code', $code);

            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(UserReturnOrderForm::class, 'return_order_add');
        }

        $returnOrder = $returnOrderModel->getFirst(['`desc`'], 'order_code=:oc', ['oc' => $code]);
        $orderItems = $orderModel->getOrderItemsWithReturnOrderItems([
            'oi.*', 'roi.order_item_id', 'roi.product_count AS return_count', 'pa.slug AS product_slug',
            'pa.image AS product_image', 'pa.code AS main_product_code',
        ], 'oi.order_code=:code', ['code' => $code]);

        if (!count($orderItems)) {
            return $this->show404();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/return-order/add');
        return $this->render(array_merge($data, [
            'sub_title' => 'آیتم‌های مرجوع سفارش با کد ' . '<span class="badge badge-info">' . $code . '</span>',
            //
            'code' => $code,
            'returnOrder' => $returnOrder,
            'orderItems' => $orderItems,
        ]));
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function detail($id)
    {
        /**
         * @var ReturnOrderModel $returnOrderModel
         */
        $returnOrderModel = container()->get(ReturnOrderModel::class);

        $returnOrder = $returnOrderModel->get(
            ['*'],
            'id=:id',
            ['id' => $id],
            ['id DESC'],
            1,
            0
        );

        if (!count($returnOrder)) {
            session()->setFlash('err_return_order_sess', 'سفارش مرجوعی انتخاب شده نامعتبر است.');
            redirect(url('user.return-order'));
        }
        $returnOrder = $returnOrder[0];

        $returnOrderItems = $returnOrderModel->getReturnOrderItems(
            [
                'oi.*', 'roi.order_item_id', 'roi.is_accepted', 'roi.product_count AS return_count',
                'pa.slug AS product_slug', 'pa.image AS product_image', 'pa.code AS main_product_code',
            ],
            'ro.id=:id',
            ['id' => $id]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/return-order/detail');
        return $this->render([
            'sub_title' => 'جزئیات سفارش مرجوع شده با کد '
                . '<span class="badge badge-info">'
                . $returnOrder['order_code'] . '</span>',
            //
            'return_order' => $returnOrder,
            'return_order_items' => $returnOrderItems,
        ]);
    }

    /**
     * @param $id
     * @return void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var ReturnOrderModel $returnOrderModel
             */
            $returnOrderModel = container()->get(ReturnOrderModel::class);
            $order = $returnOrderModel->getFirst(['status'], 'id=:id', ['id' => $id]);
            if (count($order)) {
                if ($order['status'] == RETURN_ORDER_STATUS_CHECKING) {
                    $handler = new GeneralAjaxRemoveHandler();
                    $resourceHandler = $handler->handle(BaseModel::TBL_RETURN_ORDERS, $id);
                } else {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('امکان حذف وجود ندارد.');
                }
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('سفارش مرجوعی انتخاب شده نامعتبر است.');
            }
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }
}
