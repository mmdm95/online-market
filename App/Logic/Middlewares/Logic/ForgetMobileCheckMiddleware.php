<?php

namespace App\Logic\Middlewares\Logic;

use App\Logic\Models\UserModel;
use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Form\Validations\PersianMobileValidation;

class ForgetMobileCheckMiddleware extends AbstractMiddleware
{
    public function handle(...$_): bool
    {
        $mobile = session()->getFlash('forget.username', null, false);
        if (is_null($mobile)) return false;

        /**
         * @var PersianMobileValidation $mobileValidator
         */
        $mobileValidator = container()->get(PersianMobileValidation::class);
        $isValid = $mobileValidator->validate($mobile);
        if (!$isValid) return false;

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $count = $userModel->count('username=:username', ['username' => $mobile]);
        if (1 !== $count) return false;

        return parent::handle(...$_);
    }
}