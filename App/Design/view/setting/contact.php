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
            <form action="<?= url('admin.setting.contact')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_contact">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_contact_errors ?? [],
                    'success' => $setting_contact_success ?? '',
                    'warning' => $setting_contact_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات تماس']); ?>

                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'اطلاعات اصلی']); ?>
                        <!-- Main info -->
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                شماره تماس اصلی:
                            </label>
                            <input type="text" class="form-control"
                                   placeholder="وارد کنید"
                                   name="inp-setting-main-phone"
                                   value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-main-phone') ?: config()->get('settings.main_phone.value')) : config()->get('settings.main_phone.value'); ?>">
                        </div>
                        <div class="form-group">
                            <label>
                                آدرس:
                            </label>
                            <textarea
                                    class="form-control form-control-min-height"
                                    name="inp-setting-address"
                                    cols="30"
                                    rows="10"
                                    placeholder="متن پیامک را وارد کنید..."
                            ><?= !$validator->getStatus() ? ($validator->setInput('inp-setting-address') ?: config()->get('settings.address.value')) : config()->get('settings.address.value'); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>
                                شماره‌های تماس:
                            </label>
                            <input type="text" class="form-control tags-input"
                                   placeholder="وارد کنید"
                                   name="inp-setting-phones"
                                   value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-phones') ?: config()->get('settings.phones.value')) : config()->get('settings.phones.value'); ?>">
                        </div>
                        <!-- /main info -->

                        <?php load_partial('admin/section-header', ['header_title' => 'ویژگی‌ها']); ?>
                        <!-- Properties -->
                        <?php
                        $features = config()->get('settings.features.value') ?: [];
                        $feature1 = $features[0] ?? ['title' => '', 'sub_title' => ''];
                        $feature2 = $features[1] ?? ['title' => '', 'sub_title' => ''];
                        $feature3 = $features[2] ?? ['title' => '', 'sub_title' => ''];
                        ?>
                        <div class="row">
                            <div class="col-lg-5 form-group">
                                <label>
                                    عنوان:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder=""
                                       name="inp-setting-features-title[]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-features-title') ?: $feature1['title']) : $feature1['title']; ?>">
                            </div>
                            <div class="col-lg-7 form-group">
                                <label>
                                    زیر عنوان:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder=""
                                       name="inp-setting-features-sub-title[]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-features-sub-title') ?: $feature1['sub_title']) : $feature1['sub_title']; ?>">
                            </div>
                            <div class="col-lg-5 form-group">
                                <label>
                                    عنوان:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder=""
                                       name="inp-setting-features-title[]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-features-title') ?: $feature2['title']) : $feature2['title']; ?>">
                            </div>
                            <div class="col-lg-7 form-group">
                                <label>
                                    زیر عنوان:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder=""
                                       name="inp-setting-features-sub-title[]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-features-sub-title') ?: $feature2['sub_title']) : $feature2['sub_title']; ?>">
                            </div>
                            <div class="col-lg-5 form-group">
                                <label>
                                    عنوان:
                                </label>
                                <input type="text" class="form-control"
                                       placeholder=""
                                       name="inp-setting-features-title[]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-features-title') ?: $feature3['title']) : $feature3['title']; ?>">
                            </div>
                            <div class="col-lg-7 form-group">
                                <label>
                                    زیر عنوان:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder=""
                                       name="inp-setting-features-sub-title[]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-features-sub-title') ?: $feature3['sub_title']) : $feature3['sub_title']; ?>">
                            </div>
                        </div>
                        <!-- /properties -->

                        <?php load_partial('admin/section-header', ['header_title' => 'سایر']); ?>
                        <!-- Other -->
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <label>
                                    ایمیل:
                                </label>
                                <input type="text" class="form-control"
                                       placeholder=""
                                       name="inp-setting-email"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-email') ?: config()->get('settings.email.value')) : config()->get('settings.email.value'); ?>">
                            </div>

                            <div class="col-12">
                                <div class="row border border-info m-0 px-2 py-3 rounded">
                                    <div class="col-12">
                                        <?php load_partial('admin/message/message-info', [
                                            'info' => 'از نقشه گوگل یا پارسی مپ کمک بگیرید و <code>lng</code> و <code>lat</code> یا همان طول و عرض جغرافیفایی آدرس خود را وارد کنید.',
                                            'dismissible' => false,
                                        ]); ?>
                                    </div>

                                    <?php
                                    $location = config()->get('settings.lat_lng.value') ?: [];
                                    $lng = $location['lng'] ?? '';
                                    $lat = $location['lat'] ?? '';
                                    ?>
                                    <div class="col-lg-6 form-group">
                                        <label>
                                            طول جغرافیایی:
                                        </label>
                                        <input type="text" class="form-control"
                                               placeholder=""
                                               name="inp-setting-lng"
                                               value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-lng') ?: $lng) : $lng; ?>">
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>
                                            عرض جغرافیایی:
                                        </label>
                                        <input type="text" class="form-control"
                                               placeholder=""
                                               name="inp-setting-lat"
                                               value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-lat') ?: $lat) : $lat; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /other -->
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
