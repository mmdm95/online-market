<?php

namespace App\Logic\Forms\Admin\Setting;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CityModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\SettingModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class SettingBuyForm implements IPageForm
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
                'inp-setting-store-province' => 'استان محل فروشگاه',
                'inp-setting-store-city' => 'شهر محل فروشگاه',
                'inp-setting-current-city-post-price' => 'قیمت در مناظق داخل شهر انتخاب شده',
                'inp-setting-min-free-price' => 'حداقل قیمت رایگان شدن هزینه ارسال',
            ])
            ->setOptionalFields([
                'inp-setting-current-city-post-price',
                'inp-setting-min-free-price',
            ]);

        // store province
        $validator
            ->setFields('inp-setting-store-province')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var ProvinceModel $provinceModel
                 */
                $provinceModel = container()->get(ProvinceModel::class);
                if ($provinceModel->count('id=:id', ['id' => $value->getValue()]) > 0) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // store city
        $validator
            ->setFields('inp-setting-store-city')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var CityModel $cityModel
                 */
                $cityModel = container()->get(CityModel::class);
                if ($cityModel->count('id=:id', ['id' => $value->getValue()]) > 0) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // current city price
        // min free price
        $validator
            ->setFields([
                'inp-setting-current-city-post-price',
                'inp-setting-min-free-price'
            ])
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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
            $ownProvince = input()->post('inp-setting-store-province', '')->getValue();
            $ownCity = input()->post('inp-setting-store-city', '')->getValue();
            $cityPostPrice = input()->post('inp-setting-current-city-post-price', '')->getValue();
            $minFreePrice = input()->post('inp-setting-min-free-price', '')->getValue();

            return $settingModel->updateBuySetting([
                SETTING_STORE_PROVINCE => $xss->xss_clean(trim($ownProvince)),
                SETTING_STORE_CITY => $xss->xss_clean(trim($ownCity)),
                SETTING_CURRENT_CITY_POST_PRICE => $xss->xss_clean(trim($cityPostPrice)),
                SETTING_MIN_FREE_PRICE => $xss->xss_clean(trim($minFreePrice)),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}