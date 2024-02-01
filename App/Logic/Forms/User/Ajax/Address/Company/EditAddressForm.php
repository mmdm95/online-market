<?php

namespace App\Logic\Forms\User\Ajax\Address\Company;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\AddressCompanyModel;
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

class EditAddressForm implements IPageForm
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
            'inp-edit-address-company-name' => 'نام شرکت',
            'inp-edit-address-company-economic-code' => 'کد اقتصادی',
            'inp-edit-address-company-economic-national-id' => 'شناسه ملی',
            'inp-edit-address-company-registration-number' => 'شماره ثبت',
            'inp-edit-address-company-landline-tel' => 'تلفن ثابت',
            'inp-edit-address-company-province' => 'استان',
            'inp-edit-address-company-city' => 'شهر',
            'inp-edit-address-company-postal-code' => 'کد پستی',
            'inp-edit-address-company-addr' => 'آدرس',
        ]);

        // eco code
        $validator
            ->setFields('inp-edit-address-company-economic-code')
            ->required();
        // eco national id
        $validator
            ->setFields('inp-edit-address-company-economic-national-id')
            ->required();
        // eco reg number
        $validator
            ->setFields('inp-edit-address-company-registration-number')
            ->required();
        // company name
        $validator
            ->setFields('inp-edit-address-company-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha();
        // tel
        $validator
            ->setFields('inp-edit-address-company-landline-tel')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/^\d{11}$/', '{alias} ' . 'نامعتبر است.');
        // province
        $validator
            ->setFields('inp-edit-address-company-province')
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
            ->setFields('inp-edit-address-company-city')
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
            ->setFields('inp-edit-address-company-postal-code')
            ->required();
        // address
        $validator
            ->setFields('inp-edit-address-company-addr')
            ->required();

        // check if address is existed
        $id = session()->getFlash('user-address-company-edit-addr-id', null, false);
        if (!empty($id)) {
            /**
             * @var AddressCompanyModel $addressCompanyModel
             */
            $addressCompanyModel = container()->get(AddressCompanyModel::class);
            if (0 === $addressCompanyModel->count('id=:id', ['id' => $id])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-address-company-name', 'شناسه آدرس نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-address-company-name', 'شناسه آدرس نامعتبر است.');
        }

        // check if user is existed
        $userId = session()->getFlash('user-address-company-edit-id', null, false);
        if (!empty($userId)) {
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);
            if (0 === $userModel->count('id=:id', ['id' => $userId])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-address-company-name', 'شناسه کاربر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-address-company-name', 'شناسه کاربر نامعتبر است.');
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
         * @var AddressCompanyModel $addressCompanyModel
         */
        $addressCompanyModel = container()->get(AddressCompanyModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $id = session()->getFlash('user-address-company-edit-addr-id', null);
            $userId = session()->getFlash('user-address-company-edit-id', null);
            $name = input()->post('inp-edit-address-company-name', '')->getValue();
            $ecoCode = input()->post('inp-edit-address-company-economic-code', '')->getValue();
            $ecoId = input()->post('inp-edit-address-company-economic-national-id', '')->getValue();
            $regNum = input()->post('inp-edit-address-company-registration-number', '')->getValue();
            $tel = input()->post('inp-edit-address-company-landline-tel', '')->getValue();
            $province = input()->post('inp-edit-address-company-province', '')->getValue();
            $city = input()->post('inp-edit-address-company-city', '')->getValue();
            $postalCode = input()->post('inp-edit-address-company-postal-code', '')->getValue();
            $address = input()->post('inp-edit-address-company-addr', '')->getValue();

            if (is_null($id) || is_null($userId)) return false;

            $res = $addressCompanyModel->update([
                'company_name' => $xss->xss_clean($name),
                'economic_code' => $xss->xss_clean($ecoCode),
                'economic_national_id' => $xss->xss_clean($ecoId),
                'registration_number' => $xss->xss_clean($regNum),
                'landline_tel' => $xss->xss_clean(StringUtil::toEnglish($tel)),
                'address' => $xss->xss_clean($address),
                'city_id' => $xss->xss_clean($city),
                'province_id' => $xss->xss_clean($province),
                'postal_code' => $xss->xss_clean($postalCode),
                'updated_at' => time(),
            ], 'id=:id AND user_id=:uId', ['id' => $id, 'uId' => $userId]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}
