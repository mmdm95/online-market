<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<?php if (
    in_array($order['payment_status'], [PAYMENT_STATUS_WAIT, PAYMENT_STATUS_NOT_PAYED]) &&
    isset($reserved_item['expire_at']) && $reserved_item['expire_at'] >= time()
): ?>
    <!-- Repay ability -->
    <div class="dashboard_content alert alert-warning">
        <div class="m-0 row flex-column-reverse flex-sm-row">
            <a href="<?= url('user.order.re-pay', ['id' => $order['id']])->getRelativeUrlTrimmed(); ?>"
               class="btn btn-sm bg-warning text-white mt-4 mt-sm-0">
                هم‌اکنون پرداخت شود
                <i class="linearicons-chevron-left icon-half-x ml-2"></i>
            </a>
            <div class="col row no-gutters align-content-center text-center" countdown
                 data-date="<?= date('Y-m-d H:i:s', $reserved_item['expire_at']); ?>">
                <div class="col">
                    <span class="d-inline-block" data-hours>0</span>
                    <small class="text-danger d-inline-block">ساعت</small>
                </div>
                <div class="col">
                    <span class="d-inline-block" data-minutes>0</span>
                    <small class="text-danger d-inline-block">دقیقه</small>
                </div>
                <div class="col">
                    <span class="d-inline-block" data-seconds>0</span>
                    <small class="text-danger d-inline-block">ثانیه</small>
                </div>
            </div>
        </div>
    </div>
    <!-- /repay ability -->
<?php endif; ?>

<!-- Order factor download -->
<div class="dashboard_content text-right">
    <button type="button" id="excelPdfOrder"
            class="btn btn-sm bg-info text-white"
            data-export-code="<?= $order['code']; ?>">
        دانلود فاکتور
        <i class="ti-file icon-half-x ml-2"></i>
    </button>
</div>
<!-- /order factor download -->

<!-- Receiver info -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>اطلاعات گیرنده</h3>
        </div>
        <div class="row m-0">
            <div class="col-lg-7 border border-light px-3 py-2 text-center">
                <label class="m-0">
                    <?= $order['receiver_name']; ?>
                </label>
            </div>

            <?php if ((int)$order['receiver_type'] === RECEIVER_TYPE_LEGAL): ?>
                <div class="col-lg-5 border border-light px-3 py-2">
                    <small class="mb-1">
                        تلفن ثابت:
                    </small>
                    <label class="mb-1">
                        <?= StringUtil::toPersian(trim($order['company_tel'])) ?? '-'; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        کد اقتصادی:
                    </small>
                    <label class="mb-1">
                        <?= StringUtil::toPersian(trim($order['company_economic_code'])) ?? '-'; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        شناسه ملی:
                    </small>
                    <label class="mb-1">
                        <?= StringUtil::toPersian(trim($order['company_economic_national_id'])) ?? '-'; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        شماره ثبت:
                    </small>
                    <label class="mb-1">
                        <?= StringUtil::toPersian(trim($order['company_registration_number'])) ?? '-'; ?>
                    </label>
                </div>
            <?php else: ?>
                <div class="col-lg-5 border border-light px-3 py-2">
                    <small class="mb-1">
                        کد ملی:
                    </small>
                    <label class="mb-1">
                        <?= StringUtil::toPersian(trim($order['user_national_number'])) ?? '-'; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        شماره تماس:
                    </small>
                    <label class="mb-1">
                        <?= StringUtil::toPersian(trim($order['receiver_mobile'])); ?>
                    </label>
                </div>
            <?php endif; ?>

            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    استان:
                </small>
                <label class="mb-1">
                    <?= $order['province']; ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    شهر:
                </small>
                <label class="mb-1">
                    <?= $order['city']; ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    کد پستی:
                </small>
                <label class="mb-1">
                    <?= $order['postal_code']; ?>
                </label>
            </div>
            <div class="col-lg-12 border border-light px-3 py-2 text-center">
                <label class="m-0">
                    <?= $order['address']; ?>
                </label>
            </div>
        </div>
    </div>
