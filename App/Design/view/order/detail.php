<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- Content area -->
<div class="content">

    <div class="row">
        <div class="col-lg-6">
            <form action="<?= url('admin.order.detail', ['id' => $order_id])->getRelativeUrlTrimmed(); ?>"
                  method="post">
                <div class="card">
                    <?php load_partial('admin/card-header', [
                        'header_title' => 'تغییر وضعیت پرداخت',
                        'collapse' => false,
                    ]); ?>

                    <div class="card-body">
                        <?php load_partial('admin/message/message-form', [
                            'errors' => $invoice_change_errors ?? [],
                            'success' => $invoice_change_success ?? '',
                            'warning' => $invoice_change_warning ?? '',
                        ]); ?>

                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                        <div class="form-group">
                            <select data-placeholder="انتخاب وضعیت پرداخت..."
                                    class="form-control form-control-select2-searchable"
                                    name="inp-change-order-invoice-status" data-fouc>
                                <option value="<?= DEFAULT_OPTION_VALUE ?>" disabled selected="selected">انتخاب کنید
                                </option>
                                <?php foreach (PAYMENT_STATUSES as $status => $text): ?>
                                    <option value="<?= $status; ?>"
                                        <?= $status == $order['payment_status'] ? 'selected="selected"' : ''; ?>>
                                        <?= $text; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="changeInvoiceStatusBtn"
                                    class="btn bg-indigo-400 btn-show-loading">
                                تغییر وضعیت پرداخت
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-6">
            <form action="<?= url('admin.order.detail', ['id' => $order_id])->getRelativeUrlTrimmed(); ?>"
                  method="post">
                <div class="card">
                    <?php load_partial('admin/card-header', [
                        'header_title' => 'تغییر وضعیت ارسال',
                        'collapse' => false,
                    ]); ?>

                    <div class="card-body">
                        <?php load_partial('admin/message/message-form', [
                            'errors' => $send_change_errors ?? [],
                            'success' => $send_change_success ?? '',
                            'warning' => $send_change_warning ?? '',
                        ]); ?>

                        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                        <div class="form-group">
                            <select data-placeholder="انتخاب وضعیت ارسال..."
                                    class="form-control form-control-select2-searchable"
                                    name="inp-change-order-send-status" data-fouc>
                                <option value="<?= DEFAULT_OPTION_VALUE ?>" disabled selected="selected">انتخاب کنید
                                </option>
                                <?php foreach ($badges as $badge): ?>
                                    <option value="<?= $badge['code']; ?>"
                                            style="background-color: <?= $badge['color']; ?>; color: <?= get_color_from_bg($badge['color']); ?>;"
                                        <?= $badge['code'] == $order['send_status_code'] ? 'selected="selected"' : ''; ?>>
                                        <?= $badge['title']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="changeSendStatusBtn"
                                    class="btn bg-success-400 btn-show-loading">
                                تغییر وضعیت ارسال
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'جزئیات سفارش']); ?>

        <div class="card-body">
            <fieldset>
                <legend class="font-weight-semibold">
                    <i class="icon-user mr-2"></i>
                    اطلاعات گیرنده
                </legend>
                <div class="row m-0">
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            نام
                        </div>
                        <div class="text-info-800">
                            <?= $order['receiver_name']; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            کد ملی
                        </div>
                        <div class="text-info-800">
                            <?= StringUtil::toPersian(trim($order['user_national_number'])) ?? '-'; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شماره تماس
                        </div>
                        <div class="text-info-800">
                            <?= StringUtil::toPersian($order['receiver_mobile']); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            استان
                        </div>
                        <div class="text-info-800">
                            <?= $order['province']; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شهر
                        </div>
                        <div class="text-info-800">
                            <?= $order['city']; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            کدپستی
                        </div>
                        <div class="text-info-800">
                            <?= StringUtil::toPersian($order['postal_code']); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 border py-2 px-3">
                        <div class="mb-2">
                            آدرس پستی
                        </div>
                        <div class="text-info-800">
                            <?= $order['address']; ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mt-3">
                <legend class="font-weight-semibold">
                    <i class="icon-basket mr-2"></i>
                    جزئیات سفارش
                </legend>

                <div class="row m-0">
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شماره فاکتور
                        </div>
                        <div class="text-info-800">
                            <?= $order['code']; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            مبلغ قابل پرداخت
                        </div>
                        <div class="text-warning-400">
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                            تومان
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            تاریخ ثبت سفارش
                        </div>
                        <div class="text-green-800">
                            <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['ordered_at']); ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            وضعیت سفارش
                        </div>
                        <span class="p-1 rounded"
                              style="background-color: <?= $order['send_status_color']; ?>; color: <?= get_color_from_bg($order['send_status_color']); ?>">
                            <?= $order['send_status_title']; ?>
                        </span>
                    </div>

                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            طریقه ارسال
                        </div>
                        <div class="text-info-800">
                            <?= $order['send_method_title']; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            هزینه ارسال
                        </div>
                        <div class="text-info-800">
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['shipping_price']))); ?>
                            <small>تومان</small>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            عنوان کوپن
                        </div>
                        <div class="text-info-800">
                            <?php if (!empty($order['coupon_title'])): ?>
                                <?= $order['coupon_title']; ?>
                            <?php else: ?>
                                <i class="icon-minus2" aria-hidden="true"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            مبلغ کوپن
                        </div>
                        <div class="text-info-800">
                            <?php if (!empty($order['coupon_price'])): ?>
                                <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['coupon_price']))); ?>
                                <small>تومان</small>
                            <?php else: ?>
                                <i class="icon-minus2" aria-hidden="true"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mt-3">
                <legend class="font-weight-semibold">
                    <i class="icon-credit-card mr-2"></i>
                    وضعیت مالی
                </legend>

                <div class="row m-0">
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            وضعیت پرداخت
                        </div>
                        <?php load_partial('admin/parser/payment-status', ['status' => $order['payment_status']]); ?>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            کد رهگیری
                        </div>
                        <strong class="text-danger-800">
                            <?php if (!is_null($order['payment_code'])): ?>
                                <?= StringUtil::toEnglish($order['payment_code']); ?>
                            <?php endif; ?>
                        </strong>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            تاریخ پرداخت
                        </div>
                        <div class="text-green-800">
                            <?php if (!empty($order['payed_at'])): ?>
                                <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['payed_at']); ?>
                            <?php else: ?>
                                <i class="icon-minus2" aria-hidden="true"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شیوه پرداخت
                        </div>
                        <div class="text-green-800">
                            <?= $order['method_title']; ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <!-- Invoice template -->
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h6 class="card-title">آیتم‌های خریداری شده</h6>
            <div class="header-elements">
                <button type="button" id="excelPdfOrder"
                        class="btn btn-sm bg-orange-800"
                        data-export-id="<?= $order_id ?>">
                    <i class="icon-file-pdf mr-2"></i>
                    دانلود فاکتور
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>مشخصات محصول</th>
                    <th>فی(به تومان)</th>
                    <th>تعداد</th>
                    <th>مرجوع شده</th>
                    <th>قیمت کل(به تومان)</th>
                    <th>قیمت نهایی(به تومان)</th>
                </tr>
                </thead>
                <tbody>
                <?php $k = 0; ?>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td data-order="<?= $k; ?>"><?= StringUtil::toPersian(++$k); ?></td>
                        <td>
                            <?php load_partial('admin/parser/image-placeholder', [
                                'img' => $item['product_image'],
                                'alt' => $item['product_title'],
                                'lightbox' => true,
                            ]); ?>
                            <div>
                                <?php if (!empty($item['product_image'])): ?>
                                    <a href="<?= url('admin.product.detail', ['id' => $item['product_id']])->getRelativeUrl(); ?>">
                                        <h6 class="mb-0">
                                            <?= $item['product_title']; ?>
                                        </h6>
                                    </a>
                                <?php else: ?>
                                    <h6 class="mb-0">
                                        <?= $item['product_title']; ?>
                                    </h6>
                                <?php endif; ?>
                                <div class="text-muted">
                                    رنگ
                                    <span class="btn-icon rounded-full p-3"
                                          style="background-color: <?= $item['color_name']; ?>"></span>
                                    <?= $item['color_name']; ?>
                                    <?php if (!empty($item['size'])): ?>
                                        ,
                                        سایز
                                        <?= $item['size']; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($item['guarantee'])): ?>
                                        ,
                                        <?= $item['guarantee']; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td data-order="<?= (int)StringUtil::toEnglish($item['unit_price']); ?>">
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['unit_price']))); ?>
                        </td>
                        <td data-order="<?= (int)StringUtil::toEnglish($item['product_count']); ?>">
                            <?= StringUtil::toPersian($item['product_count']); ?>
                        </td>

                        <?php
                        $isChecked = is_value_checked($item['is_returned']);
                        ?>
                        <td class="<?= $isChecked ? 'table-success' : 'table-warning'; ?>">
                            <?php if ($isChecked): ?>
                                بله
                            <?php else: ?>
                                خیر
                            <?php endif; ?>
                        </td>
                        <td data-order="<?= (int)StringUtil::toEnglish($item['price']); ?>">
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['price']))); ?>
                        </td>
                        <td class="table-success"
                            data-order="<?= (int)StringUtil::toEnglish($item['discounted_price']); ?>">
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['discounted_price']))); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card-body">
            <div class="d-md-flex flex-md-wrap">
                <?php if (!empty($order['coupon_price'])): ?>
                    <div class="pt-2 pr-3 mb-3">
                        <h6 class="mb-3">کوپن تخفیف:</h6>

                        <ul class="list-unstyled text-muted">
                            <li>
                                <span class="badge badge-light badge-striped badge-striped-left border-left-success">
                                    <?= $order['coupon_title']; ?>
                                    -
                                    <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['coupon_price']))); ?>
                                    تومان
                                </span>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                    <h6 class="mb-3">فاکتور نهایی:</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>مجموع مبالغ:</th>
                                <td class="text-right">
                                    <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['total_price']))); ?>
                                    تومان
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    تخفیف:
                                </th>
                                <td class="text-right">
                                    <?= StringUtil::toPersian(number_format((float)StringUtil::toEnglish($order['discount_price']))); ?>
                                    تومان
                                </td>
                            </tr>
                            <tr>
                                <th>مبلغ قابل پرداخت:</th>
                                <td class="text-right text-primary">
                                    <h5 class="font-weight-semibold">
                                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                                        تومان
                                    </h5>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /invoice template -->

</div>
<!-- /content area -->