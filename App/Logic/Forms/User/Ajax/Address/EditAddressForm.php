<?php

namespace App\Logic\Forms\User\Ajax\Address;

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
            'inp-edit-address-full-name' => 'نام گیرنده',
            'inp-edit-address-mobile' => 'موبایل',
            'inp-edit-address-province' => 'استان',
            'inp-edit-address-city' => 'شهر',
            'inp-edit-address-postal-code' => 'کد پستی',
            'inp-edit-address-address' => 'آدرس',
        ]);

        // full name
        $validator
            ->setFields('inp-edit-address-full-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha();
        // mobile
        $validator
            ->setFields('inp-edit-address-mobile')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');
        // province
        $validator
            ->setFields('inp-edit-address-province')
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
            ->setFields('inp-edit-address-city')
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
            ->setFields('inp-edit-address-postal-code')
            ->required();
        // address
        $validator
            ->setFields('inp-edit-address-address')
            ->required();

        // check if address is existed
        $id = session()->getFlash('user-address-edit-addr-id', null, false);
        if (!empty($id)) {
            /**
             * @var AddressModel $addressModel
             */
            $addressModel = container()->get(AddressModel::class);
            if (0 === $addressModel->count('id=:id', ['id' => $id])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-address-full-name', 'شناسه آدرس نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-address-full-name', 'شناسه آدرس نامعتبر است.');
        }

        // check if user is existed
        $userId = session()->getFlash('user-address-edit-id', null, false);
        if (!empty($userId)) {
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);
            if (0 === $userModel->count('id=:id', ['id' => $userId])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-edit-address-full-name', 'شناسه کاربر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-address-full-name', 'شناسه کاربر نامعتبر است.');
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
            $userId = session()->getFlash('user-address-edit-id', null);
            $id = session()->getFlash('user-address-edit-addr-id', null);
            $name = input()->post('inp-edit-address-full-name', '')->getValue();
            $mobile = input()->post('inp-edit-address-mobile', '')->getValue();
            $province = input()->post('inp-edit-address-province', '')->getValue();
            $city = input()->post('inp-edit-address-city', '')->getValue();
            $postalCode = input()->post('inp-edit-address-postal-code', '')->getValue();
            $address = input()->post('inp-edit-address-address', '')->getValue();

            if (is_null($userId)) return false;

            $res = $addressModel->update([
                'full_name' => $xss->xss_clean($name),
                'mobile' => $xss->xss_clean(StringUtil::toEnglish($mobile)),
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
