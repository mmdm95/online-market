<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Models\OrderModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function detail($id)
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        $order = [];

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/order/detail');
        return $this->render([
            'order' => $order,
            'sub_title' => 'جزئیات سفارش'// . '-' . $order['code'],
        ]);
    }
}