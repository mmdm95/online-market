<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <!-- 2 columns form -->
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن رنگ جدید']); ?>

        <div class="card-body">
            <form action="<?= url('admin.color.add')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_add_color">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $color_add_errors ?? [],
                    'success' => $color_add_success ?? '',
                    'warning' => $color_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>نام رنگ:</label>
                        <input type="text" class="form-control" placeholder="وارد کنید"
                               name="inp-add-color-name"
                               value="<?= $validator->setInput('inp-add-color-name'); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mr-1">انتخاب رنگ:</label>
                        <?php
                        $c = $validator->setInput('inp-add-color-color');
                        ?>
                        <input type="text" class="form-control colorpicker-show-input"
                               name="inp-add-color-color"
                               data-color="<?= $c; ?>"
                               value="<?= $c; ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <div class="form-check form-check-switchery form-check-switchery-double mt-2">
                            <label class="form-check-label">
                                نمایش
                                <input type="checkbox" class="form-check-input-switchery"
                                       name="inp-add-color-status"
                                    <?= $validator->setCheckbox('inp-add-color-status', 'on', true); ?>>
                                عدم نمایش
                            </label>
                        </div>
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
    <!-- /2 columns form -->
</div>
<!-- /content area -->