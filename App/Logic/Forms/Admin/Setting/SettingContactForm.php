<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
use App\Logic\Validations\ExtendedValidator;
use Pecee\Http\Input\InputItem;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class SettingContactForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws ConfigNotRegisteredException
     * @throws FormException
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
                'inp-setting-main-phone' => 'شمار تماس اصلی',
                'inp-setting-address' => 'آدرس',
                'inp-setting-phones' => ' شماره‌های تماس',
                'inp-setting-email' => 'ایمیل',
                'inp-setting-features-title.*' => 'عنوان ویژگی',
                'inp-setting-features-sub-title.*' => 'زیر عنوان ویژگی',
            ]);

        // email
        $validator
            ->setFields('inp-setting-email')
            ->email();
        // main phone
        $validator
            ->setFields([
                'inp-setting-main-phone',
            ])
            ->required();

        // assemble features
        $features = [];
        $featuresTitle = input()->post('inp-setting-features-title', null);
        $featuresSubTitle = input()->post('inp-setting-features-sub-title', null);

        $featuresTitle = is_array($featuresTitle) ? $featuresTitle : [];
        $featuresSubTitle = is_array($featuresSubTitle) ? $featuresSubTitle : [];

        /**
         * @var InputItem $title
         */
        foreach ($featuresTitle as $k => $title) {
            if ('' != trim($title->getValue())) {
                $features[] = [
                    'title' => $title->getValue(),
                    'sub_title' => $featuresSubTitle[$k]->getValue() ?? '',
                ];
            }
        }

        session()->setFlash('setting-contact-features-assembled', $features);

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
         * @var SettingModel $settingModel
         */
        $settingModel = container()->get(SettingModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $mainPhone = input()->post('inp-setting-main-phone', '')->getValue();
            $address = input()->post('inp-setting-address', '')->getValue();
            $phones = input()->post('inp-setting-phones', '')->getValue();
            $email = input()->post('inp-setting-email', '')->getValue();
            $features = json_encode(session()->getFlash('setting-contact-features-assembled') ?: []);

            return $settingModel->updateContactSetting([
                SETTING_MAIN_PHONE => $xss->xss_clean(trim($mainPhone)),
                SETTING_ADDRESS => $xss->xss_clean(trim($address)),
                SETTING_PHONES => $xss->xss_clean(trim($phones)),
                SETTING_EMAIL => $xss->xss_clean(trim($email)),
                SETTING_FEATURES => $xss->xss_clean(trim($features)),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}