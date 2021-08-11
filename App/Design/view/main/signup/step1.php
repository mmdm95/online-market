<?php
$validator = form_validator();
?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START LOGIN SECTION -->
    <div class="login_register_wrap section">
        <div class="custom-container container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-8 col-sm-10">
                    <div class="login_wrap" id="__register_form_container">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <h3>ایجاد یک حساب کاربری</h3>
                            </div>
                            <form action="<?= url('home.signup')->getRelativeUrlTrimmed(); ?>#__register_form_container"
                                  method="post" id="__form_register">
                                <?php load_partial('main/message/message-form', [
                                    'errors' => $register_errors ?? [],
                                    'success' => $register_success ?? '',
                                    'warning' => $register_warning ?? '',
                                ]); ?>

                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="inp-register-username"
                                           placeholder="موبایل خود را وارد کنید"
                                           value="<?= $validator->setInput('inp-register-username'); ?>">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input placeholder="کد تصویر را وارد کنید" class="form-control"
                                               name="inp-register-captcha" required>
                                    </div>
                                    <div class="form-group col-md-6 d-flex justify-content-center align-items-center __captcha_main_container">
                                        <div class="__captcha_container">
                                        </div>
                                        <button class="btn btn-link text_default p-2 ml-3 __captcha_regenerate_btn"
                                                type="button" aria-label="regenerate captcha">
                                            <input type="hidden" name="inp-captcha-name"
                                                   value="<?= url() . '__form_register'; ?>" data-ignored>
                                            <i class="icon-refresh icon-2x" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="login_footer form-group">
                                    <div class="chek-form">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox"
                                                   name="inp-register-terms-chk"
                                                   id="termChk">
                                            <label class="form-check-label" for="termChk">
                                                <span>
                                                    من با
                                                    <a href="<?= url('home.pages', [
                                                        'url' => 'terms',
                                                    ]); ?>"
                                                       class="btn-link"
                                                       target="_blank">
                                                        شرایط و سیاست
                                                    </a>
                                                    سایت موافقم
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-fill-out btn-block" name="register">
                                        ثبت نام
                                    </button>
                                </div>
                            </form>
                            <div class="form-note text-center">
                                حساب کاربری دارید؟
                                <a href="<?= url('home.login'); ?>">وارد شوید</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END LOGIN SECTION -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->