<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION STEPY -->
    <?php load_partial('main/section-stepy', [
        'steps' => $stepy ?? [],
    ]); ?>
    <!-- END SECTION STEPY -->

    <!-- START SECTION FORM -->
    <div class="login_register_wrap section">
        <div class="custom-container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-8 col-sm-10">
                    <div class="login_wrap">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <h3>اتمام مراحل</h3>
                            </div>
                            <div class="form-group">
                                <p class="text-success">
                                    <i class="linearicons-check"></i>
                                    کلمه عبور شما با موفقیت تغییر یافت.
                                </p>
                            </div>
                            <div class="form-note text-center">
                                <a href="<?= url('home.login'); ?>" class="btn btn-fill-out btn-block">ورود به حساب کاربری</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION FORM -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->