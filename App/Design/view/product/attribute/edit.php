<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش ویژگی جستجو']); ?>

        <div class="card-body">
            <form action="<?= url('admin.product.attr.edit')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_edit_product_attr">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $product_attr_edit_errors ?? [],
                    'success' => $product_attr_edit_success ?? '',
                    'warning' => $product_attr_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>

                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            عنوان ویژگی:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید"
                               name="inp-edit-product-attr-title" required="required"
                               value="<?= $validator->setInput('inp-edit-product-attr-title', $attrs['title']); ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            نوع ویژگی:
                        </label>
                        <select data-placeholder="نوع ویژگی را انتخاب کنید..." required="required"
                                class="form-control form-control-select2"
                                name="inp-edit-product-attr-type" data-fouc>
                            <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                    disabled="disabled"
                                    selected="selected">
                                انتخاب کنید
                            </option>
                            <?php foreach ([PRODUCT_SIDE_SEARCH_ATTR_TYPE_MULTI_SELECT, PRODUCT_SIDE_SEARCH_ATTR_TYPE_SINGLE_SELECT] as $type): ?>
                                <option value="<?= $type; ?>"
                                    <?= $validator->setSelect('inp-edit-product-attr-type', $attrs['type']) ?: ($attrs['type'] == $type ? 'selected="selected"' : ''); ?>>
                                    <?php if ($type == PRODUCT_SIDE_SEARCH_ATTR_TYPE_MULTI_SELECT): ?>
                                        چند انتخابی
                                    <?php elseif ($type == PRODUCT_SIDE_SEARCH_ATTR_TYPE_SINGLE_SELECT): ?>
                                        تک انتخابی
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
</div>
<!-- /content area -->