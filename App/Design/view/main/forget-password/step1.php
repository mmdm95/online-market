<?php
$validator = form_validator();
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
        <div class="custom-container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-8 col-sm-10">
                    <div class="login_wrap" id="__forget_form_container">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <h3>وارد کردن شماره موبایل</h3>
                            </div>
                            <form action="<?= url('home.forget-password', [
                                'step' => 'step1'
                            ])->getRelativeUrlTrimmed(); ?>#__forget_form_container"
                                  method="post" id="__forget_form_step1">
                                <?php load_partial('main/message/message-form', [
                                    'errors' => $forget_errors ?? [],
                                    'success' => $forget_success ?? '',
                                    'warning' => $forget_warning ?? '',
                                ]); ?>
                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="inp-forget-mobile"
                                           placeholder="شماره موبایل شما"
                                           value="<?= $validator->setInput('inp-forget-mobile'); ?>">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input placeholder="کد تصویر را وارد کنید" class="form-control"
                                               name="inp-forget-captcha" required>
                                    </div>
                                    <div class="form-group col-md-6 d-flex justify-content-center align-items-center __captcha_main_container">
                                        <div class="__captcha_container">
                                        </div>
                                        <button class="btn btn-link text_default p-2 ml-3 __captcha_regenerate_btn"
                                                type="button" aria-label="regenerate captcha">
                                            <input type="hidden" name="inp-captcha-name"
                                                   value="<?= url() . '__forget_form_step1'; ?>">
                                            <i class="icon-refresh icon-2x" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group text-center ltr">
                                    <button type="submit" class="btn btn-danger rtl">
                                        مرحله بعد
                                        <i class="linearicons-chevron-left ml-2" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="form-note text-center">
                                <a href="<?= url('home.login'); ?>">ورود</a>
                                |
                                <a href="<?= url('home.signup'); ?>">ثبت نام کنید</a>
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