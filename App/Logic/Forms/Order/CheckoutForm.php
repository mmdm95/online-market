<?php

namespace App\Logic\Forms\Order;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CityModel;
use App\Logic\Models\CouponModel;
use App\Logic\Models\Model;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\OrderUtil;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class CheckoutForm implements IPageForm
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
            'inp-is-real-or-legal' => 'نوع گیرنده',
            //
            'fname' => 'نام',
            'lname' => 'نام خانوادگی',
            'natnum' => 'کد ملی',
            //
            'inp-addr-full-name' => 'نام خریدار',
            'inp-addr-mobile' => 'شماره موبایل خریدار',
            'inp-addr-province' => 'شهر',
            'inp-addr-city' => 'استان',
            'inp-addr-postal-code' => 'کد پستی',
            'inp-addr-address' => 'آدرس',
            //
            'inp-addr-company-name' => 'نام شرکت',
            'inp-addr-company-eco-code' => 'کد اقتصادی',
            'inp-addr-company-eco-nid' => 'شناسه ملی',
            'inp-addr-company-reg-num' => 'شماره ثبت',
            'inp-addr-tel' => 'تلفن ثابت',
            'inp-addr-company-province' => 'شهر',
            'inp-addr-company-city' => 'استان',
            'inp-addr-company-postal-code' => 'کد پستی',
            'inp-addr-company-address' => 'آدرس',
        ])
            ->toEnglishValue(true, true);
        // all required fields
        $validator
            ->setFields([
                'fname',
                'lname',
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true);

        // get type of receiver/buyer
        $realOrLegal = $validator->getFieldValue('inp-is-real-or-legal', RECEIVER_TYPE_REAL);
        $realOrLegal = (int)$realOrLegal;

        // check validation for real person type
        if ($realOrLegal === RECEIVER_TYPE_REAL) {
            // all required fields
            $validator
                ->setFields([
                    'inp-addr-full-name',
                    'inp-addr-address',
                ])
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true);
            // province
            $validator
                ->setFields('inp-addr-province')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->custom(function (FormValue $formValue) {
                    /**
                     * @var ProvinceModel $provinceModel
                     */
                    $provinceModel = container()->get(ProvinceModel::class);
                    $province = $provinceModel->getFirst(['name'], 'id=:id AND is_deleted=:del', [
                        'id' => $formValue->getValue(),
                        'del' => DB_NO,
                    ]);
                    if (0 !== count($province)) {
                        session()->setFlash('__custom_province_info_in_order', $province['name']);
                        return true;
                    }
                    return false;
                }, '{alias} ' . 'انتخاب شده نامعتبر است.');
            if (empty($validator->getError('inp-addr-province'))) {
                // city
                $validator
                    ->setFields('inp-addr-city')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true)
                    ->custom(function (FormValue $formValue) use ($validator) {
                        /**
                         * @var CityModel $cityModel
                         */
                        $cityModel = container()->get(CityModel::class);
                        $city = $cityModel->getFirst(['name'], 'id=:id AND province_id=:pid AND is_deleted=:del', [
                            'id' => $formValue->getValue(),
                            'pid' => $validator->getFieldValue('inp-addr-province', -1),
                            'del' => DB_NO,
                        ]);
                        if (0 !== count($city)) {
                            session()->setFlash('__custom_city_info_in_order', $city['name']);
                            return true;
                        }
                        return false;
                    }, '{alias} ' . 'انتخاب شده نامعتبر است.');
            }
            // mobile
            $validator
                ->setFields('inp-addr-mobile')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->persianMobile('{alias} ' . 'نامعتبر است.');
            // postal code
            $validator
                ->setFields('inp-addr-postal-code')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true);
        } elseif ($realOrLegal === RECEIVER_TYPE_LEGAL) { // check validation for legal/company type
            // all required fields
            $validator
                ->setFields([
                    'inp-addr-company-name',
                    'inp-addr-company-eco-code',
                    'inp-addr-company-eco-nid',
                    'inp-addr-company-reg-num',
                    'inp-addr-company-address',
                ])
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true);
            // tel
            $validator
                ->setFields('inp-addr-tel')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->regex('/^\d{11}$/', '{alias} ' . 'نامعتبر است.');
            // province
            $validator
                ->setFields('inp-addr-company-province')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->custom(function (FormValue $formValue) {
                    /**
                     * @var ProvinceModel $provinceModel
                     */
                    $provinceModel = container()->get(ProvinceModel::class);
                    $province = $provinceModel->getFirst(['name'], 'id=:id AND is_deleted=:del', [
                        'id' => $formValue->getValue(),
                        'del' => DB_NO,
                    ]);
                    if (0 !== count($province)) {
                        session()->setFlash('__custom_province_info_in_order', $province['name']);
                        return true;
                    }
                    return false;
                }, '{alias} ' . 'انتخاب شده نامعتبر است.');
            if (empty($validator->getError('inp-addr-company-province'))) {
                // city
                $validator
                    ->setFields('inp-addr-company-city')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true)
                    ->custom(function (FormValue $formValue) use ($validator) {
                        /**
                         * @var CityModel $cityModel
                         */
                        $cityModel = container()->get(CityModel::class);
                        $city = $cityModel->getFirst(['name'], 'id=:id AND province_id=:pid AND is_deleted=:del', [
                            'id' => $formValue->getValue(),
                            'pid' => $validator->getFieldValue('inp-addr-company-province', -1),
                            'del' => DB_NO,
                        ]);
                        if (0 !== count($city)) {
                            session()->setFlash('__custom_city_info_in_order', $city['name']);
                            return true;
                        }
                        return false;
                    }, '{alias} ' . 'انتخاب شده نامعتبر است.');
            }
            // postal code
            $validator
                ->setFields('inp-addr-company-postal-code')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true);
        } else {
            $validator->setError('inp-is-real-or-legal', 'نوع گیرنده/خریدار را مشخص نمایید.');
        }

        if ($validator->getStatus()) {
            // national number
            $auth = auth_home();
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);
            $user = $userModel->getFirst(['national_number'], 'id=:id', ['id' => $auth->getCurrentUser()['id'] ?? 0]);
            if (empty(trim($user['national_number']))) {
                $validator
                    ->setFields('natnum')
                    ->stopValidationAfterFirstError(false)
                    ->required()
                    ->stopValidationAfterFirstError(true)
                    ->persianNationalCode('{alias} ' . 'نامعتبر است.');
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
     * @throws \Sim\Cart\Interfaces\IDBException
     */
    public function store(): bool
    {
        $res = false;
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var Model $model
         */
        $model = container()->get(Model::class);
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var CouponModel $couponModel
         */
        $couponModel = container()->get(CouponModel::class);
        /**
         * @var OrderBadgeModel $badgeModel
         */
        $badgeModel = container()->get(OrderBadgeModel::class);

        try {
            // if user is logged in, fetch his info
            if ($auth->isLoggedIn()) {
                $code = OrderUtil::getUniqueOrderCode();

                $isRealOrLegal = input()->post('inp-is-real-or-legal', null)->getValue();
                $isRealOrLegal = (int)$isRealOrLegal;

                if (!in_array($isRealOrLegal, RECEIVER_TYPES)) return false;

                $firstName = input()->post('fname', '')->getValue();
                $lastName = input()->post('lname', '')->getValue();

                if ($isRealOrLegal === RECEIVER_TYPE_LEGAL) {
                    $postalCode = input()->post('inp-addr-company-postal-code', '')->getValue();
                    $addr = input()->post('inp-addr-company-address', '')->getValue();
                } else {
                    $postalCode = input()->post('inp-addr-postal-code', '')->getValue();
                    $addr = input()->post('inp-addr-address', '')->getValue();
                }

                $province = session()->getFlash('__custom_province_info_in_order', 'نامشخص');
                $city = session()->getFlash('__custom_city_info_in_order', 'نامشخص');

                $gateway = session()->getFlash(SESSION_GATEWAY_RECORD);
                $sendMethod = session()->getFlash(SESSION_SEND_METHOD_RECORD);

                $badge = $badgeModel->getFirst([
                    'code', 'title', 'color'
                ], 'is_default_badge=:idb AND is_deleted=:del', [
                    'idb' => DB_YES,
                    'del' => DB_NO,
                ]);

                $userId = $auth->getCurrentUser()['id'] ?? 0;
                $user = $userModel->getFirst(['*'], 'id=:id', ['id' => $userId]);

                // update user's national code field
                $natnum = input()->post('natnum', '')->getValue();
                $uRes = true;
                if (empty(trim($user['national_number'])) && !empty($natnum)) {
                    $uRes = $userModel->update([
                        'national_number' => $xss->xss_clean(trim($natnum)),
                    ], 'id=:id', ['id' => $userId]);
                }
                if (empty(trim($user['national_number'])) && empty($natnum)) $uRes = false;
                if (!$uRes) return false;
                //-----
                if (!count($badge)) return false;
                //-----
                $totalPrice = 0.0;
                $totalDiscountedPrice = 0.0;
                $discountPrice = 0.0;
                cart()->restore(true);
                $items = cart()->getItems();
                foreach ($items as $item) {
                    $x = isset($item['stepped_price']) ? (float)$item['stepped_price'] : (float)$item['price'];
                    $y = (float)get_discount_price($item)[0];

                    $discountPrice += $item['qnt'] * abs($x - $y);

                    $totalPrice += $item['qnt'] * $x;
                    $totalDiscountedPrice += $item['qnt'] * $y;
                }

                // calculate send price (get from session)
                $shipping = session()->get(SESSION_APPLIED_POST_PRICE);
                if (null !== $shipping) {
                    $totalPrice += (float)$shipping;
                    $totalDiscountedPrice += (float)$shipping;
                }

                $isInPlaceDelivery = session()->get(SESSION_APPLIED_IN_PlACE_DELIVERY);

                $orderArr = [
                    'code' => $code,
                    'user_id' => $user['id'],
                    'user_national_number' => $user['national_number'],
                    'first_name' => $xss->xss_clean($firstName),
                    'last_name' => $xss->xss_clean($lastName),
                    'mobile' => $xss->xss_clean($user['username']),
                    'city' => $xss->xss_clean($city),
                    'province' => $xss->xss_clean($province),
                    'address' => $xss->xss_clean($addr),
                    'postal_code' => $xss->xss_clean($postalCode),
                    'method_code' => $gateway['code'] ?? 'نامشخص',
                    'method_title' => $gateway['title'] ?? 'نامشخص',
                    'method_type' => $gateway['method_type'] ?? 'نامشخص',
                    'send_method_title' => $sendMethod['title'],
                    'payment_status' => PAYMENT_STATUS_WAIT,
                ];

                // check for company or a real user difference to add info accordingly
                if ($isRealOrLegal === RECEIVER_TYPE_LEGAL) { // is legal company
                    $receiver = input()->post('inp-addr-company-name', '')->getValue();
                    $ecoCode = input()->post('inp-addr-company-eco-code', '')->getValue();
                    $ecoNID = input()->post('inp-addr-company-eco-nid', '')->getValue();
                    $regNum = input()->post('inp-addr-company-reg-num', '')->getValue();
                    $tel = input()->post('inp-addr-tel', '')->getValue();

                    $orderArr['receiver_type'] = RECEIVER_TYPE_LEGAL;
                    $orderArr['company_economic_code'] = $xss->xss_clean($ecoCode);
                    $orderArr['company_economic_national_id'] = $xss->xss_clean($ecoNID);
                    $orderArr['company_registration_number'] = $xss->xss_clean($regNum);
                    $orderArr['company_tel'] = $xss->xss_clean($tel);
                } else { // otherwise
                    $orderArr['receiver_type'] = RECEIVER_TYPE_REAL;
                    $receiver = input()->post('inp-addr-full-name', '')->getValue();
                    $mobile = input()->post('inp-addr-mobile', '')->getValue();

                    $orderArr['receiver_mobile'] = $xss->xss_clean($mobile);
                }

                $orderArr['receiver_name'] = $xss->xss_clean($receiver);

                $couponRes = true;
                $coupon = $couponModel->getFirst([
                    'id', 'code', 'title', 'price',
                ], 'code=:code AND publish=:pub AND start_at<=:start AND expire_at>=:expire', [
                    'code' => session()->get(SESSION_APPLIED_COUPON_CODE, ''),
                    'pub' => DB_YES,
                    'start' => time(),
                    'expire' => time(),
                ]);
                if (count($coupon)) {
                    $totalPrice -= $coupon['price'];
                    $totalDiscountedPrice -= $coupon['price'];
                    $discountPrice += $coupon['price'];

                    $orderArr = array_merge($orderArr, [
                        'coupon_id' => $coupon['id'],
                        'coupon_code' => $coupon['code'],
                        'coupon_title' => $coupon['title'],
                        'coupon_price' => $coupon['price'],
                    ]);

                    // make this coupon a used one
                    $couponUpdate = $model->update();
                    $couponUpdate
                        ->table(BaseModel::TBL_COUPONS)
                        ->set('use_count', 'use_count-1')
                        ->where('id=:id AND code=:code')
                        ->bindValues([
                            'id' => $coupon['id'],
                            'code' => $coupon['code'],
                        ]);
                    $couponRes = $model->execute($couponUpdate);
                }
                // to prevent go further because DB operation failed!
                if (!$couponRes) return false;

                $orderArr = array_merge($orderArr, [
                    'total_price' => $totalPrice,
                    'discount_price' => $discountPrice,
                    'shipping_price' => $shipping ?: 0,
                    'final_price' => $totalDiscountedPrice,
                    'send_status_code' => $badge['code'],
                    'send_status_title' => $badge['title'],
                    'send_status_color' => $badge['color'],
                    'ordered_at' => time(),
                    'is_in_place_delivery' => is_value_checked($isInPlaceDelivery) ? DB_YES : DB_NO,
                    'invoice_status_changed_at' => time(),
                    'send_status_changed_at' => time(),
                ]);

                // remove any order array from session
                session()->remove(SESSION_ORDER_ARR_INFO);

                // store order array in flash session for gateway usage
                session()->set(SESSION_ORDER_ARR_INFO, $orderArr);

                // insert to database
                $res = $orderModel->issueFullFactor($orderArr, cart()->restore(true)->getItems());
            }
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }
}
