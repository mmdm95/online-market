<?php

$validator = form_validator();

$isSmsRecover = session()->get('forget.recover_by_mobile', -1);

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION STEPY -->
    <?php load_partial('main/section-stepy', [
        'steps' => $stepy ?? [],
    ]); ?>
    <!-- END SECTION STEPY -->

    <!-- START SECTION FORM -->
    <div class="login_register_wrap section">
        <div class="custom-container container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-8 col-sm-10">
                    <div class="login_wrap" id="__forget_form_container">
                        <div class="padding_eight_all bg-white">
                            <?php if (RECOVER_PASS_TYPE_SMS == $isSmsRecover): ?>
                                <div class="heading_s1">
                                    <h3>وارد کردن کد ارسال شده</h3>
                                </div>

                                <form action="<?= url('home.forget-password', [
                                    'step' => 'step2'
                                ])->getRelativeUrlTrimmed(); ?>#__forget_form_container"
                                      method="post" id="__forget_form_step2_1">
                                    <?php load_partial('main/message/message-form', [
                                        'errors' => $forget_errors ?? [],
                                        'success' => $forget_success ?? '',
                                        'warning' => $forget_warning ?? '',
                                    ]); ?>
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>"
                                           data-ignored>
                                    <div class="form-group">
                                        <input type="text" required class="form-control" name="inp-forget-code"
                                               placeholder="کد ارسال شده">
                                    </div>
                                    <div class="row form-group text-center ltr m-0">
                                        <button type="submit" class="col-sm-6 btn btn-danger mb-2 rtl"
                                                name="submit-code">
                                            مرحله بعد
                                            <i class="linearicons-chevron-left ml-2" aria-hidden="true"></i>
                                        </button>
                                        <a href="<?= url('home.forget-password', [
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
                            <?php elseif (RECOVER_PASS_TYPE_SECURITY_QUESTION == $isSmsRecover): ?>
                                <div class="heading_s1">
                                    <h3>پاسخ سؤال امنیتی</h3>
                                </div>

                                <form action="<?= url('home.forget-password', [
                                    'step' => 'step2'
                                ])->getRelativeUrlTrimmed(); ?>#__forget_form_container"
                                      method="post" id="__forget_form_step2_2">
                                    <?php load_partial('main/message/message-form', [
                                        'errors' => $forget_errors ?? [],
                                        'success' => $forget_success ?? '',
                                        'warning' => $forget_warning ?? '',
                                    ]); ?>
                                    <p>
                                        <?= $sec_question ?? ''; ?>
                                    </p>
                                    <div class="form-group">
                                        <input type="text" required class="form-control" name="inp-forget-question"
                                               placeholder="پاسخ سؤال امنیتی"
                                               value="<?= $validator->setInput('inp-forget-question'); ?>">
                                    </div>
                                    <div class="row form-group text-center ltr m-0">
                                        <button type="submit" class="col-sm-6 btn btn-danger mb-2 rtl"
                                                name="submit-question">
                                            مرحله بعد
                                            <i class="linearicons-chevron-left ml-2" aria-hidden="true"></i>
                                        </button>
                                        <a href="<?= url('home.forget-password', [
                                            'step' => 'step1'
                                        ]); ?>" class="col-sm-6 btn btn-light mx-0 mb-2 rtl">
                                            <i class="linearicons-reply fa-flip-horizontal ml-2"
                                               aria-hidden="true"></i>
                                            شروع مجدد
                                        </a>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="heading_s1">
                                    <h3>خطا در ارزیابی اطلاعات</h3>
                                </div>

                                <div class="row form-group text-center ltr m-0">
                                    <a href="<?= url('home.forget-password', [
                                        'step' => 'step1'
                                    ]); ?>" class="col-sm-6 btn btn-light mx-0 mb-2 rtl">
                                        <i class="linearicons-reply fa-flip-horizontal ml-2"
                                           aria-hidden="true"></i>
                                        شروع مجدد
                                    </a>
                                </div>
                            <?php endif; ?>
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