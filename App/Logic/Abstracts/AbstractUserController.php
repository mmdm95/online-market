<?php

namespace App\Logic\Abstracts;

use App\Logic\Models\UserModel;
use App\Logic\Models\WalletModel;
use Sim\Auth\DBAuth;

abstract class AbstractUserController extends AbstractMainController
{
    /**
     * @var string
     */
    protected $main_layout = 'main-user';

    public function __construct()
    {
        parent::__construct();

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');

        // get current user info
        $user = $userModel->getFirst(['*'], 'id=:id', ['id' => $auth->getCurrentUser()['id'] ?? 0]);
        $user['roles'] = $userModel->getUserRoles($user['id'], null, [], ['r.*']);

        $this->setDefaultArguments(array_merge($this->getDefaultArguments(), [
            'user' => $user,
        ]));
    }
}