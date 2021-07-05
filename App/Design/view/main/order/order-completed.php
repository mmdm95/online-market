<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="custom-container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="text-center order_complete">
                        <?php if ($result): ?>
                            <i class="fas fa-check-circle text-success"></i>
                            <div class="heading_s1">
                                <h3>سفارش شما انجام شد</h3>
                            </div>
                        <?php else: ?>
                            <i class="fas fa-times-circle text-danger"></i>
                            <div class="heading_s1">
                                <h3>سفارش شما با خطا مواجه شد!</h3>
                            </div>
                        <?php endif; ?>

                        <?php if ($order_code): ?>
                            <p>
                                کد سفارش:
                                <?= $order_code; ?>
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
                        <a href="<?= url('home.search')->getRelativeUrl() ?>" class="btn btn-info">
                            ادامه خرید
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
