<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن جشنواره جدید']); ?>

        <div class="card-body">
            <form action="<?= url('admin.festival.add')->getRelativeUrlTrimmed(); ?>" method="post"
                  id="__form_add_festival">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $festival_add_errors ?? [],
                    'success' => $festival_add_success ?? '',
                    'warning' => $festival_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <fieldset class="col-12">
                                <legend class="font-weight-semibold">
                                    <i class="icon-info22 mr-2"></i>
                                    وضعیت جشنواره
                                </legend>
                                <div class="form-group col-12 text-right">
                                    <div class="form-check form-check-switchery form-check-switchery-double">
                                        <label class="form-check-label">
                                            فعال
                                            <input type="checkbox" class="form-check-input-switchery"
                                                   name="inp-add-festival-status"
                                                <?= $validator->setCheckbox('inp-add-festival-status', 'on', true); ?>
                                                   data-fouc>
                                            غیرفعال
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group col-6">
                                <label>نام جشنواره:</label>
                                <input type="text" class="form-control" placeholder="وارد کنید">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label>تاریخ شروع:</label>
                        <input type="text" class="form-control persian-calender" placeholder="تاریخ شمسی"
                               readonly="readonly">
                    </div>
                    <div class="form-group col-6">
                        <label>تاریخ پایان جشنواره:</label>
                        <input type="text" class="form-control persian-calender" placeholder="تاریخ شمسی"
                               readonly="readonly">
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

