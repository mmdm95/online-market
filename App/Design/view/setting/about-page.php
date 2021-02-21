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
            <form action="<?= url('admin.setting.pages.about')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_pages_about">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_about_errors ?? [],
                    'success' => $setting_about_success ?? '',
                    'warning' => $setting_about_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات صفحه درباره']); ?>

                    <div class="card-body">
                        <?php
                        $aboutSec = config()->get('settings.about_section.value') ?: [];
                        ?>

                        <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                            <?php
                            $img = $validator->setInput('inp-setting-img') ?: ($aboutSec['image'] ?? '');
                            ?>
                            <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto <?= !empty($img) ? 'has-image' : ''; ?>"
                                 data-toggle="modal"
                                 data-target="#modal_efm">
                                <input type="hidden" name="inp-setting-img"
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
                                <span class="text-danger">*</span>
                                انتخاب تصویر درباره
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                عنوان درباره:
                            </label>
                            <input type="text" class="form-control"
                                   placeholder="از نوع عددی"
                                   name="inp-setting-title"
                                   value="<?= $validator->setInput('inp-setting-title') ?: ($aboutSec['title'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                توضیحات درباره:
                            </label>
                            <textarea
                                    class="form-control cntEditor"
                                    name="inp-setting-desc"
                                    cols="30"
                                    rows="10"
                                    placeholder=""
                            ><?= $validator->setInput('inp-setting-desc') ?: ($aboutSec['desc'] ?? ''); ?></textarea>
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

    <?php load_partial('editor/browser-tiny-func'); ?>
</div>
<!-- /content area -->
