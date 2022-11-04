<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>مرجوع سفارش</h3>
        </div>
        <div class="card-body">
            <?php load_partial('main/message/message-warning.php', [
                'warning' => session()->getFlash('err_return_order_sess') ?? '',
            ]); ?>

            <form action="<?= url('user.return-order.addTmp')->getRelativeUrlTrimmed(); ?>"
                  method="post">
                <div class="d-block d-sm-flex align-items-end">
                    <div class="form-group col px-0 px-sm-3">
                        <select name="return-order-code-inp" class="selectric_dropdown">
                            <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                    selected="selected"
                                    disabled="disabled">
                                انتخاب کنید
                            </option>
                            <?php foreach ($orders as $order): ?>
                                <option value='<?= $order['code']; ?>'>
                                    <?= $order['code']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit"
                                class="btn btn-fill-out btn-sm">
                            <i class="ti-check" aria-hidden="true"></i>
                            ثبت مرجوعی
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>سفارشات مرجوعی</h3>
        </div>
        <?php if (count($return_orders)): ?>
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
                    <?php foreach ($return_orders as $order): ?>
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
                                   class="btn btn-fill-line btn-sm">
                                    نمایش
                                </a>
                                <?php if ($order['status'] == RETURN_ORDER_STATUS_CHECKING): ?>
                                    <button type="button"
                                            data-remove-url="<?= url('ajax.user.return-order.remove')->getRelativeUrlTrimmed(); ?>"
                                            data-remove-id="<?= $order['id']; ?>"
                                            class="btn btn-fill-out btn-sm __item_remover_btn">
                                        حذف
                                    </button>
                                <?php endif; ?>
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