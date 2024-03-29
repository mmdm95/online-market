<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Forms\User\Info\ChangeUserInfoForm;
use App\Logic\Forms\User\Info\ChangeUserOtherForm;
use App\Logic\Forms\User\Info\ChangeUserPasswordForm;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CommentModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\ReturnOrderModel;
use App\Logic\Models\SecurityQuestionModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Models\WalletModel;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;

class HomeController extends AbstractUserController
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

        /**
         * @var ReturnOrderModel $returnModel
         */
        $returnModel = container()->get(ReturnOrderModel::class);

        $user = $this->getDefaultArguments()['user'];

        $walletBalance = $walletModel->getFirst(['balance'], 'username=:username', ['username' => $user['username']])['balance'] ?? 0;
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
                'o.id',
                'o.code',
                'o.payment_status',
                'o.send_status_title',
                'o.send_status_color',
                'o.final_price',
                'o.ordered_at',
            ]
        );
        $lastReturnOrders = $returnModel->getReturnOrders(
            'ro.user_id=:id',
            ['id' => $user['id']],
            ['ro.id DESC'],
            3,
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
        foreach ($lastReturnOrders as &$order) {
            $order['items_count'] = $returnModel->getReturnOrderItemsCount('return_code=:code', ['code' => $order['code']]);
        }
        $lastWalletFlow = $walletFlowModel->get(
            [
                'order_code',
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
            'accept_comment_count' => $accCommentCount,
            'not_accept_comment_count' => $naccCommentCount,
            //
            'last_orders' => $lastOrders,
            'last_return_orders' => $lastReturnOrders,
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function info()
    {
        /**
         * @var SecurityQuestionModel $secModel
         */
        $secModel = container()->get(SecurityQuestionModel::class);

        $user = $this->getDefaultArguments()['user'];
        $data = [];
        $data['security_questions'] = $secModel->get(['id', 'question'], 'publish=:pub', ['pub' => DB_YES]);

        if (is_post()) {
            session()->setFlash('the-current-user-id', $user['id']);

            if (!is_null(input()->post('infoSubmit')->getValue())) {
                $formHandler = new GeneralFormHandler();
                $data = $formHandler->handle(ChangeUserInfoForm::class, 'info_change');

                // handle back url for better user experience
                $backUrl = ArrayUtil::get($_GET, 'back_url', null);
                $backUrl = urldecode($backUrl);
                if (isset($data['info_change_success']) && !empty($backUrl)) {
                    response()->redirect($backUrl);
                }
            } elseif (!is_null(input()->post('passwordSubmit')->getValue())) {
                $formHandler = new GeneralFormHandler();
                $data = $formHandler->handle(ChangeUserPasswordForm::class, 'password_change');

                // logout to check new password
                if (isset($data['password_change_success']) && (bool)$data['password_change_success']) {
                    response()->redirect(url('home.logout')->getRelativeUrl());
                }
            } elseif (!is_null(input()->post('otherSubmit')->getValue())) {
                $formHandler = new GeneralFormHandler();
                $data = $formHandler->handle(ChangeUserOtherForm::class, 'other_change');
            }

            /**
             * @var DBAuth $auth
             */
            $auth = container()->get('auth_home');

            // get current user info
            $this->extendDefaultArguments([
                'user' => get_current_authenticated_user($auth),
            ]);
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/info');
        return $this->render($data);
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
     */
    public function favorite()
    {
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $user = $this->getDefaultArguments()['user'];

        $favorites = $productModel->getUserFavoriteProducts('fp.user_id=:id', [
            'id' => $user['id'],
        ]);

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/favorite');
        return $this->render([
            'favorites' => $favorites,
        ]);
    }

    /**
     * @param $id
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function removeFavorite($id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $user = $this->getDefaultArguments()['user'];

            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_FAVORITE_USER_PRODUCT, $id, 'user_id=:uId', ['uId' => $user['id']]);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }
}