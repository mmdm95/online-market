<?php

use Sim\Utils\StringUtil;

$cart = cart();
$cart->restore(true);
$items = $cart->getItems();

$couponPrice = 0.0;

$totalPrice = 0.0;
foreach ($items as $item) {
    $totalPrice += $item['qnt'] * (float)get_discount_price($item)[0];
}
?>

<div class="table-responsive order_table">
    <table class="table">
        <thead>
        <tr>
            <th>محصول</th>
            <th>جمع</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $shippingTimes = 1;
        ?>
        <?php foreach ($items as $item): ?>
            <?php if (is_value_checked($item['separate_consignment'])): ?>
                <?= $shippingTimes++; ?>
            <?php endif; ?>

            <tr>
                <td>
                    <span class="d-inline-block"><?= $item['title']; ?></span>
                    <span class="product-qty" dir="ltr"><?= local_number($item['qnt']); ?> x</span>
                </td>
                <td>
                    <?php
                    $price = (float)get_discount_price($item)[0];
                    ?>

                    <?php if (0 != $price): ?>
                        <?= number_format(StringUtil::toEnglish($item['qnt'] * $price)); ?>
                        <small>تومان</small>
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <?php if ($shippingTimes > 1): ?>
            <tr>
                <th>تعداد مرسوله</th>
                <td>
                    <?= number_format(StringUtil::toEnglish($shippingTimes)); ?>
                    <small>عدد</small>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <th>جمع</th>
            <td class="product-subtotal">
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
                <td>کوپن اعمال شده</td>
                <td>
                    <?= number_format(StringUtil::toEnglish($couponPrice)); ?>
                    <small>تومان</small>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <th>هزینه ارسال</th>
            <td>
                <?php
                $postPrice = session()->get(SESSION_APPLIED_POST_PRICE, 0.0);
                ?>
                <?php if (!is_null($postPrice) && 0 != $postPrice): ?>
                    <?= number_format(StringUtil::toEnglish($postPrice)); ?>
                <?php else: ?>
                    <?php if (0 == $postPrice): ?>
                        رایگان
                    <?php else: ?>
                        وابسته به آدرس
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>مبلغ قابل پرداخت</th>
            <td class="product-subtotal">
                <?php
                $theTotalPrice = (float)$totalPrice + (float)$couponPrice + (float)$postPrice;
                ?>
                <?php if (0 != $theTotalPrice): ?>
                    <?= number_format(StringUtil::toEnglish($theTotalPrice)); ?>
                    <small>تومان</small>
                <?php else: ?>
                    رایگان
                <?php endif; ?>
            </td>
        </tr>
        </tfoot>
    </table>
</div>