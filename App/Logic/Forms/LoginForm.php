<?php

namespace App\Logic\Forms;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class LoginForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
     * @throws ConfigNotRegisteredException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
            ->setFields('inp-login-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // username
        $validator
            ->setFields('inp-login-username')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');
        // password
        $validator
            ->setFields('inp-login-password')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true);

        // validate user status
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $info = $userModel->getFirst(
            ['is_login_locked', 'ban', 'ban_desc', 'is_deleted'],
            'username=:u_name',
            ['u_name' => input()->post('inp-login-username', '')->getValue()]);
        if (!count($info)) {
            $validator->setStatus(false)->setError('inp-login-username', 'نام کاربری یا کلمه عبور نادرست است!');
        } elseif (DB_YES == $info['is_login_locked'] || DB_YES == $info['is_deleted']) {
            $validator->setStatus(false)->setError('inp-login-username', 'امکان ورود با این حساب کاربری وجود ندارد.');
        } elseif (DB_YES == $info['ban']) {
            $validator->setStatus(false)->setError('inp-login-username', $info['ban_desc']);
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