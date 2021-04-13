<?php

$validator = form_validator();

?>

<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">

            <!-- Login card -->
            <form class="login-form form-validate"
                  action="<?= url('admin.login')->getRelativeUrlTrimmed(); ?>"
                  method="post">
                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>

                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                            <h5 class="mb-0">به حساب کاربری خود وارد شوید</h5>
                        </div>

                        <?php load_partial('admin/message/message-form', [
                            'errors' => $login_errors ?? [],
                            'warning' => $login_warning ?? '',
                        ]); ?>

                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="text" class="form-control" name="inp-username" placeholder="نام کاربری"
                                   required value="<?= $validator->setInput('inp-username'); ?>">
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="password" class="form-control" name="inp-password" placeholder="کلمه عبور"
                                   required>
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-center align-items-center __captcha_main_container">
                            <div class="__captcha_container">
                            </div>
                            <button class="btn btn-link rounded-full border text-dark p-2 ml-3 __captcha_regenerate_btn"
                                    type="button" aria-label="regenerate captcha">
                                <input type="hidden" name="inp-captcha-name"
                                       value="<?= url() . '__form_login'; ?>">
                                <i class="icon-reload-alt icon-half-x" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="form-group">
                            <input placeholder="کد تصویر را وارد کنید" class="form-control"
                                   name="inp-login-captcha" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn bg-primary btn-block">
                                بیا تو دم در بده
                            </button>
                        </div>

                        <span class="form-text text-center text-muted">
                            توسعه داده شده توسط
                            <a href="#">تیم هیوا</a>
                        </span>
                    </div>
                </div>
            </form>
            <!-- /login card -->

        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->
