<?php

namespace App\Logic\Forms\ForgetPassword;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ForgetFormStep2 implements IPageForm
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
            'inp-forget-code' => 'کد ارسال شده',
        ]);
        // code
        $validator
            ->setFields('inp-forget-code')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true);

        // validate code
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $code = $userModel->getFirst(
            ['forget_password_code'],
            'username=:u_name',
            ['u_name' => session()->getFlash('forget.username', '', false)]);
        if (input()->post('inp-forget-code', null)->getValue() !== $code['forget_password_code'] ?? '') {
            $validator->setStatus(false)->setError('inp-forget-code', 'کد وارد شده صحیح نمی‌باشد.');
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
     */
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

        $username = session()->getFlash('forget.username', '', false);
        // insert to database
        $res = $userModel->update([
            'incorrect_password_count' => 0,
            'forget_password_code' => null,
            'activate_code_request_free_at' => null,
        ], 'username=:u_name', ['u_name' => $username]);

        return $res;
    }
}