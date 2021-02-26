<?php

namespace App\Logic\Forms\User\Info;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ChangeUserInfoForm implements IPageForm
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
                'inp-info-first-name' => 'نام',
                'inp-info-last-name' => 'نام خانوادگی',
                'inp-info-email' => 'ایمیل',
                'inp-info-shaba-num' => 'شماره شبا',
            ])
            ->setOptionalFields([
                'inp-info-last-name',
                'inp-info-email',
                'inp-info-shaba-num',
            ]);

        // name
        $validator
            ->setFields('inp-info-first-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha()
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // family
        $validator
            ->setFields('inp-info-last-name')
            ->persianAlpha()
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // email
        $validator
            ->setFields('inp-info-email')
            ->email();
        // shaba number
        $validator
            ->setFields('inp-info-shaba-num')
            ->isInteger();

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
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $name = input()->post('inp-info-first-name', '')->getValue();
            $family = input()->post('inp-info-last-name', '')->getValue();
            $email = input()->post('inp-info-email', '')->getValue();
            $shabaNum = input()->post('inp-info-shaba-num', '')->getValue();
            $id = session()->getFlash('the-current-user-id');

            if (empty($id)) return false;

            return $userModel->update([
                'first_name' => $xss->xss_clean(trim($name)),
                'last_name' => $xss->xss_clean(trim($family)),
                'email' => $xss->xss_clean(trim($email)),
                'shaba_number' => $xss->xss_clean(trim($shabaNum)),
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}