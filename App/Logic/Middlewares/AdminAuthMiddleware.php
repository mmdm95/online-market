<?php

namespace App\Logic\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class AdminAuthMiddleware implements IMiddleware
{
    /**
     * @param Request $request
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
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