<?php

$validator = form_validator();

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="custom-container container">
            <form action="#"
                  method="post" id="__checkout_payment_gateway">
                <div class="row" id="__theia_sticky_sidebar_container">
                    <div class="col-md-6">
                        <div class="heading_s1">
                            <h4>جزئیات صورتحساب</h4>
                        </div>

                        <div class="form-group">
                            <label>
                                <span class="text-danger" aria-hidden="true">*</span>
                                نام:
                            </label>
                            <?php
                            $ufn = trim($user['first_name']);
                            ?>
                            <input type="text" class="form-control" name="fname"
                                   placeholder="حروف فارسی"
                                <?= $ufn ? 'disabled="disabled"' : '' ?>
                                   value="<?= $ufn ?: $validator->setInput('fname'); ?>">
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger" aria-hidden="true">*</span>
                                نام خانوادگی:
                            </label>
                            <?php
                            $uln = trim($user['last_name']);
                            ?>
                            <input type="text" class="form-control" name="lname"
                                   placeholder="حروف فارسی"
                                <?= $uln ? 'disabled="disabled"' : '' ?>
                                   value="<?= $uln ?: $validator->setInput('lname'); ?>">
                        </div>

                        <?php if (empty($user['national_number'])): ?>
                            <div class="form-group">
                                <label>
                                    <span class="text-danger" aria-hidden="true">*</span>
                                    کد ملی:
                                </label>
                                <input type="text" class="form-control" name="natnum"
                                       placeholder="از نوع عددی"
                                       value="<?= $user['national_number'] ?: $validator->setInput('natnum'); ?>">
                            </div>
                        <?php endif; ?>

                        <div class="medium_divider"></div>

                        <div class="heading_s1">
                            <h4>خرید به عنوان</h4>
                        </div>

                        <!-- Radio Buttons -->
                        <div class="pb-4 mb-4 border-bottom">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input d-none"
                                       type="radio" name="inp-is-real-or-legal"
                                       value="<?= RECEIVER_TYPE_REAL; ?>"
                                    <?= $validator->setRadio('inp-addr-full-name', RECEIVER_TYPE_REAL, true); ?>
                                       id="realOrLegalTab1">
                                <label
                                        class="form-check-label active-real-or-legal-radio"
                                        style="cursor: pointer;"
                                        for="realOrLegalTab1"
                                >
                                    شخص حقیقی
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input d-none"
                                       type="radio" name="inp-is-real-or-legal"
                                       value="<?= RECEIVER_TYPE_LEGAL; ?>"
                                    <?= $validator->setRadio('inp-addr-full-name', RECEIVER_TYPE_LEGAL, false); ?>
                                       id="realOrLegalTab2">
                                <label
                                        class="form-check-label"
                                        style="cursor: pointer;"
                                        for="realOrLegalTab2"
                                >
                                    شخص حقوقی
                                </label>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3" id="realOrLegalTabContent">
                            <div class="tab-pane fade show active" id="realOrLegalContent1">
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-fill-line"
                                            id="__user_addr_choose_btn"
                                            data-toggle="modal" data-target="#__user_addr_choose_modal">
                                        انتخاب آدرس
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        نام گیرنده:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="حروف فارسی" name="inp-addr-full-name"
                                           value="<?= $validator->setInput('inp-addr-full-name') ?: trim("{$user['first_name']} {$user['last_name']}"); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        موبایل گیرنده:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="یازده رقم" name="inp-addr-mobile"
                                           value="<?= $validator->setInput('inp-addr-mobile') ?: $user['username']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-addr-province"
                                            class="selectric_dropdown city-loader-select"
                                            data-current-province="<?= $validator->setInput('inp-addr-province'); ?>"
                                            data-city-select-target="#addAddressCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شهر:
                                    </label>
                                    <select name="inp-addr-city"
                                            class="selectric_dropdown"
                                            data-current-city="<?= $validator->setInput('inp-addr-city'); ?>"
                                            id="addAddressCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        کد پستی:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="از نوع عددی" name="inp-addr-postal-code"
                                           value="<?= $validator->setInput('inp-addr-postal-code'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            type="text"
                                            class="form-control form-control-min-height"
                                            name="inp-addr-address"
                                            placeholder=""
                                    ><?= $validator->setInput('inp-addr-address'); ?></textarea>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="realOrLegalContent2">
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-fill-line"
                                            id="__user_addr_company_choose_btn"
                                            data-toggle="modal" data-target="#__user_addr_company_choose_modal">
                                        انتخاب آدرس
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        نام شرکت:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="حروف فارسی" name="inp-addr-company-name"
                                           value="<?= $validator->setInput('inp-addr-company-name'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        کد اقتصادی:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="وارد نمایید" name="inp-addr-company-eco-code"
                                           value="<?= $validator->setInput('inp-addr-company-eco-code'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شناسه ملی:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="وارد نمایید" name="inp-addr-company-eco-nid"
                                           value="<?= $validator->setInput('inp-addr-company-eco-nid'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شماره ثبت:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="وارد نمایید" name="inp-addr-company-reg-num"
                                           value="<?= $validator->setInput('inp-addr-company-reg-num'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        تلفن ثابت:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="یازده رقم" name="inp-addr-tel"
                                           value="<?= $validator->setInput('inp-addr-tel'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-addr-company-province"
                                            class="selectric_dropdown city-loader-select"
                                            data-current-province="<?= $validator->setInput('inp-addr-company-province'); ?>"
                                            data-city-select-target="#addAddressCompanyCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شهر:
                                    </label>
                                    <select name="inp-addr-company-city"
                                            class="selectric_dropdown"
                                            data-current-city="<?= $validator->setInput('inp-addr-company-city'); ?>"
                                            id="addAddressCompanyCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        کد پستی:
                                    </label>
                                    <input class="form-control" type="text"
                                           placeholder="از نوع عددی" name="inp-addr-company-postal-code"
                                           value="<?= $validator->setInput('inp-addr-company-postal-code'); ?>">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            type="text"
                                            class="form-control form-control-min-height"
                                            name="inp-addr-company-address"
                                            placeholder=""
                                    ><?= $validator->setInput('inp-addr-company-address'); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="medium_divider"></div>
                                <div class="divider center_icon"><i class="linearicons-credit-card"></i></div>
                                <div class="medium_divider"></div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="coupon_form" id="coupon">
                                    <div class="panel-body">
                                        <p>اگر کد کوپن دارید ، لطفاً آن را در اینجا وارد کنید.</p>
                                        <div class="coupon field_form input-group">
                                            <input type="text" value=""
                                                   class="form-control __coupon_field_inp"
                                                   placeholder="کد کوپن را وارد کنید...">
                                            <div class="input-group-append">
                                                <button class="btn btn-fill-out btn-sm __apply_coupon" type="button">
                                                    اعمال کوپن
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="__theia_sticky_sidebar">
                        <div class="order_review">
                            <div class="heading_s1">
                                <h4>سفارشات شما</h4>
                            </div>
                            <div class="shop-cart-info-table">
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="medium_divider"></div>
                                    <div class="divider center_icon">
                                        <i class="linearicons-circle" style="background-color: #f7f8fb;"></i>
                                    </div>
                                    <div class="medium_divider"></div>
                                </div>
                            </div>

                            <div class="payment_method">
                                <div class="heading_s1">
                                    <h4>روش ارسال</h4>
                                </div>
                                <div class="payment_option">
                                    <?php if (count($send_methods)): ?>
                                        <?php $counter = 0; ?>
                                        <?php foreach ($send_methods as $k => $method): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="custome-radio">
                                                    <input class="form-check-input"
                                                        <?= 0 == $counter++ ? 'checked="checked"' : ''; ?>
                                                           type="radio" name="send_method_option"
                                                           id="sendMethod<?= $k; ?>" value="<?= $method['code']; ?>">
                                                    <label class="form-check-label" for="sendMethod<?= $k; ?>">
                                                        <img src=""
                                                             data-src="<?= url('image.show', ['filename' => $method['image']])->getRelativeUrl(); ?>"
                                                             alt="<?= $method['title']; ?>" width="100px" height="auto"
                                                             class="lazy">
                                                        <span class="ml-2"><?= $method['title']; ?></span>
                                                    </label>
                                                    <div class="mt-2"><?= $method['desc']; ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        هیچ روش ارسالی وجود ندارد
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="medium_divider"></div>
                                    <div class="divider center_icon">
                                        <i class="linearicons-circle" style="background-color: #f7f8fb;"></i>
                                    </div>
                                    <div class="medium_divider"></div>
                                </div>
                            </div>

                            <div class="payment_method">
                                <div class="heading_s1">
                                    <h4>روش پرداخت</h4>
                                </div>
                                <div class="payment_option">
                                    <?php if (count($payment_methods)): ?>
                                        <?php $counter = 0; ?>
                                        <?php foreach ($payment_methods as $k => $method): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="custome-radio">
                                                    <input class="form-check-input"
                                                        <?= 0 == $counter++ ? 'checked="checked"' : ''; ?>
                                                           type="radio" name="payment_method_option"
                                                           id="method<?= $k; ?>" value="<?= $method['code']; ?>">
                                                    <label class="form-check-label" for="method<?= $k; ?>">
                                                        <img src=""
                                                             data-src="<?= $method['method_type'] == METHOD_TYPE_WALLET ? asset_path($method['image'], false) : url('image.show', ['filename' => $method['image']])->getRelativeUrl(); ?>"
                                                             alt="<?= $method['title']; ?>" width="100px" height="auto"
                                                             class="lazy">
                                                        <span class="ml-2"><?= $method['title']; ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        هیچ روش پرداختی وجود ندارد
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-fill-out btn-block">ثبت سفارش و پرداخت</button>
                    </div>
                </div>
        </div>
        </form>
    </div>
    <!-- END SECTION SHOP -->

    <!-- START ADD ADDRESS MODAL -->
    <div class="modal fade subscribe_popup" id="__user_addr_choose_modal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
                    </button>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="popup_content text-left">
                                <div class="popup-text">
                                    <div class="heading_s3 text-left">
                                        <h4>انتخاب آدرس گیرنده</h4>
                                    </div>
                                </div>

                                <?php load_partial('main/message/message-info', [
                                    'info' => 'تنها آدرس هایی که در پنل کاربری خود وارد کرده‌اید، آورده شده است.',
                                    'dismissible' => false,
                                ]); ?>

                                <div id="__address_choice_container">
                                    <?php load_partial('main/ajax/user-addresses', [
                                        'addresses' => $addresses,
                                    ]); ?>
                                </div>

                                <div>
                                    <button class="btn btn-primary btn-block text-uppercase"
                                            id="__address_choice_button"
                                            title="اشتراک" type="button">
                                        انتخاب آدرس
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ADD ADDRESS MODAL -->

    <!-- START ADD ADDRESS COMPANY MODAL -->
    <div class="modal fade subscribe_popup" id="__user_addr_company_choose_modal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
                    </button>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="popup_content text-left">
                                <div class="popup-text">
                                    <div class="heading_s3 text-left">
                                        <h4>انتخاب آدرس گیرنده</h4>
                                    </div>
                                </div>

                                <?php load_partial('main/message/message-info', [
                                    'info' => 'تنها آدرس هایی که در پنل کاربری خود وارد کرده‌اید، آورده شده است.',
                                    'dismissible' => false,
                                ]); ?>

                                <div id="__address_company_choice_container">
                                    <?php load_partial('main/ajax/user-addresses-company', [
                                        'addresses' => $addresses_company,
                                    ]); ?>
                                </div>

                                <div>
                                    <button class="btn btn-primary btn-block text-uppercase"
                                            id="__address_company_choice_button"
                                            title="اشتراک" type="button">
                                        انتخاب آدرس
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ADD ADDRESS COMPANY MODAL -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->
