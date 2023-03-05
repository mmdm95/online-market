<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش تخصیص ویژگی به دسته‌بندی']); ?>

        <div class="card-body">
            <form action="<?= url('admin.product.attr.category.edit')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_edit_product_attr">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $product_attr_cat_edit_errors ?? [],
                    'success' => $product_attr_cat_edit_success ?? '',
                    'warning' => $product_attr_cat_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>

                <div class="row">
                    <div class="form-group col-lg-5">
                        <label>
                            <span class="text-danger">*</span>
                            ویژگی:
                        </label>
                        <select data-placeholder="ویژگی را انتخاب کنید..." required="required"
                                class="form-control form-control-select2-searchable"
                                name="inp-edit-product-attr-id" data-fouc>
                            <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                    disabled="disabled"
                                    selected="selected">
                                انتخاب کنید
                            </option>
                            <?php foreach ($attrs as $attr): ?>
                                <option value="<?= $attr['id']; ?>"
                                    <?= $attrCategory['p_attr_id'] == $attr['id'] ? 'selected="selected"' : ''; ?>>
                                    <?= $attr['title']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-5">
                        <label>
                            <span class="text-danger">*</span>
                            دسته‌بندی:
                        </label>
                        <select data-placeholder="دسته‌بندی را انتخاب کنید..." required="required"
                                class="form-control form-control-select2-searchable"
                                name="inp-edit-product-cat-id" data-fouc>
                            <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                    disabled="disabled"
                                    selected="selected">
                                انتخاب کنید
                            </option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id']; ?>"
                                    <?= $attrCategory['c_id'] == $category['id'] ? 'selected="selected"' : ''; ?>>
                                    <?= $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>
                            <span class="text-danger">*</span>
                            اولویت:
                        </label>
                        <input type="text" class="form-control"
                               placeholder="از نوع عددی"
                               name="inp-edit-product-priority"
                               value="<?= $validator->setInput('inp-edit-product-priority') ?: $attrCategory['priority']; ?>">
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