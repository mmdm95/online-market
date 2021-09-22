<?php

namespace App\Logic\Middlewares\Logic;

use App\Logic\Handlers\ResourceHandler;
use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Auth\DBAuth;

class NeedLoginResponseMiddleware extends AbstractMiddleware
{
    /**
     * @param mixed ...$_
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function handle(...$_): bool
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');

        if (!$auth->isLoggedIn()) {
            $resourceHandler = new ResourceHandler();
            $resourceHandler->type(RESPONSE_TYPE_WARNING)->data('لطفا ابتدا به پنل کاربری خود وارد شوید.');
            response()->json($resourceHandler->getReturnData());
            return false;
        }
        return parent::handle(...$_);
    }
}