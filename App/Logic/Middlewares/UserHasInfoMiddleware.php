<?php

namespace App\Logic\Middlewares;

use App\Logic\Models\UserModel;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;

class UserHasInfoMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        $auth = auth_home();
        if (!$auth->isLoggedIn()) {
            redirect(url('home.login', [], ['back_url' => url()->getRelativeUrl()]));
        }

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $user = $userModel->getFirst([
            'first_name', 'last_name', 'national_number'
        ], 'id=:id', ['id' => $auth->getCurrentUser()['id']]);
        if (!count($user)) {
            redirect(url('home.login', [], ['back_url' => url()->getRelativeUrl()]));
        }

        if (empty(trim($user['first_name'])) || empty(trim($user['last_name'])) || empty(trim($user['national_number']))) {
            redirect(url('user.info', [], ['back_url' => url()->getRelativeUrl()]) . '#changeInfo');
        }
    }
}
