<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن صفحه ثابت جدید']); ?>

        <div class="card-body">
            <form action="<?= url('admin.static.page.add')->getRelativeUrlTrimmed(); ?>" method="post"
                  id="__form_add_static_page">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $static_page_add_errors ?? [],
                    'success' => $static_page_add_success ?? '',
                    'warning' => $static_page_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group text-center text-lg-right">
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    نمایش صفحه
                                    <input type="checkbox" class="form-check-input-switchery"
                                           name="inp-add-static-page-status"
                                        <?= $validator->setCheckbox('inp-add-static-page-status', 'on', true); ?>>
                                    عدم نمایش صفحه
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xl-6">
                        <label>
                            <span class="text-danger">*</span>
                            عنوان صفحه:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید" name="inp-add-static-page-title"
                               value="<?= $validator->setInput('inp-add-static-page-title'); ?>">
                    </div>
                    <div class="form-group col-xl">
                        <label>
                            <span class="text-danger">*</span>
                            آدرس صفحه:
                        </label>
                        <div class="d-flex ltr">
                            <span class="form-control ltr text-grey-300 text-left d-none d-md-block" disabled="disabled">
                                <?= get_base_url() . ltrim(url('home.pages')->getRelativeUrl(), '/'); ?>
                            </span>
                            <input type="text" class="form-control ltr text-right" placeholder="حروف لاتین"
                                   name="inp-add-static-page-url"
                                   value="<?= $validator->setInput('inp-add-static-page-url'); ?>">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>کلمات کلیدی:</label>
                        <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                               name="inp-add-static-page-keywords"
                               value="<?= $validator->setInput('inp-add-static-page-keywords'); ?>">
                    </div>
                    <div class="form-group col-lg-12">
                        <label>
                            <span class="text-danger">*</span>
                            توضیحات مطلب:
                        </label>
                        <textarea name="inp-add-static-page-desc"
                                  cols="30"
                                  rows="10"
                                  placeholder="توضیحات خود را وارد کنید..."
                                  class="form-control cntEditor"
                        ><?= $validator->setInput('inp-add-static-page-desc'); ?></textarea>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        ذخیره اطلاعات
                        <i class="icon-floppy-disks ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php load_partial('editor/browser-tiny-func'); ?>
</div>
<!-- /content area -->

