<?php

namespace App\Logic\Forms;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;

class LoginForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws FormException
     */
    public function validate(): array
    {
        /**
         * @var ExtendedValidator $validator
         */
        $validator = form_validator();
        $validator->reset();

        // aliases
        $validator->setFieldsAlias([
            'inp-login-username' => 'موبایل',
            'inp-login-password' => 'کلمه عبور',
            'inp-login-captcha' => 'کد تصویر',
        ]);
        // captcha
        $validator
            ->setFields('inp-register-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // username
        $validator
            ->setFields('inp-login-username')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');
        // password
        $validator
            ->setFields('inp-login-password')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true);

        // validate user status
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $info = $userModel->getFirst(
            ['is_login_locked', 'ban', 'ban_desc', 'delete'],
            'username=:u_name',
            ['u_name' => input()->post('inp-login-username', '')->getValue()]);
        if (!count($info)) {
            $validator->setError('inp-login-username', 'نام کاربری یا کلمه عبور نادرست است!');
        } elseif (DB_NO === $info['is_login_locked'] || DB_YES === $info['delete']) {
            $validator->setError('inp-login-username', 'امکان ورود با این حساب کاربری وجود ندارد.');
        } elseif (DB_YES === $info['ban']) {
            $validator->setError('inp-login-username', $info['ban_desc']);
        }

        return [
            $validator->getStatus(),
            $validator->getError(),
            $validator->getUniqueErrors(),
            $validator->getFormattedError('<p class="m-0">'),
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
            $validator->getRawErrors(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function store(): bool
    {
        // there is nothing to store
        return true;
    }
}