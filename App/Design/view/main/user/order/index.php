<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>سفارشات</h3>
        </div>
        <?php if (count($orders)): ?>
            <div class="table-responsive pt-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>سفارش</th>
                        <th>تاریخ</th>
                        <th>وضعیت ارسال</th>
                        <th>وضعیت پرداخت</th>
                        <th>پرداخت از طریق</th>
                        <th>جمع</th>
                        <th>اقدامات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
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
                            <td><?= $order['method_title']; ?></td>
                            <td>
                                <?= number_format(StringUtil::toEnglish($order['final_price'])); ?>
                                تومان
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