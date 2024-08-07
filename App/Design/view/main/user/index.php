<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- Summary of things -->
<div class="row mt-3 mt-md-0">
    <div class="col-lg-12">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-body border border-dark-alpha">
                    <div class="text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="linearicons-bag icon-2x mr-3" aria-hidden="true"></i>
                            <div>سفارشات</div>
                        </div>
                        <h4 class="mt-3 mb-0">
                            <?= local_number(number_format(StringUtil::toEnglish($order_count))); ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-6">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-body border border-dark-alpha">
                    <div class="text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="linearicons-cart-remove icon-2x mr-3" aria-hidden="true"></i>
                            <div>مرجوعی</div>
                        </div>
                        <h4 class="mt-3 mb-0">
                            <?= local_number(number_format(StringUtil::toEnglish($return_order_count))); ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-6">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-body border border-dark-alpha">
                    <div class="text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="linearicons-wallet icon-2x mr-3" aria-hidden="true"></i>
                            <div>کیف پول</div>
                        </div>
                        <h4 class="mt-3 mb-0">
                            <?php if (0 == $wallet_balance): ?>
                                فاقد موجودی
                            <?php else: ?>
                                <?= local_number(number_format(StringUtil::toEnglish($wallet_balance))); ?>
                                <small>تومان</small>
                            <?php endif; ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-body border border-info">
                    <div class="text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="linearicons-heart icon-2x mr-3 text-danger"
                               aria-hidden="true"></i>
                            <div>مورد علاقه</div>
                        </div>
                        <h4 class="text-info mt-3 mb-0">
                            <?= local_number(number_format(StringUtil::toEnglish($favorite_count))); ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-body border border-success">
                    <div class="text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="ti-comments-smiley icon-2x mr-3" aria-hidden="true"></i>
                            <div>نظرات تایید شده</div>
                        </div>
                        <h4 class="text-success mt-3 mb-0">
                            <?= local_number(number_format(StringUtil::toEnglish($accept_comment_count))); ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="dashboard_content">
            <div class="card">
                <div class="card-body border border-danger">
                    <div class="text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="linearicons-bubble-alert icon-2x mr-3" aria-hidden="true"></i>
                            <div>نظرات تایید نشده</div>
                        </div>
                        <h4 class="text-danger mt-3 mb-0">
                            <?= local_number(number_format(StringUtil::toEnglish($not_accept_comment_count))); ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /summary of things -->

<!-- Last n orders -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>آخرین سفارشات</h3>
        </div>
        <?php if (count($last_orders)): ?>
            <div class="table-responsive pt-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>سفارش</th>
                        <th>تاریخ</th>
                        <th>وضعیت ارسال</th>
                        <th>وضعیت پرداخت</th>
                        <th>جمع</th>
                        <th>اقدامات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($last_orders as $order): ?>
                        <tr>
                            <td class="en-font"><?= $order['code']; ?></td>
                            <td><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['ordered_at']); ?></td>
                            <td>
                                <?php load_partial('admin/parser/status-badge', [
                                    'text' => $order['send_status_title'],
                                    'bg' => $order['send_status_color'],
                                ]); ?>
                            </td>
                            <td>
                                <?php load_partial('admin/parser/payment-status', ['status' => $order['payment_status']]); ?>
                            </td>
                            <td>
                                <?= local_number(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                                <small>تومان</small>
                            </td>
                            <td>
                                <a href="<?= url('user.order.detail', ['id' => $order['id']])->getRelativeUrlTrimmed(); ?>"
                                   class="btn btn-fill-out btn-sm">
                                    نمایش
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <?php load_partial('main/not-found-rows', ['show_border' => false]); ?>
        <?php endif; ?>
    </div>
</div>
<!-- /last n orders -->

<!-- Last n returned orders -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>آخرین سفارشات مرجوع شده</h3>
        </div>
        <?php if (count($last_return_orders)): ?>
            <div class="table-responsive pt-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>کد مرجوعی</th>
                        <th>کد سفارش</th>
                        <th>تاریخ</th>
                        <th>وضعیت</th>
                        <th>جمع</th>
                        <th>اقدامات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($last_return_orders as $order): ?>
                        <tr>
                            <td class="en-font"><?= $order['code']; ?></td>
                            <td class="en-font">
                                <a href="<?= url('user.order.detail', ['id' => $order['order_id']])->getRelativeUrlTrimmed(); ?>"
                                   class="btn btn-link">
                                    <?= $order['order_code']; ?>
                                </a>
                            </td>
                            <td><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['requested_at']); ?></td>
                            <td>
                                <?php load_partial('admin/parser/return-order-status', ['type' => $order['status']]); ?>
                            </td>
                            <td>
                                <?= local_number(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                                تومان
                            </td>
                            <td>
                                <a href="<?= url('user.return-order.detail', ['id' => $order['id']])->getRelativeUrl(); ?>"
                                   class="btn btn-fill-out btn-sm">
                                    نمایش
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <?php load_partial('main/not-found-rows', ['show_border' => false]); ?>
        <?php endif; ?>
    </div>
</div>
<!-- /last n returned orders -->

<!-- Last n wallet transaction -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>آخرین تراکنش‌های کیف پول</h3>
        </div>
        <?php if (count($last_wallet_flow)): ?>
            <div class="table-responsive pt-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>کد</th>
                        <th>تاریخ</th>
                        <th>عنوان</th>
                        <th>مبلغ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($last_wallet_flow as $flow): ?>
                        <tr>
                            <td class="en-font">
                                <?= $flow['order_code']; ?>
                            </td>
                            <td>
                                <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $flow['deposit_at']); ?>
                            </td>
                            <td>
                                <?= $flow['deposit_type_title']; ?>
                            </td>
                            <?php
                            $formattedDepositPrice = local_number(number_format(StringUtil::toEnglish(abs($flow['deposit_price']))));
                            ?>
                            <?php if ($flow['deposit_price'] > 0): ?>
                                <td class="text-success">
                                    <?= $formattedDepositPrice ?>
                                    <small>تومان</small>
                                    <span class="linearicons-arrow-up" aria-hidden="true"></span>
                                </td>
                            <?php else: ?>
                                <td class="text-danger">
                                    <?= $formattedDepositPrice; ?>
                                    -
                                    <small>تومان</small>
                                    <span class="linearicons-arrow-down" aria-hidden="true"></span>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <?php load_partial('main/not-found-rows', ['show_border' => false]); ?>
        <?php endif; ?>
    </div>
</div>
<!-- /last n wallet transaction -->