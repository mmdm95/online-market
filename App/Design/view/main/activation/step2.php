<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION STEPY -->
    <?php load_partial('main/section-stepy', [
        'steps' => $stepy ?? [],
    ]); ?>
    <!-- END SECTION STEPY -->

    <!-- START SECTION FORM -->
    <div class="login_register_wrap section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-8 col-sm-10">
                    <div class="login_wrap">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <h3>وارد کردن کد ارسال شده</h3>
                            </div>
                            <form action="<?= url('home.activation', [
                                'step' => 'step2'
                            ])->getOriginalUrl(); ?>" method="post">
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="activate-code"
                                           placeholder="کد ارسال شده">
                                </div>
                                <div class="row form-group text-center ltr m-0">
                                    <button type="submit" class="col-sm-6 btn btn-danger mb-2 rtl" name="submit-code">
                                        مرحله بعد
                                        <i class="linearicons-chevron-left ml-2" aria-hidden="true"></i>
                                    </button>
                                    <a href="<?= url('home.activation', [
                                        'step' => 'step1'
                                    ]); ?>" class="col-sm-6 btn btn-light mx-0 mb-2 rtl">
                                        <i class="linearicons-reply fa-flip-horizontal ml-2"
                                           aria-hidden="true"></i>
                                        شروع مجدد
                                    </a>
                                </div>
                            </form>
                            <div class="form-note text-center">
                                <a href="javascript:void(0);" id="resendCode">ارسال مجدد کد</a>
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