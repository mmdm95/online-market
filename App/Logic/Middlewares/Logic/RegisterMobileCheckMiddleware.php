<?php

namespace App\Logic\Middlewares\Logic;

use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Form\Validations\PersianMobileValidation;

class RegisterMobileCheckMiddleware extends AbstractMiddleware
{
    public function handle(...$_): bool
    {
        $mobile = session()->getFlash('register.username', null, false);
        if (is_null($mobile)) return false;

        $mobileValidator = new PersianMobileValidation();
        $isValid = $mobileValidator->validate($mobile);

        if (!$isValid) return false;

        return parent::handle(...$_);
    }
}