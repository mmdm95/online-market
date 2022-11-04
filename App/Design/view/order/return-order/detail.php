<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">

    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'عملیات قابل انجام']); ?>

        <div class="card-body">
            <?php load_partial('admin/message/message-form', [
                'errors' => $return_order_status_handling_errors ?? [],
                'success' => $return_order_status_handling_success ?? '',
                'warning' => $return_order_status_handling_warning ?? '',
            ]); ?>
            <?php load_partial('admin/message/message-form', [
                'errors' => $return_order_respond_handling_errors ?? [],
                'success' => $return_order_respond_handling_success ?? '',
                'warning' => $return_order_respond_handling_warning ?? '',
            ]); ?>

            <div class="row">
                <div class="col-md-4 mb-2">
                    <a href="<?= url('admin.order.detail', ['id' => $order_id]); ?>"
                       class="btn btn-info btn-block">
                        جزئيات سفارش
                    </a>
                </div>
                <div class="col-md-8 mb-2">
                    <form action="<?= url('admin.return.order.detail')->getRelativeUrlTrimmed(); ?>">
                        <div class="form-group">
                            <label>وضعیت مرجوعی:</label>
                            <select data-placeholder="انتخاب وضعیت مرجوعی..."
                                    class="form-control form-control-select2"
                                    name="inp-return-order-status" data-fouc>
                                <option value="<?= DEFAULT_OPTION_VALUE ?>" disabled selected="selected">انتخاب کنید
                                </option>
                                <?php foreach (RETURN_ORDER_STATUSES as $status => $label): ?>
                                    <option value="<?= $status; ?>"
                                        <?= $status == $return_order['status'] ? 'selected="selected"' : ''; ?>>
                                        <?= $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="return-order-status-btn"
                                    class="btn bg-orange-400 btn-show-loading">
                                تغییر وضعیت مرجوعی
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-12">
                    <form action="<?= url('admin.return.order.detail')->getRelativeUrlTrimmed(); ?>">
                        <div class="form-group">
                            <label>پاسخ به کاربر:</label>
                            <textarea class="form-control form-control-min-height maxlength-placeholder"
                                      placeholder="تا ۵۰۰ کاراکتر"
                                      maxlength="500"
                                      name="inp-return-order-respond"
                            ><?= $validator->setInput('inp-return-order-respond', $return_order['respond']); ?></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" name="return-order-respond-btn"
                                    class="btn bg-purple-400 btn-show-loading">
                                ارسال پاسخ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'جزئیات سفارش']); ?>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <fieldset>
                        <legend class="font-weight-semibold">
                            <i class="icon-list mr-2"></i>
                            جزئیات سفارش مرجوعی
                        </legend>

                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>درخواست کننده:</label>
                                <div>
                                    <a href="<?= url('admin.user.view', ['id' => $return_order['user_id']])->getRelativeUrl(); ?>"
                                       class="btn btn-link">
                                        <?php if (!empty($return_order['user_first_name']) || !empty($return_order['user_last_name'])): ?>
                                            <h6 class="d-inline-block">
                                                <?= trim($return_order['user_first_name'] . ' ' . $return_order['user_last_name']); ?>
                                            </h6>
                                            -
                                        <?php endif; ?>
                                        <?= local_number($return_order['username']); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>شماره فاکتور:</label>
                                <div class="text-info-800">
                                    <strong>
                                        <?= $return_order['order_code']; ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>تاریخ درخواست مرجوعی:</label>
                                <div class="text-slate">
                                    <strong>
                                        <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $return_order['requested_at']); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>وضعیت ارجاع:</label>
                                <?php load_partial('admin/parser/return-order-status', ['type' => $return_order['status']]); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-12">
                    <fieldset>
                        <legend class="font-weight-semibold">
                            <i class="icon-comment mr-2"></i>
                            توضیحات کاربر
                        </legend>

                        <div class="form-group">
                            <p class="p-2 bg-dark-alpha text-dark rounded">
                                <?= $return_order['desc']; ?>
                            </p>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice template -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'آیتم‌های مورد درخواست جهت مرجوعی']); ?>

        <div class="table-responsive">
            <table class="table">
                <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>محصول مرجوع شده</th>
                    <th>قیمت واحد(به تومان)</th>
                    <th>تعداد درخواستی برای مرجوع</th>
                    <th>قیمت نهایی(به تومان)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($return_order_items ?? [] as $item): ?>
                    <tr>
                        <td><?= local_number($item['return_id']); ?></td>
                        <td>
                            <?php if (!empty($item['product_image'])): ?>
                                <a href="<?= url('admin.product.detail', ['id' => $item['product_id']])->getRelativeUrl(); ?>">
                                    <?php load_partial('admin/parser/image-placeholder', [
                                        'img' => $item['product_image'],
                                        'alt' => $item['product_title']
                                    ]); ?>
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
                                <span class="btn-icon rounded-full p-1 d-inline-block mt-1"
                                      style="background-color: <?= $item['color']; ?>; width: 15px; height: 15px;"></span>
                                (<?= $item['color_name']; ?>)
                                <?php if (!empty($item['size'])): ?>
                                    ,
                                    سایز
                                    (<?= $item['size']; ?>)
                                <?php endif; ?>
                                <?php if (!empty($item['guarantee'])): ?>
                                    ,
                                    (<?= $item['guarantee']; ?>)
                                <?php endif; ?>
                            </div>
                        </td>
                        <td data-order="<?= (int)StringUtil::toEnglish($item['unit_price']); ?>">
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['unit_price']))); ?>
                            تومان
                        </td>
                        <td data-order="<?= (int)StringUtil::toEnglish($item['product_count']); ?>">
                            <?= StringUtil::toPersian($item['return_count']); ?>
                            <?= $item['unit_title']; ?>
                        </td>
                        <td data-order="<?= (int)StringUtil::toEnglish($item['discounted_price']); ?>">
                            <?php if ((int)StringUtil::toEnglish($item['price']) > (int)StringUtil::toEnglish($item['discounted_price'])): ?>
                                <del>
                                    <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['price']))); ?>
                                    تومان
                                </del>
                            <?php endif; ?>
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['discounted_price']))); ?>
                            تومان
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /invoice template -->
</div>
<!-- /content area -->