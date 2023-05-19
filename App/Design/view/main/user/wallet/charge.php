<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="custom-container container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="text-center order_complete">

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

                        <a href="<?= url('home.index')->getRelativeUrl(); ?>" class="btn btn-dark">
                            بازگشت به خانه
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->
