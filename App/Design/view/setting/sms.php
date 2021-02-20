<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">

    <!-- Inner container -->
    <div class="d-xl-flex align-items-xl-start">

        <!-- Left sidebar component -->
        <?php load_partial('admin/setting-sidebar'); ?>
        <!-- /left sidebar component -->

        <!-- Right content -->
        <div class="w-100">
            <form action="<?= url('admin.setting.sms')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_sms">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_sms_errors ?? [],
                    'success' => $setting_sms_success ?? '',
                    'warning' => $setting_sms_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات پیامک']); ?>

                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'پیامک فعالسازی حساب']); ?>
                        <!-- Activation code -->
                        <div class="form-group">
                            <textarea
                                    class="form-control form-control-min-height"
                                    name="inp-setting-sms-activation"
                                    cols="30"
                                    rows="10"
                                    placeholder="متن پیامک را وارد کنید..."
                            ><?= $validator->setInput('inp-setting-sms-activation') ?: config()->get('settings.sms_activation.value'); ?></textarea>
                            <label class="form-text">
                                می‌توانید از
                                <code>@code@</code>
                                برای قرار دادن محل کد و از
                                <code>@mobile@</code>
                                برای قرار دادن محل شماره موبایل، استفاده کنید.
                            </label>
                        </div>
                        <!-- /activation code -->

                        <?php load_partial('admin/section-header', ['header_title' => 'پیامک فراموشی کلمه عبور']); ?>
                        <!-- Recover pass -->
                        <div class="form-group">
                            <textarea
                                    class="form-control form-control-min-height"
                                    name="inp-setting-sms-recover-pass"
                                    cols="30"
                                    rows="10"
                                    placeholder="متن پیامک را وارد کنید..."
                            ><?= $validator->setInput('inp-setting-sms-recover-pass') ?: config()->get('settings.sms_recover_pass.value'); ?></textarea>
                            <label class="form-text">
                                می‌توانید از
                                <code>@code@</code>
                                برای قرار دادن محل کد و از
                                <code>@mobile@</code>
                                برای قرار دادن محل شماره موبایل، استفاده کنید.
                            </label>
                        </div>
                        <!-- /recover pass -->

                        <?php load_partial('admin/section-header', ['header_title' => 'پیامک خرید کالا']); ?>
                        <!-- Buy product -->
                        <div class="form-group">
                            <textarea
                                    class="form-control form-control-min-height"
                                    name="inp-setting-sms-buy"
                                    cols="30"
                                    rows="10"
                                    placeholder="متن پیامک را وارد کنید..."
                            ><?= $validator->setInput('inp-setting-sms-buy') ?: config()->get('settings.sms_buy.value'); ?></textarea>
                            <label class="form-text">
                                می‌توانید از
                                <code>@orderCode@</code>
                                برای قرار دادن محل شماره سفارش و از
                                <code>@mobile@</code>
                                برای قرار دادن محل شماره موبایل، استفاده کنید.
                            </label>
                        </div>
                        <!-- /buy product -->

                        <?php load_partial('admin/section-header', ['header_title' => 'پیامک تغییر وضعیت سفارش']); ?>
                        <!-- Order status change -->
                        <div class="form-group">
                            <textarea
                                    class="form-control form-control-min-height"
                                    name="inp-setting-sms-order-status"
                                    cols="30"
                                    rows="10"
                                    placeholder="متن پیامک را وارد کنید..."
                            ><?= $validator->setInput('inp-setting-sms-order-status') ?: config()->get('settings.sms_order_status.value'); ?></textarea>
                            <label class="form-text">
                                می‌توانید از
                                <code>@orderCode@</code>
                                برای قرار دادن محل شماره سفارش و از
                                <code>@mobile@</code>
                                برای قرار دادن محل شماره موبایل و از
                                <code>@status@</code>
                                برای قرار دادن وضعیت سفارش، استفاده کنید.
                            </label>
                        </div>
                        <!-- /order status change -->

                        <?php load_partial('admin/section-header', ['header_title' => 'پیامک شارژ حساب کاربری']); ?>
                        <!-- Wallet charge -->
                        <div class="form-group">
                            <textarea
                                    class="form-control form-control-min-height"
                                    name="inp-setting-sms-wallet-charge"
                                    cols="30"
                                    rows="10"
                                    placeholder="متن پیامک را وارد کنید..."
                            ><?= $validator->setInput('inp-setting-sms-wallet-charge') ?: config()->get('settings.sms_wallet_charge.value'); ?></textarea>
                            <label class="form-text">
                                می‌توانید از
                                <code>@mobile@</code>
                                برای قرار دادن محل شماره موبایل و از
                                <code>@balance@</code>
                                برای قرار دادن اعتبار فعلی استفاده کنید.
                            </label>
                        </div>
                        <!-- /wallet charge -->
                    </div>

                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success btn-block">
                                    ذخیره تنظیمات
                                    <i class="icon-check2 ml-2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /sidebars overview -->
            </form>
        </div>
        <!-- /right content -->

    </div>
    <!-- /inner container -->

</div>
<!-- /content area -->
