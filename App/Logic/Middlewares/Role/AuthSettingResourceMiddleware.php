<?php

namespace App\Logic\Role\Middlewares;

use App\Logic\Handlers\ResourceHandler;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class AuthSettingResourceMiddleware implements IMiddleware
{
    /**
     * @param Request $request
     * @throws \ReflectionException
     * @throws IDBException
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
        $auth = container()->get('auth_home');
        if (!$auth->isAllow(RESOURCE_SETTING, IAuth::PERMISSIONS)) {
            $resourceHandler = new ResourceHandler();
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('دسترسی غیر مجاز');
            response()->httpCode(403)->json($resourceHandler->getReturnData());
        }
    }
}