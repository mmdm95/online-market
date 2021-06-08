<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\WalletFlowModel;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class OrderController extends AbstractUserController
{
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

        $user = $this->getDefaultArguments()['user'];

        $orders = $orderModel->getOrders(
            'o.user_id=:id',
            ['id' => $user['id']],
            ['o.id DESC'],
            null,
            0,
            [
                'o.id',
                'o.code',
                'o.method_title',
                'o.payment_status',
                'o.send_status_title',
                'o.send_status_color',
                'o.final_price',
                'o.ordered_at',
            ]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/order/index');
        return $this->render([
            'orders' => $orders,
        ]);
    }

    /**
     * @param $id
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
    public function detail($id)
    {
        $user = $this->getDefaultArguments()['user'];

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        /**
         * @var GatewayModel $gatewayModel
         */
        $gatewayModel = container()->get(GatewayModel::class);

        if (0 === $orderModel->count('id=:id AND user_id=:uId', ['id' => $id, 'uId' => $user['id']])) {
            return $this->show404();
        }

        $order = $orderModel->getOrders('o.id=:id', ['id' => $id])[0];
        $orderItems = $orderModel->getOrderItemsWithReturnOrderItems([
            'oi.*', 'roi.order_item_id', 'p.slug AS product_slug', 'p.allow_commenting',
            'p.image AS product_image', 'pp.code AS main_product_code',
        ], 'code=:code', ['code' => $order['code']]);

        $paymentSuccess = $gatewayModel->getFirst([
            'id', 'price', 'msg', 'payment_code', 'is_success', 'method_type', 'payment_date'
        ], 'order_code=:oc AND user_id=:uId AND method_type=:mt', [
            'oc' => $order['code'],
            'uId' => $user['id'],
            'mt' => $order['method_type'],
        ], ['payment_date DESC', 'id DESC']) ?: null;
        $order['payment_code'] = $paymentSuccess['payment_code'] ?? null;
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        $paymentInfo = [];
        $paymentInfo['gateway_flow'] = $gatewayModel->get([
            'id', 'price', 'msg', 'payment_code', 'is_success', 'method_type', 'payment_date'
        ], 'order_code=:oc AND user_id=:uId', [
            'oc' => $order['code'],
            'uId' => $user['id'],
        ], ['payment_date DESC', 'id DESC']);
        //
        $paymentInfo['wallet_flow'] = $walletFlowModel->get([
            'id', 'deposit_price', 'deposit_type_title', 'deposit_at'
        ], 'order_code=:oc AND username=:username AND deposit_type_code=:dtc', [
            'oc' => $order['code'], 'username' => $user['username'], 'dtc' => DEPOSIT_TYPE_PAYED
        ]);

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/order/detail');
        return $this->render([
            'order' => $order,
            'order_items' => $orderItems,
            'payment_success' => $paymentSuccess,
            'payment_info' => $paymentInfo,
            'order_id' => $id,
            'sub_title' => 'جزئیات سفارش' . '-' . $order['code'],
        ]);
    }
}