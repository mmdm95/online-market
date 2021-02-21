<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Models\CommentModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\ReturnOrderModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Models\WalletModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class HomeController extends AbstractUserController
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
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var ReturnOrderModel $returnModel
         */
        $returnModel = container()->get(ReturnOrderModel::class);
        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);

        $user = $this->getDefaultArguments()['user'];

        $walletBalance = $walletModel->getFirst(['balance'], 'username=:username', ['username' => $user['username']])['balance'];
        $favoriteCount = $productModel->userFavoriteProductCount($user['id']);
        $orderCount = $orderModel->count('user_id=:id', ['id' => $user['id']]);
        $returnCount = $returnModel->count('user_id=:id', ['id' => $user['id']]);
        $accCommentCount = $commentModel->count('user_id=:id AND the_condition=:condition', ['id' => $user['id'], 'condition' => COMMENT_CONDITION_ACCEPT]);
        $naccCommentCount = $commentModel->count('user_id=:id AND the_condition=:condition', ['id' => $user['id'], 'condition' => COMMENT_CONDITION_REJECT]);
        //
        $lastOrders = $orderModel->getOrders(
            'o.user_id=:id',
            ['id' => $user['id']],
            ['o.id DESC'],
            3,
            0,
            [
                'o.code',
                'o.method_title',
                'o.payment_status',
                'o.send_status_title',
                'o.send_status_color',
                'o.final_price',
                'o.ordered_at',
            ]
        );
        $lastWalletFlow = $walletFlowModel->get(
            [
                'deposit_code',
                'deposit_price',
                'deposit_type_title',
                'deposit_at',
            ],
            'username=:username',
            ['username' => $user['username']],
            ['id DESC'],
            3
        );

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/index');
        return $this->render([
            'order_count' => $orderCount,
            'return_order_count' => $returnCount,
            'wallet_balance' => $walletBalance,
            'favorite_count' => $favoriteCount,
            'accept_comment_count' => $accCommentCount,
            'not_accept_comment_count' => $naccCommentCount,
            //
            'last_orders' => $lastOrders,
            'last_wallet_flow' => $lastWalletFlow,
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
    public function info()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/user/info');
        return $this->render();
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
    public function favorite()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/user/favorite');
        return $this->render();
    }
}