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
            'inp-edit-address-addr' => 'آدرس',
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
            ->required();
        // city
        $validator
            ->setFields('inp-edit-address-city')
            ->required();
        // postal code
        $validator
            ->setFields('inp-edit-address-postal-code')
            ->required();
        // address
        $validator
            ->setFields('inp-edit-address-addr')
            ->required();

        // check if address is exists
        $id = session()->getFlash('user-address-edit-id', null, false);
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

        // check if user is exists
        $userId = session()->getFlash('addr-edit-user-id', null, false);
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
            $id = session()->getFlash('user-address-edit-id', null);
            $name = input()->post('inp-edit-address-full-name', '')->getValue();
            $mobile = input()->post('inp-edit-address-mobile', '')->getValue();
            $province = input()->post('inp-edit-address-province', '')->getValue();
            $city = input()->post('inp-edit-address-city', '')->getValue();
            $postalCode = input()->post('inp-edit-address-postal-code', '')->getValue();
            $address = input()->post('inp-edit-address-addr', '')->getValue();

            $res = $addressModel->update([
                'user_id' => $userId,
                'full_name' => $xss->xss_clean($name),
                'mobile' => $xss->xss_clean(StringUtil::toEnglish($mobile)),
                'address' => $xss->xss_clean($address),
                'city_id' => $xss->xss_clean($city),
                'province_id' => $xss->xss_clean($province),
                'postal_code' => $xss->xss_clean($postalCode),
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}