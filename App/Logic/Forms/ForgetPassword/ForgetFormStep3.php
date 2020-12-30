<?php

namespace App\Logic\Forms\ForgetPassword;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\Validations\PasswordValidation;

class ForgetFormStep3 implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
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
            'inp-forget-new-password' => 'کلمه عبور',
        ]);
        // password
        $validator
            ->setFields('inp-forget-new-password')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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