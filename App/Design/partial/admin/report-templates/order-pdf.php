<?php

use Sim\Utils\StringUtil;

?>

<?php
if (count($order ?? []) && count($items ?? [])): ?>
    <div class='section'>
        <div class='section-header'>
            <strong>
                وضعیت سفارش
            </strong>
        </div>
        <div class='section-body section-important'>
            <strong>
                <?= $order['send_status_title'] ?: 'نامشخص'; ?>
            </strong>
        </div>
    </div>

    <div class='section'>
        <div class='section-header'>
            <strong>
                مشخصات پرداخت
            </strong>
        </div>
        <div class='section-body'>
            <div>
                <div class='section-half'>
                    <small>
                        کد فاکتور:
                    </small>
                    <strong>
                        <?= local_number(StringUtil::toEnglish($order['code'])); ?>
                    </strong>
                </div>
                <div class='section-half'>
                    <small>
                        نحوه پرداخت:
                    </small>
                    <strong>
                        <?= $order['method_title']; ?>
                    </strong>
                </div>
            </div>
            <div class='section-sep'></div>
            <div>
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
            </div>
            <div class='section-sep'></div>
            <div class='section-body bg-gray'>
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
        </div>
        <div class='section-body'>
            <div>
                <div class='section-half'>
                    <small>
                        مبلغ کل:
                    </small>
                    <strong>
                        <?= local_number(number_format(StringUtil::toEnglish($order['total_price']))); ?>
                        تومان
                    </strong>
                </div>
                <div class='section-half'>
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
                </div>
            </div>
            <div class='section-sep'></div>
            <div>
                <div class='section-half'>
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
                </div>
                <div class='section-half'>
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
                </div>
            </div>
            <div class='section-sep'></div>
            <div>
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
            </div>
            <div class='section-sep'></div>
            <div class='section-body bg-gray'>
                <small>
                    مبلغ قابل پرداخت:
                </small>
                <strong>
                    <?= local_number(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                    تومان
                </strong>
            </div>
        </div>
    </div>

    <div class='section'>
        <div class='section-header'>
            <strong>
                مشخصات ثبت کننده سفارش
            </strong>
        </div>
        <div class='section-body'>
            <div>
                <div class='section-half'>
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
                </div>
                <div class='section-half'>
                    <small>
                        شماره موبایل:
                    </small>
                    <strong>
                        <?= local_number(StringUtil::toEnglish($order['mobile'])); ?>
                    </strong>
                </div>
            </div>
        </div>
    </div>

    <div class='section'>
        <div class='section-header'>
            <strong>
                مشخصات گیرنده سفارش
            </strong>
        </div>
        <div class='section-body'>
            <div>
                <small>
                    نام گیرنده:
                </small>
                <strong>
                    <?= $order['receiver_name']; ?>
                </strong>
            </div>
            <div class='section-sep'></div>
            <div>
                <div class='section-half'>
                    <small>
                        شماره تماس:
                    </small>
                    <strong>
                        <?= local_number(StringUtil::toEnglish($order['receiver_mobile'])); ?>
                    </strong>
                </div>
                <div class='section-half'>
                    <small>
                        استان:
                    </small>
                    <strong>
                        <?= $order['province']; ?>
                    </strong>
                </div>
            </div>
            <div class='section-sep'></div>
            <div>
                <div class='section-half'>
                    <small>
                        شهر:
                    </small>
                    <strong>
                        <?= $order['city']; ?>
                    </strong>
                </div>
                <div class='section-half'>
                    <small>
                        کد پستی:
                    </small>
                    <strong>
                        <?= local_number(StringUtil::toEnglish($order['postal_code'])); ?>
                    </strong>
                </div>
            </div>
            <div class='section-sep'></div>
            <div>
                <small>
                    آدرس:
                </small>
                <strong>
                    <?= $order['address']; ?>
                </strong>
            </div>
        </div>
    </div>

    <br>
    <br>

    <div class='section'>
        <div class='section-header'>
            <strong>
                محصولات خریداری شده
            </strong>
        </div>
        <div class='section-body'>
            <table class='table'>
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
        </div>
    </div>
<?php endif; ?>