<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<?php
if (count($order ?? []) && count($items ?? [])): ?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="4">
                مشخصات پرداخت
            </th>
        </tr>
        </thead>
        <tr>
            <td>
                <small>
                    تاریخ ثبت سفارش
                </small>
                <strong>
                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT_WITH_TIME, $order['ordered_at']); ?>
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
            <td>
                <small>
                    روش ارسال:
                </small>
                <strong>
                    <?= !empty($order['send_method_title']) ? $order['send_method_title'] : '-'; ?>
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
            <td>
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
            <td>
                <small>
                    مبلغ کل:
                </small>
                <strong>
                    <?= local_number(number_format(StringUtil::toEnglish($order['total_price']))); ?>
                    تومان
                </strong>
            </td>
            <td>
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
            <td>
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
            <td>
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
                    کد ملی:
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
            <th colspan="5">
                مشخصات گیرنده سفارش
            </th>
        </tr>
        </thead>
        <?php if ((int)$order['receiver_type'] === RECEIVER_TYPE_LEGAL): ?>
            <tr>
                <td>
                    <small>
                        نام شرکت:
                    </small>
                    <strong>
                        <?= $order['receiver_name']; ?>
                    </strong>
                </td>
                <td>
                    <small>
                        کد اقتصادی:
                    </small>
                    <strong>
                        <?= $order['company_economic_code']; ?>
                    </strong>
                </td>
                <td>
                    <small>
                        شناسه ملی:
                    </small>
                    <strong>
                        <?= $order['company_economic_national_id']; ?>
                    </strong>
                </td>
                <td>
                    <small>
                        شماره ثبت:
                    </small>
                    <strong>
                        <?= $order['company_registration_number']; ?>
                    </strong>
                </td>
                <td>
                    <small>
                        تلفن ثابت:
                    </small>
                    <strong>
                        <?= $order['company_tel']; ?>
                    </strong>
                </td>
            </tr>
        <?php else: ?>
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
                <td>
                    <small>
                        شهر:
                    </small>
                    <strong>
                        <?= $order['city']; ?>
                    </strong>
                </td>
                <td>
                    <small>
                        کد پستی:
                    </small>
                    <strong>
                        <?= local_number(StringUtil::toEnglish($order['postal_code'])); ?>
                    </strong>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <?php if ((int)$order['receiver_type'] === RECEIVER_TYPE_LEGAL): ?>
            <td>
                <small>
                    کد پستی:
                </small>
                <strong>
                    <?= local_number(StringUtil::toEnglish($order['postal_code'])); ?>
                </strong>
            </td>
            <td colspan="4">
                <?php else: ?>
            <td colspan="5">
                <?php endif; ?>
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
            <th colspan="10">
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
                مبلغ واحد
                <br>
                (به تومان)
            </th>
            <th>
                مبلغ کل
                <br>
                (به تومان)
            </th>
            <th>
                تخفیف
                <br>
                (به تومان)
            </th>
            <th>
                مبلغ کل پس از تخفیف
                <br>
                (به تومان)
            </th>
            <th>
                مرسوله مجزا
            </th>
            <th>
                مرجوع شده
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;
        $allDiscounts = 0;
        $totalPrice = 0;
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
                    <?php
                    $isColorShowable = !empty($item['color_name']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES);
                    ?>
                    <?php if ($isColorShowable): ?>
                        رنگ:
                        <span><?= $item['color_name']; ?></span>
                    <?php endif; ?>

                    <?php if (!empty($item['size'])): ?>
                        <?php if ($isColorShowable): ?>
                            <br>
                        <?php endif; ?>
                        <?= $item['size'] ?>
                    <?php endif; ?>

                    <?php if (!empty($item['guarantee'])): ?>
                        <?php if ($isColorShowable || !empty($item['size'])): ?>
                            <br>
                        <?php endif; ?>
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
                    <?php if (0 != $item['price']): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($item['price']))); ?>
                        تومان
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($item['price'] != $item['discounted_price']): ?>
                        <?php
                        $dis = (int)$item['price'] - (int)$item['discounted_price'];
                        $allDiscounts += $dis;
                        ?>
                        <?= local_number(number_format(StringUtil::toEnglish($dis))); ?>
                        تومان
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (0 != $item['discounted_price']): ?>
                        <?php $totalPrice += $item['discounted_price']; ?>
                        <?= local_number(number_format(StringUtil::toEnglish($item['discounted_price']))); ?>
                        تومان
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </td>
                <td <?= DB_YES == $item['separate_consignment'] ? 'class="bg-gray"' : ''; ?>>
                    <?php if (DB_YES == $item['separate_consignment']): ?>
                        بله
                    <?php else: ?>
                        خیر
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
        <tfoot>
        <tr>
            <td colspan="6">
                <strong class="text-right">
                    هزینه ارسال:
                </strong>
            </td>
            <td></td>
            <td colspan="2">
                <?php if (0 != $order['shipping_price']): ?>
                    <?php $totalPrice += (int)$order['shipping_price']; ?>
                    <?= local_number(number_format(StringUtil::toEnglish($order['shipping_price']))); ?>
                    تومان
                <?php else: ?>
                    رایگان
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <strong class="text-right">
                    تخفیف ویژه (کوپن):
                </strong>
            </td>
            <td>
                <?php if (0 != $order['coupon_price']): ?>
                    <?= local_number(number_format(StringUtil::toEnglish($order['coupon_price']))); ?>
                    تومان
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="6">
                <strong class="text-right">
                    مجموع مبالغ:
                </strong>
            </td>
            <td>
                <strong>
                    <?php if (0 != $allDiscounts): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($allDiscounts))); ?>
                        تومان
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </strong>
            </td>
            <td colspan="2">
                <strong>
                    <?php if (0 != $totalPrice): ?>
                        <?= local_number(number_format(StringUtil::toEnglish($totalPrice))); ?>
                        تومان
                    <?php else: ?>
                        رایگان
                    <?php endif; ?>
                </strong>
            </td>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>