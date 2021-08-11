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
            <form action="<?= url('admin.setting.buy')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_buy">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_buy_errors ?? [],
                    'success' => $setting_buy_success ?? '',
                    'warning' => $setting_buy_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات خرید']); ?>

                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'محل فروشگاه']); ?>
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    انتخاب استان:
                                </label>
                                <select data-placeholder="انتخاب کنید..."
                                        name="inp-setting-store-province" data-fouc
                                        class="form-control form-control-select2-searchable city-loader-select"
                                        data-city-select-target="#storeCitySelect"
                                        data-current-province="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-store-province') ?: config()->get('settings.store_province.value')) : config()->get('settings.store_province.value'); ?>">
                                    <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                            selected="selected"
                                            disabled="disabled">
                                        انتخاب کنید
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>
                                    <span class="text-danger" aria-hidden="true">*</span>
                                    انتخاب شهر:
                                </label>
                                <select name="inp-setting-store-city"
                                        class="form-control form-control-select2-searchable "
                                        id="storeCitySelect"
                                        data-current-city="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-store-city') ?: config()->get('settings.store_city.value')) : config()->get('settings.store_city.value'); ?>">
                                    <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                            selected="selected"
                                            disabled="disabled">
                                        انتخاب کنید
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>
                                    قیمت در مناطق داخل شهر انتخاب شده(به تومان):
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="از نوع عددی"
                                       name="inp-setting-current-city-post-price"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-current-city-post-price') ?: config()->get('settings.current_city_post_price.value')) : config()->get('settings.current_city_post_price.value'); ?>">
                            </div>
                        </div>

                        <?php load_partial('admin/section-header', ['header_title' => 'سایر تنظیمات']); ?>
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>
                                    حداقل قیمت رایگان شدن هزینه ارسال(به تومان):
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="از نوع عددی"
                                       name="inp-setting-min-free-price"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-min-free-price') ?: config()->get('settings.min_free_price.value')) : config()->get('settings.min_free_price.value'); ?>">
                            </div>
                        </div>
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
