<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش دسته‌بندی']); ?>

        <div class="card-body">
            <form action="<?= url('admin.blog.category.add')->getRelativeUrl() . $category['id']; ?>" method="post"
                  id="__form_edit_blog_category">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $blog_category_edit_errors ?? [],
                    'success' => $blog_category_edit_success ?? '',
                    'warning' => $blog_category_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <fieldset class="col-12">
                        <legend class="font-weight-semibold">
                            <i class="icon-info22 mr-2"></i>
                            وضعیت نمایش دسته‌بندی
                        </legend>
                        <div class="form-group col-12 text-right">
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    نمایش
                                    <input type="checkbox" class="form-check-input-switchery"
                                           name="inp-edit-blog-category-status"
                                        <?= $validator->setCheckbox('inp-edit-blog-category-status', 'on') ?: (is_value_checked($category['publish']) ? 'checked="checked"' : ''); ?>>
                                    عدم نمایش
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group col-12">
                        <label>
                            <span class="text-danger">*</span>
                            نام دسته‌بندی:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید"
                               name="inp-edit-blog-category-name"
                               value="<?= $validator->setInput('inp-edit-blog-category-name') ?: $category['name']; ?>">
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

