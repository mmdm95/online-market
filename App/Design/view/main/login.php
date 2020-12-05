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
                            <form action="<?= url('home.login')->getOriginalUrl(); ?>" method="post">
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="username"
                                           placeholder="نام کاربری">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" required type="password" name="password"
                                           placeholder="کلمه عبور">
                                </div>
                                <div class="login_footer form-group">
                                    <div class="chek-form">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox"
                                                   id="rememberChk">
                                            <label class="form-check-label" for="rememberChk">
                                                <span>مرا به خاطر بسپار</span>
                                            </label>
                                        </div>
                                    </div>
                                    <a href="<?= url('home.forget-password'); ?>">رمز عبور را فراموش کرده اید؟</a>
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