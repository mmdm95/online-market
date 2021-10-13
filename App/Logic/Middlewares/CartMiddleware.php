<?php

namespace App\Logic\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;

class CartMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        if (!count(cart()->restore(true)->getItems())) {
            redirect(url('home.cart')->getRelativeUrlTrimmed());
        }
    }
}
