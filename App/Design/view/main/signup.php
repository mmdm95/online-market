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
                                <h3>ایجاد یک حساب کاربری</h3>
                            </div>
                            <form action="<?= url('home.signup')->getOriginalUrl(); ?>" method="post">
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="name"
                                           placeholder="نام خود را وارد کنید">
                                </div>
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="email"
                                           placeholder="نام کاربری خود را وارد کنید">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" required type="password" name="password"
                                           placeholder="کلمه عبور">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" required type="password" name="re-password"
                                           placeholder="تأیید کلمه عبور">
                                </div>
                                <div class="login_footer form-group">
                                    <div class="chek-form">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox"
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