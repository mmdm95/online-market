<?php

namespace App\Logic\Forms\Register;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class RegisterFormStep1 implements IPageForm
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
            'inp-register-username' => 'موبایل',
            'inp-register-captcha' => 'کد تصویر',
        ]);
        // captcha
        $validator
            ->setFields('inp-register-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // username
        $validator
            ->setFields('inp-register-username')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $username = input()->post('inp-register-username', '')->getValue();
        $hasActiveUsername = $userModel->count('username=:u_name AND is_activated=:active', [
            'u_name' => $username,
            'active' => DB_YES,
        ]);
        if (0 !== $hasActiveUsername) {
            $validator->setError('inp-register-username', 'این شماره موبایل قبلا ثبت شده است.');
        } else {
            $userModel->delete('username=:u_name', ['u_name' => $username]);
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
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        $res = false;
        $username = input()->post('inp-register-username', '')->getValue();
        if (!empty($username)) {
            $code = StringUtil::randomString(6, StringUtil::RS_NUMBER);
            // insert to database
            $res = $userModel->insert([
                'username' => $xss->xss_clean(StringUtil::toEnglish($username)),
                'password' => '',
                'is_activated' => DB_NO,
                'activate_code' => $code,
                'activate_code_request_free_at' => time() + TIME_ACTIVATE_CODE,
                'created_at' => time(),
            ]);

            if ($res) {
                session()->setFlash('register.code', $code);
            }
        }

        return $res;
    }
}