<?php

namespace App\Logic\Forms\User\Info;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ChangeUserInfoForm implements IPageForm
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
        $validator
            ->setFieldsAlias([
                'inp-info-first-name' => 'نام',
                'inp-info-last-name' => 'نام خانوادگی',
                'inp-info-email' => 'ایمیل',
                'inp-info-national-num' => 'کد ملی',
                'inp-info-shaba-num' => 'شماره شبا',
            ])
            ->toEnglishValueFields([
                'inp-info-national-num',
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
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha()
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // email
        $validator
            ->setFields('inp-info-email')
            ->email();
        // national number
        $validator
            ->setFields('inp-info-national-num')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianNationalCode();
        // shaba number
        $validator
            ->setFields('inp-info-shaba-num')
            ->regex('/[0-9*]/');

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

        try {
            $name = input()->post('inp-info-first-name', '')->getValue();
            $family = input()->post('inp-info-last-name', '')->getValue();
            $email = input()->post('inp-info-email', '')->getValue();
            $nationalNum = input()->post('inp-info-national-num', '')->getValue();
            $shabaNum = input()->post('inp-info-shaba-num', '')->getValue();
            $id = session()->getFlash('the-current-user-id');

            if (empty($id)) return false;

            return $userModel->update([
                'first_name' => $xss->xss_clean(trim($name)),
                'last_name' => $xss->xss_clean(trim($family)),
                'email' => $xss->xss_clean(trim($email)),
                'national_number' => $xss->xss_clean(trim($nationalNum)),
                'shaba_number' => $xss->xss_clean(trim($shabaNum)),
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
