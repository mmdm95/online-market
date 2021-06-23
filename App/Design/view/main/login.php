<?php
$validator = form_validator();
?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START LOGIN SECTION -->
    <div class="login_register_wrap section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-8 col-sm-10">
                    <div class="login_wrap">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <h3>ورود</h3>
                            </div>
                            <form action="<?= url('home.login', null, ['back_url' => $_GET['back_url'] ?? ''])->getRelativeUrlTrimmed(); ?>"
                                  method="post" id="__form_login">
                                <?php load_partial('main/message/message-form', [
                                    'errors' => $login_errors ?? [],
                                    'warning' => $login_warning ?? '',
                                ]); ?>
                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="inp-login-username"
                                           placeholder="موبایل"
                                           value="<?= $validator->setInput('inp-login-username'); ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" required type="password" name="inp-login-password"
                                           placeholder="کلمه عبور">
                                </div>
                                <div class="login_footer form-group">
                                    <a href="<?= url('home.forget-password'); ?>">کلمه عبور را فراموش کرده اید؟</a>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <input placeholder="کد تصویر را وارد کنید" class="form-control"
                                               name="inp-login-captcha" required>
                                    </div>
                                    <div class="form-group col-md-6 d-flex justify-content-center align-items-center __captcha_main_container">
                                        <div class="__captcha_container">
                                        </div>
                                        <button class="btn btn-link text_default p-2 ml-3 __captcha_regenerate_btn"
                                                type="button" aria-label="regenerate captcha">
                                            <input type="hidden" name="inp-captcha-name"
                                                   value="<?= url() . '__form_login'; ?>">
                                            <i class="icon-refresh icon-2x" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-fill-out btn-block" name="login">ورود</button>
                                </div>
                            </form>
                            <div class="form-note text-center">
                                حساب کاربری ندارید؟
                                <a href="<?= url('home.signup'); ?>">ثبت نام کنید</a>
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