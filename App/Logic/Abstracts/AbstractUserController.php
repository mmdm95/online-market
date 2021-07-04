<?php

namespace App\Logic\Abstracts;

use App\Logic\Models\ProductModel;
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
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // get current user info
        $user = get_current_authenticated_user($auth);

        $favoriteCount = $productModel->userFavoriteProductCount($user['id']);

        $this->setDefaultArguments(array_merge($this->getDefaultArguments(), [
            'user' => $user,
            'favorite_count' => $favoriteCount,
        ]));
    }
}