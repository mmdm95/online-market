<!-- Content area -->
<div class="content">
    <div class="card col-lg-9">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش کوپن تخفیف']); ?>

        <div class="card-body">
            <form action="<?= url('admin.coupon.edit')->getRelativeUrl() . $coupon['id']; ?>" method="post"
                  id="__form_edit_coupon">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $coupon_edit_errors ?? [],
                    'success' => $coupon_edit_success ?? '',
                    'warning' => $coupon_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <fieldset>
                            <legend class="font-weight-semibold">
                                <i class="icon-user mr-2"></i>
                                وضعیت‌ها
                            </legend>
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    امکان دسترسی
                                    <input type="checkbox" class="form-check-input-switchery"
                                           name="inp-edit-coupon-status"
                                        <?= $validator->setCheckbox('inp-edit-coupon-status', 'on') ?: (is_value_checked($coupon['publish']) ? 'checked="checked"' : ''); ?>
                                           data-fouc>
                                    عدم امکان دسترسی
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>
                                    <span class="text-danger">*</span>
                                    کد کوپن تخفیف:
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="ترکیبی از حروف انگلیسی و اعداد"
                                       name="inp-edit-coupon-code"
                                       value="<?= $validator->setInput('inp-edit-coupon-code') ?: $coupon['code']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>
                                    <span class="text-danger">*</span>
                                    عنوان کوپن تخفیف:
                                </label>
                                <input type="text" class="form-control" placeholder="مثال: کوپن عیدانه"
                                       name="inp-edit-coupon-title"
                                       value="<?= $validator->setInput('inp-edit-coupon-title') ?: $coupon['title']; ?>">
                            </div>
                            <div class="form-group col-xl-4">
                                <label>
                                    <span class="text-danger">*</span>
                                    قیمت تخفیف:
                                </label>
                                <input type="text" class="form-control" placeholder="تومان"
                                       name="inp-edit-coupon-price"
                                       value="<?= $validator->setInput('inp-edit-coupon-price') ?: $coupon['price']; ?>">
                            </div>
                            <div class="form-group col-xl-4">
                                <label>حداقل مبلغ فاکتور برای استفاده از کد تخفیف:</label>
                                <input type="text" class="form-control" placeholder="تومان"
                                       name="inp-edit-coupon-min-price"
                                       value="<?= $validator->setInput('inp-edit-coupon-code') ?: $coupon['min_price']; ?>">
                            </div>
                            <div class="form-group col-xl-4">
                                <label>حداکثر مبلغ فاکتور برای استفاده از کد تخفیف:</label>
                                <input type="text" class="form-control" placeholder="تومان"
                                       name="inp-edit-coupon-max-price"
                                       value="<?= $validator->setInput('inp-edit-coupon-max-price') ?: $coupon['max_price']; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-xl-3">
                                <label>تعداد قابل استفاده کوپن:</label>
                                <input type="text" class="form-control" placeholder="عدد"
                                       name="inp-edit-coupon-count"
                                       value="<?= $validator->setInput('inp-edit-coupon-count') ?: $coupon['use_count']; ?>">
                            </div>
                            <div class="form-group col-xl-3">
                                <label>قابلیت استفاده مجدد کوپن بعد از:</label>
                                <input type="text" class="form-control" placeholder="تعداد روز"
                                       name="inp-edit-coupon-use-after"
                                       value="<?= $validator->setInput('inp-edit-coupon-use-after') ?: $coupon['reusable_after']; ?>">
                            </div>
                            <div class="form-group col-xl-3">
                                <label>تاریخ شروع استفاده:</label>
                                <?php
                                $sd = $validator->setInput('inp-edit-coupon-start-date', '') ?: ($coupon['start_at'] ?: '');
                                if(!empty($sd)) {
                                    $sd = date('Y/m/d H:i', (int)$sd);
                                }
                                ?>
                                <input type="hidden" name="inp-edit-coupon-start-date"
                                       id="altStartDate">
                                <div class="d-flex">
                                    <input type="text" class="form-control range-from"
                                           placeholder="انتخاب تاریخ" readonly data-ignored
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
                            <div class="form-group col-xl-3">
                                <label>تاریخ پایان استفاده:</label>
                                <?php
                                $ed = $validator->setInput('inp-edit-coupon-end-date', '') ?: ($coupon['expire_at'] ?: '');
                                if(!empty($ed)) {
                                    $ed = date('Y/m/d H:i', (int)$ed);
                                }
                                ?>
                                <input type="hidden" name="inp-edit-coupon-end-date"
                                       id="altEndDate" value="<?= $ed; ?>">
                                <div class="d-flex">
                                    <input type="text" class="form-control range-to"
                                           placeholder="انتخاب تاریخ" readonly data-ignored
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