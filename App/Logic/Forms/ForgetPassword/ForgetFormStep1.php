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
use Sim\Utils\StringUtil;

class ForgetFormStep1 implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
            'inp-forget-mobile' => 'موبایل',
            'inp-forget-captcha' => 'کد تصویر',
        ]);
        // captcha
        $validator
            ->setFields('inp-forget-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // username
        $validator
            ->setFields('inp-forget-mobile')
            ->stopValidationAfterFirstError(false)
            ->required('{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $username = input()->post('inp-forget-mobile', '')->getValue();
        $hasActiveUsername = $userModel->count('username=:u_name AND is_activated=:active', [
            'u_name' => $username,
            'active' => DB_YES,
        ]);
        if (0 === $hasActiveUsername) {
            $validator->setError('inp-forget-mobile', 'این شماره موبایل وجود ندارد!');
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

        $res = false;
        $username = input()->post('inp-forget-mobile', '')->getValue();
        if (!empty($username)) {
            $code = StringUtil::randomString(6, StringUtil::RS_NUMBER);
            // insert to database
            $res = $userModel->update([
                'forget_password_code' => $code,
                'activate_code_request_free_at' => time() + TIME_ACTIVATE_CODE,
            ], 'username=:username', [
                'username' => $username
            ]);

            if ($res) {
                session()->setFlash('forget.code', $code);
            }
        }

        return $res;
    }
}