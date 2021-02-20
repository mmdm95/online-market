<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-9">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن دسته‌بندی جدید']); ?>

        <div class="card-body">
            <form action="<?= url('admin.category.add')->getRelativeUrlTrimmed(); ?>" method="post"
                  id="__form_add_category">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $cat_add_errors ?? [],
                    'success' => $cat_add_success ?? '',
                    'warning' => $cat_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <fieldset class="col-12">
                        <legend class="font-weight-semibold">
                            <i class="icon-info22 mr-2"></i>
                            وضعیت نمایش دسته‌بندی
                        </legend>
                        <div class="form-group col-12 text-left">
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    نمایش
                                    <input type="checkbox" class="form-check-input-switchery"
                                           name="inp-add-category-status"
                                        <?= $validator->setCheckbox('inp-add-category-status', 'on', true); ?>>
                                    عدم نمایش
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group col-lg-5">
                        <label>
                            <span class="text-danger">*</span>
                            نام دسته‌بندی:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید"
                               name="inp-add-category-name"
                               value="<?= $validator->setInput('inp-add-category-name'); ?>">
                    </div>
                    <div class="form-group col-lg-4">
                        <label>
                            <span class="text-danger">*</span>
                            دسته‌بندی والد:
                        </label>
                        <select data-placeholder="دسته‌بندی را انتخاب کنید..."
                                class="form-control form-control-select2-searchable"
                                name="inp-add-category-parent" data-fouc>
                            <option value="<?= DEFAULT_OPTION_VALUE; ?>" selected="selected">دسته والد</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id']; ?>"
                                    <?= $validator->setSelect('inp-add-category-parent', $category['id']); ?>>
                                    <?php for ($i = 0; $i < $category['level']; ++$i): ?>
                                        -
                                    <?php endfor; ?>
                                    <?= $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label>اولویت دسته:</label>
                        <div class="d-flex">
                            <input type="number" min="0"
                                   class="form-control"
                                   placeholder="از نوع عددی"
                                   name="inp-add-category-priority"
                                   value="<?= $validator->setInput('inp-add-category-priority'); ?>">
                            <button type="button"
                                    class="btn btn-outline-success btn-icon ml-2 icon-info3"
                                    data-popup="popover"
                                    data-trigger="focus" data-placement="right"
                                    data-content="اولویت، محل نمایش دسته را در منو مشخص خواهد کرد."></button>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>کلمات کلیدی:</label>
                        <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                               name="inp-add-category-keywords"
                               value="<?= $validator->setInput('inp-add-category-keywords'); ?>">
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

