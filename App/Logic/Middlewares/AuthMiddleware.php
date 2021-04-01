<?php

namespace App\Logic\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use Sim\Auth\DBAuth;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');

        if (!$auth->isLoggedIn()) {
            response()->redirect(url('home.login', [], ['back_url' => url()->getRelativeUrl()]));
        }
    }
}