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
                                <h3>وارد کردن اطلاعات مورد نیاز</h3>
                            </div>
                            <form action="<?= url('home.signup.info')->getRelativeUrlTrimmed(); ?>#__register_form_container"
                                  method="post" id="__form_register_step2_and_half">
                                <?php load_partial('main/message/message-form', [
                                    'errors' => $register_errors ?? [],
                                    'success' => $register_success ?? '',
                                    'warning' => $register_warning ?? '',
                                ]); ?>
                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                                <div class="form-group">
                                    <input class="form-control" required type="text" name="inp-register-first-name"
                                           placeholder="نام">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" required type="text" name="inp-register-last-name"
                                           placeholder="نام خانوادگی">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" required type="number" name="inp-register-n-code"
                                           maxlength="10" placeholder="کد ملی">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-fill-out btn-block" name="register">
                                        ادامه
                                    </button>
                                </div>
                            </form>
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