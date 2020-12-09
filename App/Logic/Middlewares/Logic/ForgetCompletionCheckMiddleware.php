<?php

namespace App\Logic\Middlewares\Logic;

use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Form\Validations\PersianMobileValidation;

class ForgetCompletionCheckMiddleware extends AbstractMiddleware
{
    public function handle(...$_): bool
    {
        $isComplete = session()->getFlash('forget.completion', null, false);
        if (is_null($isComplete)) return false;

        $isValid = 'Password changed!' === $isComplete;

        if (!$isValid) return false;

        return parent::handle(...$_);
    }
}