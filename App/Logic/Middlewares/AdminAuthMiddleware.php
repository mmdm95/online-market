<?php

namespace App\Logic\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use Sim\Auth\DBAuth;

class AdminAuthMiddleware implements IMiddleware
{
    /**
     * @param Request $request
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function handle(Request $request): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        if (!$auth->resume()->isLoggedIn()) {
            response()->redirect(url('admin.login', [], [
                'back_url' => url()->getRelativeUrlTrimmed(),
            ]));
        }
    }
}