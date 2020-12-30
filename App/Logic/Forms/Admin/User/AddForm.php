<?php

namespace App\Logic\Forms\Admin\User;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Pecee\Http\Input\InputItem;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Form\Validations\PasswordValidation;

class AddForm implements IPageForm
{
    /**
     * {@inheritdoc]
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

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        // aliases
        $validator
            ->setFieldsAlias([
                'inp-user-active-status' => 'وضعیت',
                'inp-user-mobile' => 'موبایل',
                'inp-user-email' => 'ایمیل',
                'inp-user-role' => 'نقش',
                'inp-user-role.*' => 'نقش',
                'inp-user-password' => 'کلمه عبور',
                'inp-user-first-name' => 'نام',
                'inp-user-last-name' => 'نام خانوادگی',
            ])
            ->setOptionalFields([
                'inp-user-email',
                'inp-user-first-name',
                'inp-user-last-name',
            ]);

        // mobile
        $validator
            ->setFields('inp-user-mobile')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.')
            ->custom(function (FormValue $formValue) use ($userModel) {
                $mobile = $formValue->getValue();
                if (0 === $userModel->count('username=:username', ['username' => $mobile])) {
                    return true;
                }
                return false;
            }, 'این' . ' {alias} ' . 'قبلا ثبت شده، لطفا دوباره تلاش کنید.');
        // email
        $validator
            ->setFields('inp-user-email')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->email('{alias} ' . 'وارد شده نامعتبر است.');
        // password
        $validator
            ->setFields('inp-user-password')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->password(PasswordValidation::STRENGTH_NORMAL, '{alias} ' . 'باید شامل حروف و اعداد باشد.')
            ->greaterThanEqualLength(8, '{alias} ' . 'باید بیشتر از' . ' {min} ' . 'کاراکتر باشد.')
            ->match(
                ['کلمه عبور' => 'inp-user-password'],
                ['تایید کلمه عبور' => 'inp-user-re-password'],
                '{first} ' . 'با' . ' {second} ' . 'یکسان نمی‌باشد.'
            );
        // role
        $validator
            ->setFields('inp-user-role')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->isIn(ROLES_ARRAY_ACCEPTABLE, '{alias} ' . 'انتخاب شده نامعتبر است!');
        // name
        $validator
            ->setFields('inp-user-first-name')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // last name
        $validator
            ->setFields('inp-user-last-name')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');

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
     * {@inheritdoc]
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
            $status = input()->post('inp-user-active-status', '')->getValue();
            $activatedAt = is_value_checked($status) ? time() : null;
            $mobile = input()->post('inp-user-mobile', '')->getValue();
            $email = input()->post('inp-user-email', '')->getValue();
            $password = input()->post('inp-user-password', '')->getValue();
            $roles = input()->post('inp-user-role', '');
            $firsName = input()->post('inp-user-first-name', '')->getValue();
            $lastName = input()->post('inp-user-last-name', '')->getValue();

            // get filled roles
            if (!is_array($roles)) return false;
            $roles = array_filter($roles, function (InputItem $val) {
                if (!empty($val->getValue())) return true;
                return false;
            });
            if (!count($roles)) return false;

            return $userModel->registerUser([
                'username' => $mobile,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'first_name' => $firsName ?: null,
                'last_name' => $lastName ?: null,
                'email' => $email ?: null,
                'image' => PLACEHOLDER_USER_IMAGE,
                'is_activated' => is_value_checked($status) ? DB_YES : DB_NO,
                'activated_at' => $activatedAt,
            ], $roles);
        } catch (\Exception $e) {
            return false;
        }
    }
}