<?php

namespace App\Logic\Forms\User\Info;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Models\BlogModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\Jdf;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Form\Validations\PasswordValidation;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class ChangeUserPasswordForm implements IPageForm
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
        $validator
            ->setFieldsAlias([
                'inp-pass-prev-password' => 'کلمه عبور قبلی',
                'inp-pass-password' => 'کلمه عبور جدید',
                'inp-pass-re-password' => 'تکرار کلمه عبور جدید',
            ]);

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $id = session()->getFlash('the-current-user-id', null, false);
        if (!empty($id)) {
            $password = $userModel->getFirst(['password'], 'id=:id', ['id' => $id])['password'];

            // previous password
            $validator
                ->setFields('inp-pass-prev-password')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->custom(function (FormValue $value) use ($password) {
                    if (password_verify($value->getValue(), $password)) {
                        return true;
                    }
                    return false;
                }, '{alias} ' . 'نادرست است!');
            // password
            $validator
                ->setFields('inp-pass-password')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->password(PasswordValidation::STRENGTH_NORMAL, '{alias} ' . 'باید شامل حروف و اعداد باشد.')
                ->greaterThanEqualLength(8, '{alias} ' . 'باید بیشتر از' . ' {min} ' . 'کاراکتر باشد.')
                ->match(
                    ['کلمه عبور' => 'inp-pass-password'],
                    ['تایید کلمه عبور' => 'inp-pass-re-password'],
                    '{first} ' . 'با' . ' {second} ' . 'یکسان نمی‌باشد.'
                )
                ->custom(function (FormValue $value) use ($password) {
                    if (!password_verify($value->getValue(), $password)) {
                        return true;
                    }
                    return false;
                }, $validator->getFieldAlias('inp-pass-prev-password') . ' نمی‌تواند با' . ' {alias} ' . 'برابر باشد.');
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-pass-prev-password', 'شناسه کاربر نامعتبر است.');
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
            $password = input()->post('inp-pass-password', '')->getValue();
            $id = session()->getFlash('the-current-user-id');

            if (empty($id)) return false;

            return $userModel->update([
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}