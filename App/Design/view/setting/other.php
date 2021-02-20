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
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    تعداد کالا در هر صفحه:
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="از نوع عددی"
                                       name="inp-setting-product-pagination"
                                       value="<?= $validator->setInput('inp-setting-product-pagination') ?: config()->get('settings.product_each_page.value'); ?>">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    تعداد بلاگ در هر صفحه:
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="از نوع عددی"
                                       name="inp-setting-blog-pagination"
                                       value="<?= $validator->setInput('inp-setting-blog-pagination') ?: config()->get('settings.blog_each_page.value'); ?>">
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
