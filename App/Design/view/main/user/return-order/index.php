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
            <form action="<?= url('user.return-order.add')->getRelativeUrlTrimmed(); ?>"
                  method="post">
                <div class="d-block d-sm-flex align-items-end">
                    <div class="form-group col px-0 px-sm-3">
                        <select name="" class="selectric_dropdown">
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
                        <th>سفارش</th>
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
                            <td class="en-font"><?= $order['order_code']; ?></td>
                            <td><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $order['requested_at']); ?></td>
                            <td>
                                <?php load_partial('admin/parser/return-order-status', ['type' => $order['status']]); ?>
                            </td>
                            <td>
                                <?= number_format(StringUtil::toEnglish($order['final_price'])); ?>
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