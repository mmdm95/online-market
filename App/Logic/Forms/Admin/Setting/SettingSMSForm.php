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

class SettingSMSForm implements IPageForm
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
                'inp-setting-sms-activation' => 'پیامک فعالسازی حساب',
                'inp-setting-sms-recover-pass' => 'پیامک فراموشی کلمه عبور',
                'inp-setting-sms-buy' => 'پیامک خرید کالا',
                'inp-setting-sms-order-status' => 'پیامک تغییر وضعیت سفارش',
                'inp-setting-sms-wallet-charge' => 'پیامک شارژ حساب کاربری',
            ]);

        // sms
        $validator
            ->setFields([
                'inp-setting-sms-activation',
                'inp-setting-sms-recover-pass',
                'inp-setting-sms-buy',
                'inp-setting-sms-order-status',
                'inp-setting-sms-wallet-charge',
            ])
            ->required();

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
            $activation = input()->post('inp-setting-sms-activation', '')->getValue();
            $recoverPass = input()->post('inp-setting-sms-recover-pass', '')->getValue();
            $buy = input()->post('inp-setting-sms-buy', '')->getValue();
            $orderStatus = input()->post('inp-setting-sms-order-status', '')->getValue();
            $walletCharge = input()->post('inp-setting-sms-wallet-charge', '')->getValue();

            return $settingModel->updateSMSSetting([
                SETTING_SMS_ACTIVATION => $xss->xss_clean(trim($activation)),
                SETTING_SMS_RECOVER_PASS => $xss->xss_clean(trim($recoverPass)),
                SETTING_SMS_BUY => $xss->xss_clean(trim($buy)),
                SETTING_SMS_ORDER_STATUS => $xss->xss_clean(trim($orderStatus)),
                SETTING_SMS_WALLET_CHARGE => $xss->xss_clean(trim($walletCharge)),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}