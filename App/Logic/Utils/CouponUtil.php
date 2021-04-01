<?php

namespace App\Logic\Utils;

use App\Logic\Models\CouponModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class CouponUtil
{
    /**
     * @var CouponModel
     */
    private $coupon_model;

    /**
     * CouponUtil constructor.
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function __construct()
    {
        $this->coupon_model = container()->get(CouponModel::class);
    }

    /**
     * @param $code
     * @return float
     */
    public function getCouponPrice($code): float
    {
        return $this->coupon_model->getCouponPriceFromCode($code);
    }

    /**
     * @param $code
     * @return bool
     */
    public function couponExists($code): bool
    {
        return $this->coupon_model->count('code=:code AND publish=:pub AND start_at>=:start AND expire_at<=:expire', [
                'code' => $code,
                'pub' => DB_YES,
                'start' => time(),
                'expire' => time(),
            ]) > 0;
    }

    /**
     * @param $code
     * @return array
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public static function checkCoupon($code): array
    {
        session()->remove(SESSION_APPLIED_COUPON_CODE);
        if (empty($code)) {
            return [false, 'کوپن نامعتبر است.'];
        }

        /**
         * @var CouponModel $couponModel
         */
        $couponModel = container()->get(CouponModel::class);

        $coupon = $couponModel->getFirst([
            'price', 'min_price', 'max_price', 'use_count',
        ], 'code=:code AND publish=:pub AND start_at>=:start AND expire_at<=:expire', [
            'code' => $code,
            'pub' => DB_YES,
            'start' => time(),
            'expire' => time(),
        ]);

        if (count($coupon)) {
            $totalPrice = 0.0;
            foreach (cart()->getItems() as $item) {
                $totalPrice += (float)get_discount_price($item)[0];
            }

            $ok = false;
            $msg = 'کوپن برای اعمال، در محدوده مورد نظر نیست.';
            if (!empty($coupon['min_price']) && empty($coupon['max_price'])) {
                if ($totalPrice >= $coupon['min_price']) {
                    $ok = true;
                }
            } elseif (empty($coupon['min_price']) && !empty($coupon['max_price'])) {
                if ($totalPrice <= $coupon['max_price']) {
                    $ok = true;
                }
            } elseif (!empty($coupon['min_price']) && !empty($coupon['max_price'])) {
                if ($totalPrice >= $coupon['min_price'] && $totalPrice <= $coupon['max_price']) {
                    $ok = true;
                }
            }

            if ($ok) {
                session()->set(SESSION_APPLIED_COUPON_CODE, $code);
                $msg = 'کوپن اعمال شد.';
            }

            return [$ok, $msg];
        }
        return [false, 'کوپن نامعتبر است.'];
    }

    /**
     * @return string|null
     */
    public static function getStoredCouponCode(): ?string
    {
        return session()->get(SESSION_APPLIED_COUPON_CODE);
    }
}