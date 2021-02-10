<?php

namespace App\Logic\Adapters;

use App\Logic\Handlers\ResourceHandler;
use Pecee\Http\Middleware\BaseCsrfVerifier;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\Http\Request;

class CsrfVerifier extends BaseCsrfVerifier
{
    /**
     * CSRF validation will be ignored on the following urls.
     */
    protected $except = [
        '/api/*',
        '/ajax/file-manager/upload',
    ];

    /**
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        // there is no need for this for now, if needed, uncomment commented sections
        try {
            parent::handle($request);
        } catch (TokenMismatchException $e) {
            if ($request->getUrl()->contains('/api') ||
                $request->getUrl()->contains('/ajax')) {
                if (!headers_sent()) {
                    $resourceHandler = new ResourceHandler();
                    $resourceHandler->statusCode(403)->errorMessage('CSRF token mismatched! Do operation again.');
                    response()->httpCode(403)->json($resourceHandler->getReturnData());
                }
            }
//            } else {
//                \session()->setFlash('CSRFRouteHasUndefined', 'توکن امنیتی دوباره تولید شد، لطفا عملیات را دوباره انجام دهید! نیازی به بارگذاری مجدد صفحه نمی‌باشد.');
//            }
        }
    }
}