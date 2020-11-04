<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">

            <!-- Login card -->
            <form class="login-form form-validate" action="<?= \url('admin.login'); ?>" method="post">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                            <h5 class="mb-0">به حساب کاربری خود وارد شوید.</h5>
                        </div>

                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="text" class="form-control" name="username" placeholder="نام کاربری" required>
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="password" class="form-control" name="password" placeholder="رمز عبور" required>
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group d-flex align-items-center">
                            <div class="form-check mb-0">
                                <label class="form-check-label">
                                    <input type="checkbox" name="remember" class="form-input-styled" checked data-fouc>
                                    مرا به خاطر بسپار!
                                </label>
                            </div>

                            <a href="login_password_recover.html" class="ml-auto">بازیابی رمز عبور</a>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">وارد شو <i class="icon-circle-left2 ml-2"></i></button>
                        </div>

                        <div class="form-group text-center text-muted content-divider">
                            <span class="px-2">حساب کاربری ندارید؟</span>
                        </div>

                        <div class="form-group">
                            <a href="#" class="btn btn-light btn-block">ثبت نام کنید.</a>
                        </div>

                        <span class="form-text text-center text-muted">توسعه داده شده توسط <a href="#">تیم</a> <a href="#">هیوا</a> </span>
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
