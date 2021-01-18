<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش روش پرداخت']); ?>

        <div class="card-body">
            <form action="<?= url('admin.pay_method.edit')->getRelativeUrl() . $payment['id']; ?>" method="post"
                  id="__form_edit_pay_method">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $pay_method_edit_errors ?? [],
                    'success' => $pay_method_edit_success ?? '',
                    'warning' => $pay_method_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-12">
                        <div class="d-block d-lg-flex justify-content-between align-items-end">
                            <div class="form-group text-center text-lg-left">
                                <label>
                                    <span class="text-danger">*</span>
                                    انتخاب تصویر:
                                </label>
                                <?php
                                $img = $validator->setInput('inp-edit-pay-method-img') ?: (url('image.show')->getRelativeUrl() . $payment['image']);
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto ml-lg-0 mr-lg-3 mb-0 <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-edit-pay-method-img"
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
                                        نمایش روش پرداخت
                                        <input type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-pay-method-status"
                                            <?= $validator->setCheckbox('inp-edit-pay-method-status', 'on') ?: (is_value_checked($payment['publish']) ? 'checked="checked"' : ''); ?>>
                                        عدم نمایش روش پرداخت
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            عنوان روش پرداخت:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید" name="inp-edit-pay-method-title"
                               value="<?= $validator->setInput('inp-edit-pay-method-title') ?: $payment['name']; ?>">
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">
                        ویرایش اطلاعات
                        <i class="icon-checkmark3 ml-2"></i>
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