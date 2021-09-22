<?php

namespace App\Logic\Middlewares\Logic;

use App\Logic\Handlers\ResourceHandler;
use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;

class AllowCheckoutMiddleware extends AbstractMiddleware
{
    public function handle(...$_): bool
    {
        $items = cart()->getItems();
        $redirect = $_[1] ?? true;

        if (!count($items)) {
            if ($redirect) {
                response()->redirect(url('home.cart')->getRelativeUrlTrimmed());
            } else {
                $resourceHandler = new ResourceHandler();
                $resourceHandler->type(RESPONSE_TYPE_WARNING)
                    ->data('هیچ محصولی جهت تکمیل فرآیند خرید وجود ندارد!');
                response()->json($resourceHandler->getReturnData());
                return false;
            }
        }

        return parent::handle(...$_);
    }
}