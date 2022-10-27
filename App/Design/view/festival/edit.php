<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش جشنواره']); ?>

        <div class="card-body">
            <form action="<?= url('admin.festival.edit', ['id' => $festival['id']])->getRelativeUrlTrimmed(); ?>"
                  method="post"
                  id="__form_edit_festival">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $festival_edit_errors ?? [],
                    'success' => $festival_edit_success ?? '',
                    'warning' => $festival_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <fieldset class="col-lg-12">
                                <legend class="font-weight-semibold">
                                    <i class="icon-info22 mr-2"></i>
                                    وضعیت جشنواره
                                </legend>
                                <div class="form-group text-right">
                                    <div class="form-check form-check-switchery form-check-switchery-double">
                                        <label class="form-check-label">
                                            فعال
                                            <input type="checkbox" class="form-check-input-switchery"
                                                   name="inp-edit-festival-status"
                                                <?= $validator->setCheckbox('inp-edit-festival-status', 'on') ?: (is_value_checked($festival['title']) ? 'checked="checked"' : ''); ?>
                                                   data-fouc>
                                            غیرفعال
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group col-lg-6">
                                <label>
                                    <span class="text-danger">*</span>
                                    نام جشنواره:
                                </label>
                                <input type="text" class="form-control" placeholder="وارد کنید"
                                       name="inp-edit-festival-title"
                                       value="<?= $validator->setInput('inp-edit-festival-title') ?: $festival['title']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            تاریخ شروع جشنواره:
                        </label>
                        <?php
                        $sd = $validator->setInput('inp-edit-festival-start-date', '') ?: ($festival['start_at'] ?: '');
                        if (!empty($sd)) {
                            $sd = date('Y/m/d H:i', (int)$sd);
                        }
                        ?>
                        <input type="hidden" name="inp-edit-festival-start-date"
                               id="altStartDate">
                        <div class="d-flex">
                            <input type="text" class="form-control range-from"
                                   placeholder="انتخاب تاریخ" readonly data-ignored
                                   data-time="true"
                                   data-alt-field="#altStartDate"
                                   value="<?= $sd; ?>">
                            <button type="button"
                                    class="btn btn-outline-danger btn-icon ml-2 icon-cross2 date_cleaner"
                                    data-date-clean-element='data-alt-field="#altStartDate"'
                                    data-popup="tooltip"
                                    data-placement="right"
                                    data-original-title="حذف تاریخ"></button>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            تاریخ پایان جشنواره:
                        </label>
                        <?php
                        $ed = $validator->setInput('inp-edit-festival-end-date', '') ?: ($festival['expire_at'] ?: '');
                        if (!empty($ed)) {
                            $ed = date('Y/m/d H:i', (int)$ed);
                        }
                        ?>
                        <input type="hidden" name="inp-edit-festival-end-date"
                               id="altEndDate">
                        <div class="d-flex">
                            <input type="text" class="form-control range-to"
                                   placeholder="انتخاب تاریخ" readonly data-ignored
                                   data-time="true"
                                   data-alt-field="#altEndDate"
                                   value="<?= $ed; ?>">
                            <button type="button"
                                    class="btn btn-outline-danger btn-icon ml-2 icon-cross2 date_cleaner"
                                    data-date-clean-element='data-alt-field="#altEndDate"'
                                    data-popup="tooltip"
                                    data-placement="right"
                                    data-original-title="حذف تاریخ"></button>
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
</div>
<!-- /content area -->

