<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن دسته‌بندی']); ?>

        <div class="card-body">
            <form action="<?= url('admin.blog.category.add')->getRelativeUrlTrimmed(); ?>" method="post"
                  id="__form_add_blog_category">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $blog_category_add_errors ?? [],
                    'success' => $blog_category_add_success ?? '',
                    'warning' => $blog_category_add_warning ?? '',
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
                                           name="inp-add-blog-category-status"
                                        <?= $validator->setCheckbox('inp-add-blog-category-status', 'on', true); ?>>
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
                               name="inp-add-blog-category-name"
                               value="<?= $validator->setInput('inp-add-blog-category-name'); ?>">
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

