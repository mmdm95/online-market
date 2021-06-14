<?php

use App\Logic\Utils\CouponUtil;
use Sim\Utils\StringUtil;

$cart = cart();
/**
 * @var CouponUtil $couponUtil
 */
$couponUtil = container()->get(CouponUtil::class);
$couponCode = session()->get(SESSION_APPLIED_COUPON_CODE);
$couponPrice = 0.0;

$totalPrice = 0.0;
foreach ($cart->getItems() as $item) {
    $totalPrice += $item['qnt'] * (float)get_discount_price($item)[0];
}
?>

<div class="table-responsive">
    <table class="table">
        <tbody>
        <tr>
            <td class="cart_total_label">جمع سبد خرید</td>
            <td class="cart_total_amount">
                <?php if (0 != $totalPrice): ?>
                    <?= number_format(StringUtil::toEnglish($totalPrice)); ?>
                    <small>تومان</small>
                <?php else: ?>
                    رایگان
                <?php endif; ?>
            </td>
        </tr>

        <?php if (!is_null($couponCode) && $couponUtil->couponExists($couponCode)): ?>
            <?php
            $couponPrice = $couponUtil->getCouponPrice($couponCode);
            ?>
            <tr>
                <td class="cart_total_label">کوپن اعمال شده</td>
                <td class="cart_total_amount">
                    <?= number_format(StringUtil::toEnglish($couponPrice)); ?>
                    <small>تومان</small>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td class="cart_total_label">هزینه ارسال</td>
            <td class="cart_total_amount">
                وابسته به آدرس
            </td>
        </tr>
        <tr>
            <td class="cart_total_label">جمع</td>
            <td class="cart_total_amount">
                <strong>
                    <?php
                    $theTotalPrice = (float)$totalPrice + (float)$couponPrice;
                    ?>
                    <?php if (0 != $theTotalPrice): ?>
                        <?= number_format(StringUtil::toEnglish($theTotalPrice)); ?>
                        <small>تومان</small>
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </strong>
            </td>
        </tr>
        </tbody>
    </table>
</div>