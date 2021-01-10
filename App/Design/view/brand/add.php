<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن برند جدید']); ?>

        <div class="card-body">
            <form action="<?= url('admin.brand.add')->getRelativeUrlTrimmed(); ?>" method="post" id="__form_add_brand">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $brand_add_errors ?? [],
                    'success' => $brand_add_success ?? '',
                    'warning' => $brand_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-12">
                        <div class="d-block d-lg-flex justify-content-between align-items-end">
                            <div class="form-group text-center text-lg-left">
                                <label>
                                    <span class="text-danger">*</span>
                                    انتخاب تصویر برند:
                                </label>
                                <?php
                                $img = $validator->setInput('inp-add-brand-img');
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto ml-lg-0 mr-lg-3 mb-0 <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-add-brand-img"
                                           value="<?= $img; ?>">
                                    <?php if (!empty($img)): ?>
                                        <img class="img-placeholder-image" src="<?= $img; ?>" alt="selected image">
                                    <?php endif; ?>
                                    <div class="img-placeholder-icon-container">
                                        <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                        <div class="img-placeholder-num bg-warning text-white">
                                            <i class="icon-plus2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center text-lg-right">
                                <div class="form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        نمایش برند
                                        <input type="checkbox" class="form-check-input-switchery"
                                               name="inp-add-brand-status"
                                            <?= $validator->setCheckbox('inp-add-brand-status', 'on', true); ?>>
                                        عدم نمایش برند
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            عنوان فارسی برند:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید" name="inp-add-brand-fa-title"
                               value="<?= $validator->setInput('inp-add-brand-fa-title'); ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            عنوان انگلیسی برند:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید" name="inp-add-brand-en-title"
                               value="<?= $validator->setInput('inp-add-brand-en-title'); ?>">
                    </div>
                    <div class="form-group col-lg-12">
                        <label>کلمات کلیدی:</label>
                        <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                               name="inp-add-brand-keywords"
                               value="<?= $validator->setInput('inp-add-brand-keywords'); ?>">
                    </div>
                    <div class="form-group col-lg-12">
                        <label>
                            <span class="text-danger">*</span>
                            توضیحات برند:
                        </label>
                        <textarea name="inp-add-brand-desc"
                                  cols="30"
                                  rows="10"
                                  placeholder="توضیحات خود را وارد کنید..."
                                  class="form-control cntEditor"
                        ><?= $validator->setInput('inp-add-brand-desc'); ?></textarea>
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

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->

    <?php load_partial('editor/browser-tiny-func'); ?>
</div>
<!-- /content area -->