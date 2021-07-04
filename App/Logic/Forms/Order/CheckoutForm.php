<?php

namespace App\Logic\Forms\Checkout;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CityModel;
use App\Logic\Models\CouponModel;
use App\Logic\Models\Model;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ProvinceModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
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
            'fname' => 'نام',
            'lname' => 'نام خانوادگی',
            'inp-addr-full-name' => 'نام خریدار',
            'inp-addr-mobile' => 'شماره موبایل خریدار',
            'inp-addr-province' => 'شهر',
            'inp-addr-city' => 'استان',
            'inp-addr-postal-code' => 'کد پستی',
            'inp-addr-address' => 'آدرس',
        ])
            ->toEnglishValue(true, true);
        // all required fields
        $validator
            ->setFields([
                'fname',
                'lname',
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
                    session()->setFlash('__custom_province_info_in_order', $province);
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
                        session()->setFlash('__custom_city_info_in_order', $city);
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
         * @var OrderBadgeModel $badgeMode
         */
        $badgeMode = container()->get(OrderBadgeModel::class);

        try {
            // if user is logged in, fetch his info
            if ($auth->isLoggedIn()) {
                $code = StringUtil::uniqidReal(20);
                $firstName = input()->post('fname', '')->getValue();
                $lastName = input()->post('lname', '')->getValue();
                $receiver = input()->post('inp-addr-full-name', '')->getValue();
                $mobile = input()->post('inp-addr-mobile', '')->getValue();
                $postalCode = input()->post('inp-addr-postal-code', '')->getValue();
                $addr = input()->post('inp-addr-address', '')->getValue();

                $province = session()->getFlash('__custom_province_info_in_order', 'نامشخص');
                $city = session()->getFlash('__custom_city_info_in_order', 'نامشخص');

                $gateway = session()->getFlash(SESSION_GATEWAY_RECORD);

                $badge = $badgeMode->getFirst([
                    'code', 'title', 'color'
                ], 'is_default_badge=:idb AND is_deleted:del', [
                    'idb' => DB_YES,
                    'del' => DB_NO,
                ]);

                $userId = $auth->getCurrentUser()['id'] ?? 0;
                $user = $userModel->getFirst(['*'], 'id=:id', ['id' => $userId]);
                //-----
                if (!count($badge)) return false;
                //-----
                $totalPrice = 0.0;
                $totalDiscountedPrice = 0.0;
                $discountPrice = 0.0;
                cart()->restore(true);
                $items = cart()->getItems();
                foreach ($items as $item) {
                    $x = (float)$item['price'];
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

                $orderArr = [
                    'code' => $code,
                    'user_id' => $user['id'],
                    'receiver_name' => $xss->xss_clean($receiver),
                    'receiver_mobile' => $xss->xss_clean($mobile),
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
                    'send_method_title' => 'پست',
                    'payment_status' => PAYMENT_STATUS_WAIT,
                ];

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
                    'invoice_status_changed_at' => time(),
                    'send_status_changed_at' => time(),
                ]);

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
