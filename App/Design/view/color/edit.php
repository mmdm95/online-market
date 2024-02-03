<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <!-- 2 columns form -->
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش رنگ']); ?>

        <div class="card-body">
            <div class="alert alert-primary">
                <ul class="m-0">
                    <li>
                        برای نمایش رنگ و نام آن، گزینه
                        <span class="badge badge-dark">نمایش در صفحه جزئیات</span>
                        را فعال نمایید.
                    </li>
                    <li>
                        برای اینکه تنها نام رنگ نمایش داده شود، گزینه
                        <span class="badge badge-dark">رنگ طرحدار می‌باشد</span>
                        را فعال نمایید.
                    </li>
                    <li>
                        برای عدم نمایش رنگ و نام آن، گزینه‌های
                        <span class="badge badge-dark">نمایش در صفحه جزئیات</span>
                        و
                        <span class="badge badge-dark">رنگ طرحدار می‌باشد</span>
                        را غیر فعال نمایید.
                    </li>
                </ul>
            </div>
        </div>

        <div class="card-body">
            <form action="<?= url('admin.color.edit')->getRelativeUrlTrimmed(); ?>" id="__form_edit_color"
                  method="post">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $color_edit_errors ?? [],
                    'success' => $color_edit_success ?? '',
                    'warning' => $color_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>نام رنگ:</label>
                        <input type="text" class="form-control" placeholder="وارد کنید"
                               name="inp-edit-color-name"
                               value="<?= $validator->setInput('inp-edit-color-name') ?: $color['name']; ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mr-1">انتخاب رنگ:</label>
                        <?php
                        $c = $validator->setInput('inp-edit-color-color') ?: $color['hex'];
                        ?>
                        <input type="text" class="form-control colorpicker-show-input"
                               name="inp-edit-color-color"
                               data-color="<?= $c; ?>"
                               value="<?= $c; ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <div class="form-check form-check-switchery form-check-switchery-double mt-2">
                            <label class="form-check-label">
                                نمایش
                                <input type="checkbox" class="form-check-input-switchery"
                                       name="inp-edit-color-status"
                                    <?= $validator->setCheckbox('inp-edit-color-status', 'on') ?: (is_value_checked($color['publish']) ? 'checked="checked"' : ''); ?>>
                                عدم نمایش
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="form-check form-check-switchery form-check-switchery-double mt-2">
                            <label class="form-check-label">
                                نمایش در صفحه جزئیات
                                <input type="checkbox" class="form-check-input-switchery"
                                       name="inp-edit-color-show-color"
                                    <?= $validator->setCheckbox('inp-edit-color-show-color', 'on') ?: (is_value_checked($color['show_color']) ? 'checked="checked"' : ''); ?>>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="form-check form-check-switchery form-check-switchery-double mt-2">
                            <label class="form-check-label">
                                رنگ طرحدار می‌باشد
                                <input type="checkbox" class="form-check-input-switchery"
                                       name="inp-edit-color-is-patterned-color"
                                    <?= $validator->setCheckbox('inp-edit-color-is-patterned-color', 'on') ?: (is_value_checked($color['is_patterned_color']) ? 'checked="checked"' : ''); ?>>
                            </label>
                        </div>
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
    <!-- /2 columns form -->
</div>
<!-- /content area -->