</div>
<!-- /receiver info -->

<!-- Order info -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>جزئیات سفارش</h3>
        </div>
        <div class="row m-0">
            <div class="col-lg-12 text-center">
                <small class="mb-1">
                    شماره فاکتور:
                </small>
                <label class="mb-1 en-font">
                    <?= $order['code']; ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    مبلغ قابل پرداخت:
                </small>
                <label class="mb-1 text-success">
                    <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                    <small>تومان</small>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    طریقه ارسال:
                </small>
                <label class="mb-1">
                    <?= $order['send_method_title']; ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    هزینه ارسال:
                </small>
                <label class="mb-1">
                    <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['shipping_price']))); ?>
                    <small>تومان</small>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    عنوان کوپن:
                </small>
                <label class="mb-1">
                    <?php if (!empty($order['coupon_title'])): ?>
                        <?= $order['coupon_title']; ?>
                    <?php else: ?>
                        <?php load_partial('main/parser/dash-icon'); ?>
                    <?php endif; ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    مبلغ کوپن:
                </small>
                <label class="mb-1">
                    <?php if (!empty($order['coupon_price'])): ?>
                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['coupon_price']))); ?>
                        <small>تومان</small>
                    <?php else: ?>
                        <?php load_partial('main/parser/dash-icon'); ?>
                    <?php endif; ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    تاریخ ثبت سفارش:
                </small>
                <label class="mb-1">
                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['ordered_at']); ?>
                </label>
            </div>
            <div class="col-lg-12 border border-light px-3 py-2">
                <small class="d-block text-center mb-1">
                    وضعیت سفارش
                </small>
                <label class="d-block text-center p-2 rounded"
                       style="background-color: <?= $order['send_status_color']; ?>; color: <?= get_color_from_bg($order['send_status_color']); ?>;">
                    <?= $order['send_status_title']; ?>
                </label>
            </div>
        </div>
    </div>
</div>
<!-- /order info -->

