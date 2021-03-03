<?php

namespace App\Logic\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;

class CheckoutMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        // do checkout check
    }
}