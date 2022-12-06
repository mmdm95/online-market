<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">

    <!-- Fieldset legend -->
    <div class="card col-lg-9">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش کاربر']); ?>

        <div class="card-body">
            <form action="<?= url('admin.user.edit', ['id' => $user['id']])->getRelativeUrlTrimmed(); ?>" method="post">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $user_edit_errors ?? [],
                    'success' => $user_edit_success ?? '',
                    'warning' => $user_edit_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-user mr-2"></i>
                                وضعیت‌ها
                            </legend>
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <div class="form-check form-check-switchery form-check-switchery-double">
                                        <label class="d-block">
                                            وضعیت کاربر:
                                        </label>
                                        <label class="form-check-label">
                                            فعال
                                            <input type="checkbox" class="form-check-input-switchery"
                                                   name="inp-user-active-status"
                                                <?= $validator->setCheckbox('inp-user-active-status', 'on') ?: (is_value_checked($user['is_activated']) ? 'checked="checked"' : ''); ?>>
                                            غیرفعال
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <div class="form-check form-check-switchery form-check-switchery-double">
                                        <label class="d-block">
                                            وضعیت ورود:
                                        </label>
                                        <label class="form-check-label">
                                            باز کردن
                                            <input type="checkbox" class="form-check-input-switchery"
                                                   name="inp-user-active-status"
                                                <?= $validator->setCheckbox('inp-user-login-status', 'on') ?: (!is_value_checked($user['is_login_locked']) ? 'checked="checked"' : ''); ?>>
                                            بستن
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <div class="form-check form-check-switchery form-check-switchery-double">
                                        <label class="d-block">
                                            وضعیت فعالیت:
                                        </label>
                                        <label class="form-check-label">
                                            اجازه دادن
                                            <input type="checkbox" class="form-check-input-switchery"
                                                   name="inp-user-active-status"
                                                <?= $validator->setCheckbox('inp-user-ban-status', 'on') ?: (!is_value_checked($user['ban']) ? 'checked="checked"' : ''); ?>>
                                            منع
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label for="banDesc" class="d-block">
                                        علت منع فعالیت:
                                    </label>
                                    <textarea class="form-control form-control-min-height"
                                              name="inp-user-ban-desc"
                                              placeholder="توضیح دهید"
                                              id="banDesc"
                                              cols="30"
                                              rows="10"><?= $validator->setInput('inp-user-ban-desc') ?: $user['ban_desc']; ?></textarea>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-12">
                        <fieldset>
                            <legend class="font-weight-semibold">
                                <i class="icon-user mr-2"></i>
                                اطلاعات کاربری
                            </legend>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>
                                        <i class="text-danger">*</i>
                                        موبایل:
                                    </label>
                                    <div class="d-flex">
                                        <input type="text" required class="form-control"
                                               placeholder="11 رقمی"
                                               name="inp-user-mobile"
                                               value="<?= $validator->setInput('inp-user-mobile') ?: $user['username']; ?>">
                                        <button type="button"
                                                class="btn btn-outline-success btn-icon ml-2 icon-info3"
                                                data-popup="popover"
                                                data-trigger="focus" data-placement="right"
                                                data-content="موبایل به عنوان نام کاربری خواهد بود."></button>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="user_role">
                                        <i class="text-danger">*</i>
                                        نقش کاربر:
                                    </label>
                                    <select id="user_role" required
                                            data-placeholder="نقش کاربر در سایت"
                                            name="inp-user-role[]"
                                            class="form-control form-control-select2" multiple="multiple">
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role['name']; ?>"
                                                <?= in_array($role['name'], $user['roles']) ? 'selected="selected"' : ''; ?>>
                                                <?= $role['description']; ?>
                                                <?php if (DB_YES == $role['is_admin']): ?>
                                                    (دسترسی به پنل ادمین)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-12">
                                    <div class="form-check">
                                        <label class="form-check-label"
                                               data-toggle="collapse" href="#passwordContainer"
                                               role="button" aria-expanded="false"
                                               aria-controls="passwordContainer">
                                            <input type="checkbox" name="inp-user-change-password"
                                                   class="form-input-styled">
                                            تغییر کلمه عبور
                                        </label>
                                    </div>

                                    <div class="col-lg-12 border p-3 rounded form-group collapse"
                                         id="passwordContainer">
                                        <div class="row">
                                            <div class="col-12">
                                                <?php load_partial('admin/message/message-info', [
                                                    'info' => 'برای عدم تغییر کلمه عبور، فیلدهای زیر را خالی بگذارید.',
                                                    'dismissible' => false,
                                                ]); ?>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>
                                                    کلمه عبور:
                                                </label>
                                                <div class="d-flex">
                                                    <input type="password" class="form-control"
                                                           name="inp-user-password"
                                                           placeholder="حداقل ۸ کاراکتر و شامل یک حرف">
                                                    <button type="button"
                                                            class="btn btn-outline-success btn-icon ml-2 icon-info3"
                                                            data-popup="popover"
                                                            data-trigger="focus" data-placement="right"
                                                            data-content="حداقل ۸ کاراکتر و شامل یک حرف"></button>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>
                                                    تکرار کلمه عبور:
                                                </label>
                                                <input type="password" class="form-control"
                                                       placeholder="تکرار کلمه عبور"
                                                       name="inp-user-re-password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-lg-12">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>
                                اطلاعات شخصی
                            </legend>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>
                                        <i class="text-danger">*</i>
                                        نام:
                                    </label>
                                    <input type="text" class="form-control" placeholder="فقط حروف فارسی"
                                           name="inp-user-first-name"
                                           value="<?= $validator->setInput('inp-user-first-name') ?: $user['first_name']; ?>">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>
                                        <i class="text-danger">*</i>
                                        نام خانوادگی:
                                    </label>
                                    <input type="text" class="form-control" placeholder="فقط حروف فارسی"
                                           name="inp-user-last-name"
                                           value="<?= $validator->setInput('inp-user-last-name') ?: $user['last_name']; ?>">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>
                                        <i class="text-danger">*</i>
                                        شماره شناسنامه:
                                    </label>
                                    <input type="text" class="form-control" placeholder="از نوع عددی"
                                           name="inp-user-national-num"
                                           value="<?= $validator->setInput('inp-user-national-num') ?: $user['national_number']; ?>">
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>پست الکترونیکی:</label>
                                        <input type="text" placeholder="example@mail.com" class="form-control"
                                               name="inp-user-email"
                                               value="<?= $validator->setInput('inp-user-email') ?: $user['email']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>شماره شبا:</label>
                                        <input type="text" placeholder="ir xxxx xxxx xxxx xxxx xxxx xxxx"
                                               class="form-control"
                                               name="inp-user-shaba" maxlength="50"
                                               value="<?= $validator->setInput('inp-user-shaba') ?: $user['shaba_number']; ?>">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
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

