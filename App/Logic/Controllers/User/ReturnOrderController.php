<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ReturnOrderModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ReturnOrderController extends AbstractUserController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
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
            ]
        );
        foreach ($returnOrders as &$order) {
            $order['items_count'] = $returnModel->getReturnOrderItemsCount('return_code:code', ['code' => $order['code']]);
        }

        $orders = $orderModel->getOrders(
            'payment_status IN (:status1,:status2)',
            [
                'status1' => PAYMENT_STATUS_SUCCESS,
                'status2' => PAYMENT_STATUS_WAIT,
            ],
            ['o.id DESC'],
            null,
            0,
            [
                'code',
            ]
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/return-order/index');
        return $this->render([
            'return_orders' => $returnOrders,
            'orders' => $orders,
        ]);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function add()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/user/return-order/add');
        return $this->render();
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
        $this->setLayout($this->main_layout)->setTemplate('view/main/user/return-order/detail');
        return $this->render();
    }
}