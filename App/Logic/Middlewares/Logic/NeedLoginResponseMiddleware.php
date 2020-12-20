<?php

namespace App\Logic\Middlewares\Logic;

use App\Logic\Handlers\ResourceHandler;
use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class NeedLoginResponseMiddleware extends AbstractMiddleware
{
    /**
     * @param mixed ...$_
     * @return bool
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function handle(...$_): bool
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('home_auth');

        $resourceHandler = new ResourceHandler();
        if (!$auth->isLoggedIn()) {
            $resourceHandler->type(RESPONSE_TYPE_WARNING)->data('لطفا ابتدا به پنل کاربری خود وارد شوید.');
            response()->json($resourceHandler->getReturnData());
            return false;
        }
        return parent::handle(...$_);
    }
}