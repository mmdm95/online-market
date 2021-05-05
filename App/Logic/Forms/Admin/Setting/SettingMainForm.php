<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SettingModel;
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

class SettingMainForm implements IPageForm
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
                'inp-setting-logo-img' => 'لوگو',
                'inp-setting-logo-light-img' => 'لوگوی سفید',
                'inp-setting-fav-img' => 'فاو آیکون',
                'inp-setting-title' => 'عنوان',
                'inp-setting-logo-footer' => 'لوگوی پاورقی',
                'inp-setting-logo-light-footer' => 'لوگوی سفید پاورقی',
            ]);

        // images
        $validator
            ->setFields([
                'inp-setting-logo-img',
                'inp-setting-logo-light-img',
                'inp-setting-fav-img',
                'inp-setting-logo-footer',
                'inp-setting-logo-light-footer',
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists();
        // title
        $validator
            ->setFields('inp-setting-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(50);

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
            $logo = input()->post('inp-setting-logo-img', '')->getValue();
            $logoWhite = input()->post('inp-setting-logo-light-img', '')->getValue();
            $favicon = input()->post('inp-setting-fav-img', '')->getValue();
            $logoFooter = input()->post('inp-setting-logo-footer', '')->getValue();
            $logoLightFooter = input()->post('inp-setting-logo-light-footer', '')->getValue();
            $title = input()->post('inp-setting-title', '')->getValue();
            $desc = input()->post('inp-setting-desc', '')->getValue();
            $tags = input()->post('inp-setting-tags', '')->getValue();

            return $settingModel->updateMainSetting([
                SETTING_LOGO => $xss->xss_clean(trim($logo)),
                SETTING_LOGO_LIGHT => $xss->xss_clean(trim($logoWhite)),
                SETTING_FAVICON => $xss->xss_clean(trim($favicon)),
                SETTING_LOGO_FOOTER => $xss->xss_clean(trim($logoFooter)),
                SETTING_LOGO_LIGHT_FOOTER => $xss->xss_clean(trim($logoLightFooter)),
                SETTING_TITLE => $xss->xss_clean(trim($title)),
                SETTING_DESCRIPTION => $xss->xss_clean(trim($desc)),
                SETTING_KEYWORDS => $xss->xss_clean(trim($tags)),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}