<!-- Payment info -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>وضعیت مالی</h3>
        </div>
        <div class="row m-0">
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    وضعیت پرداخت:
                </small>
                <label class="mb-1">
                    <?php load_partial('admin/parser/payment-status', [
                        'status' => $order['payment_status'],
                        'extra_padding' => true,
                    ]); ?>
                </label>
            </div>

            <?php if (!empty($order['receipt_code'])): ?>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        کد رسید:
                    </small>
                    <label class="mb-1">
                        <?= $order['receipt_code']; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        تاریخ رسید:
                    </small>
                    <label class="mb-1">
                        <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['receipt_date']); ?>
                    </label>
                </div>
            <?php else: ?>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        کد رهگیری:
                    </small>
                    <label class="mb-1 text-danger">
                        <?php if (!empty($order['payment_code'])): ?>
                            <?= $order['payment_code']; ?>
                        <?php else: ?>
                            <?php load_partial('main/parser/dash-icon'); ?>
                        <?php endif; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        تاریخ پرداخت:
                    </small>
                    <label class="mb-1">
                        <?php if (!empty($order['payed_at'])): ?>
                            <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['payed_at']); ?>
                        <?php else: ?>
                            <?php load_partial('main/parser/dash-icon'); ?>
                        <?php endif; ?>
                    </label>
                </div>
            <?php endif; ?>

            <div class="col-lg-12 border border-light px-3 py-2 text-center"
                 data-toggle="collapse" data-target="#paymentHistory">
                <button class="btn p-0">
                    تاریخچه تراکنش
                    <i class="linearicons-chevron-down ml-2 mr-0" aria-hidden="true"></i>
                </button>
            </div>

            <!-- All payments -->
            <div id="paymentHistory" class="col-12 p-0 collapse bg-light">
                <div class="table-responsive pt-3">
                    <table class="table mb-3">
                        <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>توضیحات</th>
                            <th>طریقه پرداخت</th>
                            <th>وضعیت</th>
                            <th>شماره پیگیری</th>
                            <th>مبلغ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($payment_success['payment_date'])): ?>
                            <tr>
                                <td>
                                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $payment_success['payment_date']); ?>
                                </td>
                                <td>
                                    <?= $payment_success['msg']; ?>
                                </td>
                                <td>
                                    <?php load_partial('admin/parser/payment-method-type', ['type' => $payment_success['method_type']]); ?>
                                </td>
                                <td>
                                    <?php if (DB_YES == $payment_success['is_success']): ?>
                                        <i class="linearicons-checkmark-circle text-success icon-2x"
                                           aria-hidden="true"></i>
                                    <?php else: ?>
                                        <i class="linearicons-cross-circle text-danger icon-2x"
                                           aria-hidden="true"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $payment_success['payment_code']; ?>
                                </td>
                                <td>
                                    <?php if (!empty($payment_success['price'])): ?>
                                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($payment_success['price']))); ?>
                                        <small>تومان</small>
                                    <?php else: ?>
                                        <?php load_partial('main/parser/dash-icon'); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($payment_info['wallet_flow'] as $wallet): ?>
                            <tr>
                                <td>
                                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $wallet['deposit_at']); ?>
                                </td>
                                <td>
                                    <?= $wallet['deposit_type_title']; ?>
                                </td>
                                <td>
                                    <?php load_partial('admin/parser/payment-method-type', ['type' => METHOD_TYPE_WALLET]); ?>
                                </td>
                                <td>
                                    <i class="linearicons-checkmark-circle text-success icon-2x" aria-hidden="true"></i>
                                </td>
                                <td>
                                    <?php load_partial('main/parser/dash-icon'); ?>
                                </td>
                                <td>
                                    <?php if (!empty($wallet['deposit_price'])): ?>
                                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish(abs($wallet['deposit_price'])))); ?>
                                        <small>تومان</small>
                                    <?php else: ?>
                                        <?php load_partial('main/parser/dash-icon'); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($payment_info['gateway_flow'] as $payment): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($payment['payment_date'])): ?>
                                        <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $payment['payment_date']); ?>
                                    <?php else: ?>
                                        <?php load_partial('main/parser/dash-icon'); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $payment['msg']; ?>
                                </td>
                                <td>
                                    <?php load_partial('admin/parser/payment-method-type', ['type' => $payment['method_type']]); ?>
                                </td>
                                <td>
                                    <?php if (DB_YES == $payment['is_success']): ?>
                                        <i class="linearicons-checkmark-circle text-success icon-2x"
                                           aria-hidden="true"></i>
                                    <?php else: ?>
                                        <i class="linearicons-cross-circle text-danger icon-2x"
                                           aria-hidden="true"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($payment['payment_code'])): ?>
                                        <?= $payment['payment_code']; ?>
                                    <?php else: ?>
                                        <?php load_partial('main/parser/dash-icon'); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($payment['price'])): ?>
                                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($payment['price']))); ?>
                                        <small>تومان</small>
                                    <?php else: ?>
                                        <?php load_partial('main/parser/dash-icon'); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /all payments -->
        </div>
    </div>
</div>
<!-- /payment info -->

