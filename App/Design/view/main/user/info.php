<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <div class="row" id="__theia_sticky_sidebar_container">
                <!-- START DASHBOARD MENU -->
                <?php load_partial('main/user/dashboard-menu', ['user' => $user]); ?>
                <!-- END DASHBOARD MENU -->

                <div class="col-lg-9 col-md-8">
                    <div class="dashboard_content">
                        <div class="card">
                            <div class="card-header">
                                <h3>جزئیات حساب</h3>
                            </div>
                            <div class="card-body">
                                <p>قبلاً حساب دارید؟ <a href="#">وارد شوید!</a></p>
                                <form method="post" name="enq">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>نام <span class="required">*</span></label>
                                            <input required="" class="form-control" name="name" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>نام خانوادگی <span class="required">*</span></label>
                                            <input required="" class="form-control" name="phone">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>نام کاربری <span class="required">*</span></label>
                                            <input required="" class="form-control" name="dname" type="text">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>آدرس ایمیل <span class="required">*</span></label>
                                            <input required="" class="form-control" name="email" type="email">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>کلمه عبور فعلی <span class="required">*</span></label>
                                            <input required="" class="form-control" name="password" type="password">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>کلمه عبور جدید <span class="required">*</span></label>
                                            <input required="" class="form-control" name="npassword"
                                                   type="password">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>تأیید کلمه عبور <span class="required">*</span></label>
                                            <input required="" class="form-control" name="cpassword"
                                                   type="password">
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-fill-out" name="submit"
                                                    value="Submit">ذخیره
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP -->
</div>