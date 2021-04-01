<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>شارژ کیف پول</h3>
        </div>
        <div class="card-body">
            <form action="<?= url('user.wallet.charge')->getRelativeUrlTrimmed(); ?>"
                  method="post">
                <div class="d-block d-sm-flex align-items-end">
                    <div class="form-group col px-0 px-sm-3">
                        <label>
                            مبلغ مورد نظر به تومان:
                        </label>
                        <input type="text"
                               class="form-control"
                               placeholder="از نوع عددی"
                               name="">
                    </div>
                    <div class="form-group text-right">
                        <button type="submit"
                                class="btn btn-fill-out btn-sm">
                            <i class="ti-check" aria-hidden="true"></i>
                            انجام عملیات
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
            <h3>تراکنش‌های کیف پول</h3>
        </div>
        <?php if (count($wallet_flow)): ?>
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
                    <?php foreach ($wallet_flow as $flow): ?>
                        <tr>
                            <td class="en-font"><?= $flow['order_code']; ?></td>
                            <td><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $flow['deposit_at']); ?></td>
                            <td><?= $flow['deposit_type_title']; ?></td>
                            <td>
                                <?= number_format(StringUtil::toEnglish($flow['deposit_price'])); ?>
                                تومان
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