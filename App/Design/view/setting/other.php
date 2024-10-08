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
            <form action="<?= url('admin.setting.other')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_other">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_other_errors ?? [],
                    'success' => $setting_other_success ?? '',
                    'warning' => $setting_other_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>

                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'سایر تنظیمات']); ?>

                    <div class="card-body">
                        <!-- Default image placeholder -->
                        <?php load_partial('admin/section-header', ['header_title' => 'تصویر پیش فرض پیش از بارگذاری تصویر']); ?>
                        <div class="d-block d-sm-flex justify-content-center flex-wrap">
                            <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                                <?php
                                $img = !$validator->getStatus() ? ($validator->setInput('inp-setting-default-image-placeholder') ?: config()->get('settings.default_image_placeholder.value')) : config()->get('settings.default_image_placeholder.value');
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-setting-default-image-placeholder"
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
                                    انتخاب تصویر
                                </label>
                            </div>
                        </div>

                        <!-- /default image placeholder -->

                        <?php load_partial('admin/section-header', ['header_title' => 'تعداد نمایش آیتم‌ها']); ?>
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    تعداد کالا در هر صفحه:
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="از نوع عددی"
                                       name="inp-setting-product-pagination"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-product-pagination') ?: config()->get('settings.product_each_page.value')) : config()->get('settings.product_each_page.value'); ?>">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    تعداد بلاگ در هر صفحه:
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="از نوع عددی"
                                       name="inp-setting-blog-pagination"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-blog-pagination') ?: config()->get('settings.blog_each_page.value')) : config()->get('settings.blog_each_page.value'); ?>">
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

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->
</div>
<!-- /content area -->
