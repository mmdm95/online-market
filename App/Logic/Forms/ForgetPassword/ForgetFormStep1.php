<?php

namespace App\Logic\Forms\ForgetPassword;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;

class ForgetFormStep1 implements IPageForm
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
            ->required()
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
            $validator->setStatus(false)->setError('inp-forget-mobile', 'این شماره موبایل وجود ندارد!');
        }

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
            $res = false;
            $username = input()->post('inp-forget-mobile', '')->getValue();
            if (!empty($username)) {
                if($userModel->count('username=:username AND recover_password_type=:rpt', ['username' => $username, 'rpt' => RECOVER_PASS_TYPE_SMS])) {
                    $code = StringUtil::randomString(6, StringUtil::RS_NUMBER);
                    // insert to database
                    $res = $userModel->update([
                        'forget_password_code' => $code,
                    ], 'username=:username', [
                        'username' => $username
                    ]);

                    if ($res) {
                        session()->setFlash('forget.code', $code);
                    }
                } else {
                    return true;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }
}