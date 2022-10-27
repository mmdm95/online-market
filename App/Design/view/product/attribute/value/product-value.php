<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش ویژگی‌های جستجوی محصول']); ?>

        <div class="card-body">
            <?php if (!count($attr_values)): ?>
                <div class="d-flex justify-content-between flex-lg-row flex-column">
                    <span class="mb-2 h6 mb-lg-0 text-warning">هیچ ویژگی‌ای برای دسته‌بندی این محصول تعریف نشده است.</span>

                    <div class="ml-0 ml-lg-3 d-block d-lg-flex">
                        <a href="<?= url('admin.product.attr.add'); ?>"
                           class="btn bg-primary mb-2 mb-sm-0 d-block d-sm-inline-block">
                            افزودن ویژگی جستجوی جدید
                            <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <form action="<?= url('admin.product.value.edit')->getRelativeUrlTrimmed(); ?>"
                      method="post" id="__form_edit_product_attr_val">
                    <?php load_partial('admin/message/message-form', [
                        'errors' => $product_value_edit_errors ?? [],
                        'success' => $product_value_edit_success ?? '',
                        'warning' => $product_value_edit_warning ?? '',
                    ]); ?>

                    <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                    <div class="row">
                        <?php foreach ($attr_values as $aId => $attr): ?>
                            <div class="form-group col-lg-6">
                                <label class="d-block">
                                    <?= $attr['title']; ?>
                                    :
                                </label>
                                <select data-placeholder="<?= $attr['title'] ?> را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-edit-attr_<?= $aId; ?>" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($attr['values'] as $id => $value): ?>
                                        <option value="<?= $id; ?>"
                                            <?= isset($product_attr_values[$aId][$id]) ? ($product_attr_values[$aId][$id] == $value ? 'selected="selected"' : '') : ''; ?>>
                                            <?= $value; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-info">
                            ذخیره اطلاعات
                            <i class="icon-floppy-disks ml-2"></i>
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- /content area -->