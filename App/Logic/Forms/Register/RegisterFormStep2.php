<?php

namespace App\Logic\Forms\Register;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;

class RegisterFormStep2 implements IPageForm
{
    /**
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
            'inp-register-code' => 'کد ارسال شده',
        ]);
        // code
        $validator
            ->setFields('inp-register-code')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true);

        // validate code
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $code = $userModel->getFirst(
            ['activate_code'],
            'username=:u_name',
            ['u_name' => session()->getFlash('register.username', '', false)]);
        if (input()->post('inp-register-code', null)->getValue() !== $code['activate_code'] ?? '') {
            $validator->setError('inp-register-code', 'کد وارد شده صحیح نمی‌باشد.');
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
     * @return mixed
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

        $username = session()->getFlash('register.username', '', false);
        // insert to database
        $res = $userModel->update([
            'is_activated' => DB_YES,
            'incorrect_password_count' => 0,
            'activate_code' => null,
            'activate_code_request_free_at' => null,
            'activated_at' => time(),
        ], 'username=:u_name', ['u_name' => $username]);

        return $res;
    }
}