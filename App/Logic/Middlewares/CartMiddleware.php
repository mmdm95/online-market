<?php

namespace App\Logic\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use Sim\Auth\DBAuth;

class CartMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        if (!count(cart()->restore()->getItems())) {
            redirect(url('home.cart')->getRelativeUrlTrimmed());
        }
    }
}
