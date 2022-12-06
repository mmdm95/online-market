<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<?php
if (count($order ?? []) && count($items ?? [])): ?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="3">
                مشخصات پرداخت
            </th>
        </tr>
        </thead>
        <tr>
            <td style="width: 50%;">
                <small>
                    تاریخ ثبت سفارش
                </small>
                <strong>
                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['ordered_at']); ?>
                </strong>
            </td>
            <td>
                <small>
                    کد فاکتور:
                </small>
                <strong>
                    <?= local_number(StringUtil::toEnglish($order['code'])); ?>
                </strong>
            </td>
            <td>
                <small>
                    نحوه پرداخت:
                </small>
                <strong>
                    <?= $order['method_title']; ?>
                </strong>
            </td>
        </tr>

        <tr>
            <td>
                <small>
                    کد رهگیری:
                </small>
                <strong>
                    <?php if (!empty($order['info']['successful'])): ?>
                        <?= $order['info']['successful']['payment_code']; ?>
                    <?php elseif (!empty($order['info']['failed'])): ?>
                        <?= $order['info']['failed']['payment_code']; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </strong>
            </td>
            <td colspan="2">
                <div class="bg-gray">
                    <small>
                        وضعیت پرداخت:
                    </small>
                    <strong>
                        <?php if (!empty($order['info']['successful'])): ?>
                            موفق
                        <?php elseif (!empty($order['info']['failed'])): ?>
                            ناموفق
                        <?php else: ?>
                            <?= PAYMENT_STATUSES[$order['payment_status']] ?: 'نامشخص'; ?>
                        <?php endif; ?>
                    </strong>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <small>
                    مبلغ کل:
                </small>
                <strong>
                    <?= local_number(number_format(StringUtil::toEnglish($order['total_price']))); ?>
                    تومان
                </strong>
            </td>
            <td colspan="2">
                <small>
                    مبلغ تخفیف:
                </small>
                <strong>
                    <?php if (0 != $order['discount_price']): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($order['discount_price']))); ?>
                        تومان
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </strong>
            </td>
        </tr>

        <tr>
            <td>
                <small>
                    عنوان کوپن تخفیف:
                </small>
                <strong>
                    <?php if (0 != $order['coupon_title']): ?>
                        <?= $order['coupon_title']; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </strong>
            </td>
            <td colspan="2">
                <small>
                    مبلغ کوپن تخفیف:
                </small>
                <strong>
                    <?php if (0 != $order['coupon_price']): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($order['coupon_price']))); ?>
                        تومان
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </strong>
            </td>
        </tr>

        <tr>
            <td>
                <small>
                    هزینه ارسال:
                </small>
                <strong>
                    <?php if (0 != $order['shipping_price']): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($order['shipping_price']))); ?>
                        تومان
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </strong>
            </td>
            <td colspan="2">
                <div class="bg-gray">
                    <small>
                        مبلغ قابل پرداخت:
                    </small>
                    <strong>
                        <?= local_number(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                        تومان
                    </strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="3">
                مشخصات ثبت کننده سفارش
            </th>
        </tr>
        </thead>
        <tr>
            <td>
                <small>
                    نام و نام خانوادگی:
                </small>
                <strong>
                    <?php if (!empty($order['first_name']) && !empty($order['last_name'])): ?>
                        <?= trim($order['first_name'] . ' ' . $order['last_name']); ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </strong>
            </td>
            <td>
                <small>
                    شماره موبایل:
                </small>
                <strong>
                    <?= local_number(StringUtil::toEnglish($order['mobile'])); ?>
                </strong>
            </td>
            <td>
                <small>
                    شماره شناسنامه:
                </small>
                <strong>
                    <?php if (!empty($order['user_national_number'])): ?>
                        <?= local_number(StringUtil::toEnglish($order['user_national_number'])); ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </strong>
            </td>
        </tr>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="3">
                مشخصات گیرنده سفارش
            </th>
        </tr>
        </thead>
        <tr>
            <td>
                <small>
                    نام گیرنده:
                </small>
                <strong>
                    <?= $order['receiver_name']; ?>
                </strong>
            </td>
            <td>
                <small>
                    شماره تماس:
                </small>
                <strong>
                    <?= local_number(StringUtil::toEnglish($order['receiver_mobile'])); ?>
                </strong>
            </td>
            <td>
                <small>
                    استان:
                </small>
                <strong>
                    <?= $order['province']; ?>
                </strong>
            </td>
        </tr>
        <tr>
            <td>
                <small>
                    شهر:
                </small>
                <strong>
                    <?= $order['city']; ?>
                </strong>
            </td>
            <td colspan="2">
                <small>
                    کد پستی:
                </small>
                <strong>
                    <?= local_number(StringUtil::toEnglish($order['postal_code'])); ?>
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <small>
                    آدرس:
                </small>
                <strong>
                    <?= $order['address']; ?>
                </strong>
            </td>
        </tr>
    </table>

    <table class='table table-bordered'>
        <thead>
        <tr>
            <th colspan="8">
                محصولات خریداری شده
            </th>
        </tr>
        </thead>
        <thead>
        <tr>
            <th>
                ردیف
            </th>
            <th>
                نام کالا
            </th>
            <th>
                مشخصات
            </th>
            <th>
                تعداد
            </th>
            <th>
                فی
            </th>
            <th>
                تخفیف
            </th>
            <th>
                قیمت نهایی
            </th>
            <th>
                مرجوع شده
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;
        ?>
        <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <?= local_number(++$i); ?>
                </td>
                <td>
                    <?= $item['product_title']; ?>
                </td>
                <td>
                    رنگ:
                    <span><?= $item['color_name']; ?></span>

                    <?php if (!empty($item['size'])): ?>
                        <br>
                        <?= $item['size'] ?>
                    <?php endif; ?>

                    <?php if (!empty($item['guarantee'])): ?>
                        <br>
                        <?= $item['guarantee'] ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?= local_number($item['product_count']); ?>
                    <?= $item['unit_title']; ?>
                </td>
                <td>
                    <?php if (0 != $item['unit_price']): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($item['unit_price']))); ?>
                        تومان
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($item['price'] != $item['discounted_price']): ?>
                        <?= local_number(number_format(StringUtil::toEnglish((int)$item['price'] - (int)$item['discounted_price']))); ?>
                        تومان
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (0 != $item['price']): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($item['price']))); ?>
                        تومان
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </td>
                <td <?= DB_YES == $item['is_returned'] ? 'class="bg-gray"' : ''; ?>>
                    <?php if (DB_YES == $item['is_returned']): ?>
                        بله
                    <?php else: ?>
                        خیر
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>