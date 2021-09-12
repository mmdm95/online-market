<?php

namespace App\Logic\Forms\ForgetPassword;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\Validations\PasswordValidation;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ForgetFormStep3 implements IPageForm
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
            'inp-forget-new-password' => 'کلمه عبور',
            'inp-forget-new-re-password' => 'تکرار کلمه عبور',
        ]);
        // password
        $validator
            ->setFields('inp-forget-new-password')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->password(PasswordValidation::STRENGTH_NORMAL, '{alias} ' . 'باید شامل حروف و اعداد باشد.')
            ->greaterThanEqualLength(8, '{alias} ' . 'باید بیشتر از' . ' {min} ' . 'کاراکتر باشد.')
            ->match(
                ['کلمه عبور' => 'inp-forget-new-password'],
                ['تایید کلمه عبور' => 'inp-forget-new-re-password'],
                '{first} ' . 'با' . ' {second} ' . 'یکسان نمی‌باشد.'
            );

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        try {
            $username = session()->getFlash('forget.username', '', false);
            $password = input()->post('inp-forget-new-password', '')->getValue();
            // insert to database
            $res = $userModel->update([
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'forget_password_at' => time(),
            ], 'username=:u_name', ['u_name' => $username]);
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }
}