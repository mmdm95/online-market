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
                    <div class="login_wrap" id="__forget_form_container">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <h3>تغییر کلمه عبور</h3>
                            </div>
                            <form action="<?= url('home.forget-password', [
                                'step' => 'step2'
                            ])->getRelativeUrlTrimmed(); ?>#__forget_form_container"
                                  method="post" id="__forget_form_step3">
                                <?php load_partial('main/message/message-form', [
                                    'errors' => $forget_errors ?? [],
                                    'success' => $forget_success ?? '',
                                    'warning' => $forget_warning ?? '',
                                ]); ?>
                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                                <div class="form-group">
                                    <input class="form-control" required type="password" name="inp-forget-new-password"
                                           placeholder="کلمه عبور جدید">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" required type="password" name="inp-forget-new-re-password"
                                           placeholder="تأیید کلمه عبور جدید">
                                </div>
                                <div class="row form-group text-center ltr m-0">
                                    <button type="submit" class="col-sm-6 btn btn-danger mb-2 rtl">
                                        تغییر کلمه عبور
                                        <i class="linearicons-chevron-left ml-2" aria-hidden="true"></i>
                                    </button>
                                    <a href="<?= url('home.forget-password', [
                                        'step' => 'step1'
                                    ]); ?>" class="col-sm-6 btn btn-light mx-0 mb-2 rtl">
                                        <i class="linearicons-reply fa-flip-horizontal ml-2" aria-hidden="true"></i>
                                        شروع مجدد
                                    </a>
                                </div>
                            </form>
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