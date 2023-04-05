<?php

$validator = form_validator();
$userInfo = get_current_authenticated_user(auth_home());

?>

<!-- START MAIN COMPLAINT -->
<div class="main_content">
    <!-- START SECTION COMPLAINT -->
    <div class="section pt-0">
        <div class="custom-container container">
            <div class="row">
                <div class="col-lg-10 mx-auto mt-5" id="__complaint_form_container">
                    <div class="heading_s1">
                        <h2>ارسال شکایت</h2>
                    </div>
                    <p class="leads">
                        پیام خود را با ما در میان بگذارید.
                    </p>
                    <div class="field_form">
                        <form action="<?= url('home.complaint')->getRelativeUrlTrimmed(); ?>#__complaint_form_container"
                              method="post" id="__form_complaint">
                            <?php load_partial('main/message/message-form', [
                                'errors' => $complaint_errors ?? [],
                                'success' => $complaint_success ?? '',
                                'warning' => $complaint_warning ?? '',
                            ]); ?>
                            <div class="row">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                                <div class="form-group col-md-6">
                                    <input placeholder="نام را وارد کنید *"
                                           class="form-control" name="inp-complaint-name" type="text" required
                                           value="<?= $validator->setInput('inp-complaint-name', $userInfo['first_name'] ?? ''); ?>">
                                </div>
                                <div class="form-group col-md-6 ltr">
                                    <input placeholder="ایمیل را وارد کنید *" class="form-control text-left"
                                           name="inp-complaint-email" type="text" required
                                           value="<?= $validator->setInput('inp-complaint-email', $userInfo['email'] ?? ''); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <input placeholder="شماره موبایل خود را وارد کنید *"
                                           class="form-control" name="inp-complaint-mobile" type="text" required
                                           value="<?= $validator->setInput('inp-complaint-mobile', $userInfo['username'] ?? ''); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <input placeholder="موضوع را وارد کنید *" class="form-control"
                                           name="inp-complaint-subject" type="text" required
                                           value="<?= $validator->setInput('inp-complaint-subject'); ?>">
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea placeholder="متن شکایت *" class="form-control"
                                              name="inp-complaint-message" required
                                              rows="4"><?= $validator->setInput('inp-complaint-message'); ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <input placeholder="کد تصویر را وارد کنید *" class="form-control"
                                           name="inp-complaint-captcha" required>
                                </div>
                                <div class="form-group col-md-6 d-flex justify-content-center align-items-center __captcha_main_container">
                                    <div class="__captcha_container">
                                    </div>
                                    <button class="btn btn-link text_default p-2 ml-3 __captcha_regenerate_btn"
                                            type="button" aria-label="regenerate captcha">
                                        <input type="hidden" name="inp-captcha-name"
                                               value="<?= url() . '__form_complaint'; ?>">
                                        <i class="icon-refresh icon-2x" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-fill-out">
                                        ارسال شکایت
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION COMPLAINT -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN COMPLAINT -->