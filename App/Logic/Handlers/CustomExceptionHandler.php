<?php

namespace App\Logic\Handlers;

use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;

class CustomExceptionHandler implements IExceptionHandler
{
    /**
     * @param Request $request
     * @param \Exception $error
     * @throws \Exception
     */
    public function handleError(Request $request, \Exception $error): void
    {
        /* You can use the exception handler to format errors depending on the request and type. */
        if ($request->getUrl()->contains('/api') ||
            $request->getUrl()->contains('/ajax')) {
            $resourceHandler = new ResourceHandler();
            $resourceHandler->type(RESPONSE_TYPE_ERROR)->statusCode((int)$error->getCode())->errorMessage($error->getMessage());
            response()->httpCode((int)$error->getCode())->json($resourceHandler->getReturnData());
        }

        /* The router will throw the NotFoundHttpException on 404 */
        if ($error instanceof NotFoundHttpException) {
            if ($request->getUrl()->contains('/admin')) {
                // Render custom admin 404-page
                $request->setRewriteUrl(url(NOT_FOUND_ADMIN));
            } else {
                // Render custom 404-page
                $request->setRewriteUrl(url(NOT_FOUND));
            }
            return;
        } elseif ($error->getCode() == 500) {
            // Render custom 500-page
            $request->setRewriteUrl(url(SERVER_ERROR));
            return;
        }

        throw $error;
    }
}