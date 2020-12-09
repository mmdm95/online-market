<?php

namespace App\Logic\Middlewares\Logic;

use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Form\Validations\PersianMobileValidation;

class ForgetCodeCheckMiddleware extends AbstractMiddleware
{
    public function handle(...$_): bool
    {
        $code = session()->getFlash('forger.code-step', null, false);
        if (is_null($code)) return false;

        $isValid = 'I am ready to set password' === $code;

        if (!$isValid) return false;

        return parent::handle(...$_);
    }
}