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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-8 col-sm-10">
                    <div class="login_wrap" id="__forget_form_container">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <h3>وارد کردن شماره موبایل</h3>
                            </div>
                            <form action="<?= url('home.forget-password', [
                                'step' => 'step1'
                            ])->getOriginalUrl(); ?>#__forget_form_container"
                                  method="post" id="__forget_form_step1">
                                <?php load_partial('main/message/message-form', [
                                    'errors' => $forget_errors ?? [],
                                    'success' => $forget_success ?? '',
                                    'warning' => $forget_warning ?? '',
                                ]); ?>
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="inp-forget-mobile"
                                           placeholder="شماره موبایل شما"
                                           value="<?= $validator->setInput('inp-forget-mobile'); ?>">
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