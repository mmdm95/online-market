<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>نتیجه شارژ کیف پول</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="text-center">
                        <?php if ($result): ?>
                            <i class="fas fa-check-circle text-success"></i>
                            <div class="heading_s1">
                                <h3>کیف پول شما شارژ شد</h3>
                            </div>
                        <?php else: ?>
                            <i class="fas fa-times-circle text-danger"></i>
                            <div class="heading_s1">
                                <h3>شارژ کیف پول با خطا مواجه شد!</h3>
                            </div>
                        <?php endif; ?>

                        <?php if ($price > 0): ?>
                            <p class="<?= $result ? 'text-info' : 'text-danger'; ?>">
                                مبلغ درخواستی شارژ کیف پول:
                                <?= local_number(number_format($price)); ?>
                                <small>تومان</small>
                            </p>
                        <?php endif; ?>
                        <?php if ($wallet_code): ?>
                            <p>
                                کد شارژ کیف پول:
                                <?= $wallet_code; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($payment_code): ?>
                            <p>
                                کد پیگیری:
                                <?= $payment_code; ?>
                            </p>
                        <?php endif; ?>

                        <p><?= $message; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>