<!-- Order items -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>آیتم‌های سفارش</h3>
        </div>
        <?php if (count($order_items)): ?>
            <?php $k = 0; ?>
            <?php foreach ($order_items as $item): ?>
                <div class="card-body position-relative <?= 0 != $k++ ? 'border-top' : ''; ?>">
                    <?php if (!empty($item['main_product_code'])): ?>
                        <div class="order-detail-order-items-more">
                            <button class="btn pl-3 pb-3 pr-0 pt-0 dropdown-toggle no-icon" data-toggle="dropdown">
                                <i class="ti-more-alt fa-rotate-90 m-0" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu px-3">
                                <div>
                                    <?php if (DB_YES == $item['allow_commenting']): ?>
                                        <a href="<?= url('user.comment.decider', ['id' => $item['product_id']])->getRelativeUrlTrimmed(); ?>"
                                           class="d-block p-2">
                                            ثبت نظر
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    <a href="javascript:void(0);"
                                       class="d-block p-2 __add_to_cart_btn"
                                       data-cart-item-code="<?= $item['main_product_code']; ?>">
                                        خرید مجدد کالا
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="d-block d-lg-flex m-0 align-items-start">
                        <?php if (!empty($item['product_image'])): ?>
                            <div>
                                <a href="<?= url('home.product.show', [
                                    'id' => $item['product_id'],
                                    'slug' => $item['product_slug'],
                                ]); ?>">
                                    <img src=""
                                         data-src="<?= url('image.show', ['filename' => $item['product_image']]); ?>"
                                         alt="<?= $item['product_title']; ?>"
                                         class="mx-0 mx-lg-3 lazy"
                                         width="160px" height="auto">
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="col">
                            <div>
                                <div class="d-flex justify-content-between pr-4">
                                    <?php if (!empty($item['product_image'])): ?>
                                        <a href="<?= url('home.product.show', [
                                            'id' => $item['product_id'],
                                            'slug' => $item['product_slug'],
                                        ]); ?>">
                                            <h6>
                                                <?= $item['product_title']; ?>
                                            </h6>
                                        </a>
                                    <?php else: ?>
                                        <h6>
                                            <?= $item['product_title']; ?>
                                        </h6>
                                    <?php endif; ?>

                                    <?php if ($item['separate_consignment'] == DB_YES): ?>
                                        <div>
                                            <span class="badge badge-info px-2 py-1 mx-1">مرسوله مجزا</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($item['is_returned'] == DB_YES): ?>
                                        <div>
                                            <span class="badge badge-danger px-2 py-1 mx-1">مرجوع شده</span>
                                        </div>
                                    <?php elseif (!empty($item['order_item_id'])): ?>
                                        <div>
                                            <span class="badge badge-warning px-2 py-1">درخواست مرجوع</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($item['color']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES)): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="product_color_badge">
                                            <?php if ($item['is_patterned_color'] == DB_NO): ?>
                                                <span class="mr-2"
                                                      style="background-color: <?= $item['color']; ?>;"></span>
                                            <?php endif; ?>
                                            <div class="d-inline-block"><?= $item['color_name']; ?></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['size'])): ?>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="icon-size-fullscreen mr-2 ml-1" aria-hidden="true"></i>
                                        <?= $item['size']; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['guarantee'])): ?>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="linearicons-shield-check mr-2 ml-1" aria-hidden="true"></i>
                                        <?= $item['guarantee']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <ul class="list-inline list-inline-dotted my-3">
                                <li class="list-inline-item my-1">
                                    <small>قیمت واحد:</small>
                                    <label class="m-0">
                                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['unit_price']))); ?>
                                        <small>تومان</small>
                                    </label>
                                </li>
                                <li class="list-inline-item my-1">
                                    <small>تعداد:</small>
                                    <label class="m-0">
                                        <?= local_number($item['product_count']); ?>
                                        <?= $item['unit_title']; ?>
                                    </label>
                                </li>
                                <?php
                                $discount = ((float)$item['price'] - (float)$item['discounted_price']);
                                ?>
                                <?php if (!empty($discount)): ?>
                                    <li class="list-inline-item my-1">
                                        <small>تخفیف:</small>
                                        <label class="m-0">
                                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($discount))); ?>
                                            <small>تومان</small>
                                        </label>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php load_partial('main/not-found-rows', ['show_border' => false]); ?>
        <?php endif; ?>
    </div>
</div>
<!-- /order items -->
