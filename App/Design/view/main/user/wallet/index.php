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
                  method="post" id="__frm_charge_user_wallet">

                <div class="mb-3">
                    <label>
                        مبلغ مورد نظر به تومان:
                    </label>
                    <input type="text"
                           class="form-control"
                           placeholder="از نوع عددی"
                           name="inp-wallet-price">
                </div>
                <div class="mb-3">
                    <div class="order_review">
                        <div class="payment_method">
                            <div class="heading_s1">
                                <h6>روش پرداخت</h6>
                            </div>
                            <div class="payment_option">
                                <?php if (count($payment_methods)): ?>
                                    <?php $counter = 0; ?>
                                    <?php foreach ($payment_methods as $k => $method): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="custome-radio">
                                                <input class="form-check-input" required=""
                                                    <?= 0 == $counter++ ? 'checked="checked"' : ''; ?>
                                                       type="radio" name="inp-wallet-payment-method-option"
                                                       id="method<?= $k; ?>" value="<?= $method['code']; ?>">
                                                <label class="form-check-label" for="method<?= $k; ?>">
                                                    <img src=""
                                                         data-src="<?= url('image.show', ['filename' => $method['image']])->getRelativeUrl(); ?>"
                                                         alt="<?= $method['title']; ?>" width="100px" height="auto"
                                                         class="lazy">
                                                    <span class="ml-2"><?= $method['title']; ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    هیچ روش پرداختی وجود ندارد.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit"
                            class="btn btn-fill-out btn-sm">
                        <i class="ti-check" aria-hidden="true"></i>
                        انجام عملیات
                    </button>
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