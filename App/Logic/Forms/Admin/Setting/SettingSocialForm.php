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

class SettingSocialForm implements IPageForm
{
    /**
     * {@inheritdoc}
     */
    public function validate(): array
    {
        return [
            true,
            [],
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
            $telegram = input()->post('inp-setting-telegram', '')->getValue();
            $instagram = input()->post('inp-setting-instagram', '')->getValue();
            $whatsapp = input()->post('inp-setting-whatsapp', '')->getValue();

            return $settingModel->updateSocialSetting([
                SETTING_SOCIAL_TELEGRAM => $xss->xss_clean(trim($telegram)),
                SETTING_SOCIAL_INSTAGRAM => $xss->xss_clean(trim($instagram)),
                SETTING_SOCIAL_WHATSAPP => $xss->xss_clean(trim($whatsapp)),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}