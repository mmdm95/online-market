<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'تخصیص ویژگی به دسته‌بندی']); ?>

        <div class="card-body">
            <form action="<?= url('admin.product.attr.category.add')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_add_product_attr">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $product_attr_cat_add_errors ?? [],
                    'success' => $product_attr_cat_add_success ?? '',
                    'warning' => $product_attr_cat_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            ویژگی:
                        </label>
                        <select data-placeholder="ویژگی را انتخاب کنید..." required="required"
                                class="form-control form-control-select2-searchable"
                                name="inp-add-product-attr-id" data-fouc>
                            <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                    disabled="disabled"
                                    selected="selected">
                                انتخاب کنید
                            </option>
                            <?php foreach ($attrs as $attr): ?>
                                <option value="<?= $attr['id']; ?>"
                                    <?= $validator->setSelect('inp-add-product-attr-id', $attr['id']); ?>>
                                    <?= $attr['title']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            دسته‌بندی:
                        </label>
                        <select data-placeholder="دسته‌بندی را انتخاب کنید..." required="required"
                                class="form-control form-control-select2-searchable"
                                name="inp-add-product-cat-id" data-fouc>
                            <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                    disabled="disabled"
                                    selected="selected">
                                انتخاب کنید
                            </option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id']; ?>"
                                    <?= $validator->setSelect('inp-add-product-cat-id', $category['id']); ?>>
                                    <?= $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
</div>
<!-- /content area -->