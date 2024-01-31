<?php

namespace App\Logic\Forms\Ajax\Address\Company;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\AddressModel;
use App\Logic\Models\CityModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddAddressForm implements IPageForm
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
            'inp-add-address-company-name' => 'نام شرکت',
            'inp-add-address-company-economic-code' => 'کد اقتصادی',
            'inp-add-address-company-economic-national-id' => 'شناسه ملی',
            'inp-add-address-company-registration-number' => 'شماره ثبت',
            'inp-add-address-company-landline-tel' => 'تلفن ثابت',
            'inp-add-address-company-province' => 'استان',
            'inp-add-address-company-city' => 'شهر',
            'inp-add-address-company-postal-code' => 'کد پستی',
            'inp-add-address-company-addr' => 'آدرس',
        ]);

        // eco code
        $validator
            ->setFields('inp-add-address-company-economic-code')
            ->required();
        // eco national id
        $validator
            ->setFields('inp-add-address-company-economic-national-id')
            ->required();
        // eco reg number
        $validator
            ->setFields('inp-add-address-company-registration-number')
            ->required();
        // company name
        $validator
            ->setFields('inp-add-address-company-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha();
        // tel
        $validator
            ->setFields('inp-add-address-company-landline-tel')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('^\d{11}$', '{alias} ' . 'نامعتبر است.');
        // province
        $validator
            ->setFields('inp-add-address-company-province')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $formValue) {
                /**
                 * @var ProvinceModel $provinceModel
                 */
                $provinceModel = container()->get(ProvinceModel::class);
                if (0 !== $provinceModel->count('id=:id', ['id' => $formValue->getValue()])) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // city
        $validator
            ->setFields('inp-add-address-company-city')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $formValue) {
                /**
                 * @var CityModel $cityModel
                 */
                $cityModel = container()->get(CityModel::class);
                if (0 !== $cityModel->count('id=:id', ['id' => $formValue->getValue()])) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'انتخاب شده نامعتبر است.');
        // postal code
        $validator
            ->setFields('inp-add-address-company-postal-code')
            ->required();
        // address
        $validator
            ->setFields('inp-add-address-company-addr')
            ->required();

        // check if user is existed
        $userId = session()->getFlash('addr-add-user-id', null, false);
        if (!empty($userId)) {
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);
            if (0 === $userModel->count('id=:id', ['id' => $userId])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-address-company-name', 'شناسه کاربر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-add-address-company-name', 'شناسه کاربر نامعتبر است.');
        }

        // check if address is not exceed max number
        if (!empty($userId)) {
            /**
             * @var AddressModel $addressModel
             */
            $addressModel = container()->get(AddressModel::class);
            if ($addressModel->count('user_id=:uId', ['uId' => $userId]) >= ADDRESS_MAX_COUNT) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-address-company-name', 'تعداد آدرس‌ها به حداکثر خود رسیده است.');
            }
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getUniqueErrors(),
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
         * @var AddressModel $addressModel
         */
        $addressModel = container()->get(AddressModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $userId = session()->getFlash('addr-company-add-user-id', null);
            $name = input()->post('inp-add-address-company-name', '')->getValue();
            $ecoCode = input()->post('inp-add-address-company-economic-code', '')->getValue();
            $ecoId = input()->post('inp-add-address-company-economic-national-id', '')->getValue();
            $regNum = input()->post('inp-add-address-company-registration-number', '')->getValue();
            $tel = input()->post('inp-add-address-company-landline-tel', '')->getValue();
            $province = input()->post('inp-add-address-company-province', '')->getValue();
            $city = input()->post('inp-add-address-company-city', '')->getValue();
            $postalCode = input()->post('inp-add-address-company-postal-code', '')->getValue();
            $address = input()->post('inp-add-address-company-addr', '')->getValue();

            if (is_null($userId)) return false;

            $res = $addressModel->insert([
                'user_id' => $userId,
                'company_name' => $xss->xss_clean($name),
                'economic_code' => $xss->xss_clean($ecoCode),
                'economic_national_id' => $xss->xss_clean($ecoId),
                'registration_number' => $xss->xss_clean($regNum),
                'landline_tel' => $xss->xss_clean(StringUtil::toEnglish($tel)),
                'address' => $xss->xss_clean($address),
                'city_id' => $xss->xss_clean($city),
                'province_id' => $xss->xss_clean($province),
                'postal_code' => $xss->xss_clean($postalCode),
                'created_at' => time(),
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}
