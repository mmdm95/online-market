<?php

namespace App\Logic\Forms\User\Ajax\Address;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\AddressModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
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
            'inp-add-address-full-name' => 'نام گیرنده',
            'inp-add-address-mobile' => 'موبایل',
            'inp-add-address-province' => 'استان',
            'inp-add-address-city' => 'شهر',
            'inp-add-address-postal-code' => 'کد پستی',
            'inp-add-address-addr' => 'آدرس',
        ]);

        // full name
        $validator
            ->setFields('inp-add-address-full-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha();
        // mobile
        $validator
            ->setFields('inp-add-address-mobile')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');
        // province
        $validator
            ->setFields('inp-add-address-province')
            ->required();
        // city
        $validator
            ->setFields('inp-add-address-city')
            ->required();
        // postal code
        $validator
            ->setFields('inp-add-address-postal-code')
            ->required();
        // address
        $validator
            ->setFields('inp-add-address-addr')
            ->required();

        // check if user is exists
        $userId = session()->getFlash('user-address-add-id', null, false);
        if (!empty($userId)) {
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);
            if (0 === $userModel->count('id=:id', ['id' => $userId])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-address-full-name', 'شناسه کاربر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-add-address-full-name', 'شناسه کاربر نامعتبر است.');
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
                    ->setError('inp-add-address-full-name', 'تعداد آدرس‌ها به حداکثر خود رسیده است.');
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
            $userId = session()->getFlash('user-address-add-id', null);
            $name = input()->post('inp-add-address-full-name', '')->getValue();
            $mobile = input()->post('inp-add-address-mobile', '')->getValue();
            $province = input()->post('inp-add-address-province', '')->getValue();
            $city = input()->post('inp-add-address-city', '')->getValue();
            $postalCode = input()->post('inp-add-address-postal-code', '')->getValue();
            $address = input()->post('inp-add-address-addr', '')->getValue();

            $res = $addressModel->insert([
                'user_id' => $userId,
                'full_name' => $xss->xss_clean($name),
                'mobile' => $xss->xss_clean(StringUtil::toEnglish($mobile)),
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