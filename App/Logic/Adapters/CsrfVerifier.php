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
        '/finish/*',
        '/ajax/file-manager/rename',
        '/ajax/file-manager/delete',
        '/ajax/file-manager/mkdir',
        '/ajax/file-manager/mvdir',
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
            // Refresh existing token
            $this->tokenProvider->refresh();
            if ($request->getUrl()->contains('/api') ||
                $request->getUrl()->contains('/ajax')) {
                // generate csrf token in case of mismatch
//                csrf_token_generate();

                response()->httpCode(403)->json(['توکن امنیتی دچار مشکل شد! عملیات را مجددا انجام دهید.']);
            }
//            } else {
//                \session()->setFlash('CSRFRouteIsUndefined', 'توکن امنیتی دوباره تولید شد، لطفا عملیات را دوباره انجام دهید! نیازی به بارگذاری مجدد صفحه نمی‌باشد.');
//            }
        }
    }
}