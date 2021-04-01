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
            <form action="<?= url('admin.setting.main')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_main">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_main_errors ?? [],
                    'success' => $setting_main_success ?? '',
                    'warning' => $setting_main_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات اصلی']); ?>

                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'تصاویر لوگو']); ?>
                        <div class="d-block d-sm-flex justify-content-center flex-wrap">
                            <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                                <?php
                                $img = !$validator->getStatus() ? ($validator->setInput('inp-setting-logo-img') ?: config()->get('settings.logo.value')) : config()->get('settings.logo.value');
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-setting-logo-img"
                                           value="<?= $img; ?>">
                                    <?php if (!empty($img)): ?>
                                        <img class="img-placeholder-image" src="<?= url('image.show') . $img; ?>"
                                             alt="selected image">
                                    <?php endif; ?>
                                    <div class="img-placeholder-icon-container">
                                        <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                        <div class="img-placeholder-num bg-warning text-white">
                                            <i class="icon-plus2"></i>
                                        </div>
                                    </div>
                                </div>
                                <label class="form-text text-info">
                                    انتخاب تصویر لوگو
                                </label>
                            </div>

                            <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                                <?php
                                $img = !$validator->getStatus() ? ($validator->setInput('inp-setting-logo-light-img') ?: config()->get('settings.logo_light.value')) : config()->get('settings.logo_light.value');
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-setting-logo-light-img"
                                           value="<?= $img; ?>">
                                    <?php if (!empty($img)): ?>
                                        <img class="img-placeholder-image" src="<?= url('image.show') . $img; ?>"
                                             alt="selected image">
                                    <?php endif; ?>
                                    <div class="img-placeholder-icon-container">
                                        <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                        <div class="img-placeholder-num bg-warning text-white">
                                            <i class="icon-plus2"></i>
                                        </div>
                                    </div>
                                </div>
                                <label class="form-text text-info">
                                    انتخاب تصویر سفید لوگو
                                </label>
                            </div>

                            <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                                <?php
                                $img = !$validator->getStatus() ? ($validator->setInput('inp-setting-fav-img') ?: config()->get('settings.favicon.value')) : config()->get('settings.favicon.value');
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-setting-fav-img"
                                           value="<?= $img; ?>">
                                    <?php if (!empty($img)): ?>
                                        <img class="img-placeholder-image" src="<?= url('image.show') . $img; ?>"
                                             alt="selected image">
                                    <?php endif; ?>
                                    <div class="img-placeholder-icon-container">
                                        <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                        <div class="img-placeholder-num bg-warning text-white">
                                            <i class="icon-plus2"></i>
                                        </div>
                                    </div>
                                </div>
                                <label class="form-text text-info">
                                    انتخاب فاو آیکون
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'اطلاعات سایت']); ?>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                عنوان سایت:
                            </label>
                            <input type="text" class="form-control maxlength"
                                   placeholder="تا ۵۰ کاراکتر"
                                   maxlength="50"
                                   name="inp-setting-title"
                                   value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-title') ?: config()->get('settings.title.value')): config()->get('settings.title.value'); ?>">
                        </div>
                        <div class="form-group">
                            <label>توضیحات مختصر:</label>
                            <textarea class="form-control form-control-min-height maxlength-placeholder"
                                      placeholder="تا ۲۵۰ کاراکتر"
                                      maxlength="250"
                                      name="inp-setting-desc"
                            ><?= !$validator->getStatus() ? ($validator->setInput('inp-setting-desc') ?: config()->get('settings.description.value')): config()->get('settings.description.value'); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>کلمات کلیدی:</label>
                            <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                                   name="inp-setting-tags"
                                   value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-tags') ?: config()->get('settings.keywords.value')): config()->get('settings.keywords.value'); ?>">
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

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->
</div>
<!-- /content area -->
