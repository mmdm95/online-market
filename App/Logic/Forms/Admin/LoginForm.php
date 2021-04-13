<?php

namespace App\Logic\Forms\Admin;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class LoginForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws FormException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
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
            'inp-username' => 'موبایل',
            'inp-password' => 'کلمه عبور',
            'inp-login-captcha' => 'کد تصویر',
        ]);
        // captcha
        $validator
            ->setFields('inp-login-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // username
        $validator
            ->setFields('inp-username')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');
        // password
        $validator
            ->setFields('inp-password')
            ->required();

        // validate user status
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $info = $userModel->getFirst(
            ['is_login_locked', 'ban', 'ban_desc', 'is_deleted'],
            'username=:u_name',
            ['u_name' => input()->post('inp-username', '')->getValue()]);
        if (!count($info)) {
            $validator->setStatus(false)->setError('inp-username', 'نام کاربری یا کلمه عبور نادرست است!');
        } elseif (DB_NO === $info['is_login_locked'] || DB_YES === $info['is_deleted']) {
            $validator->setStatus(false)->setError('inp-username', 'امکان ورود با این حساب کاربری وجود ندارد.');
        } elseif (DB_YES === $info['ban']) {
            $validator->setStatus(false)->setError('inp-username', $info['ban_desc']);
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getUniqueErrors(),
            $validator->getError(),
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