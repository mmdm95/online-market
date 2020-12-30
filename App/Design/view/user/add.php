<?php
$validator = form_validator();
?>

<!-- Content area -->
<div class="content">

    <!-- Fieldset legend -->
    <div class="card col-lg-9">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن کاربر']); ?>

        <div class="card-body">
            <form action="<?= url('admin.user.add')->getRelativeUrlTrimmed(); ?>" method="post">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $user_add_errors ?? [],
                    'success' => $user_add_success ?? '',
                    'warning' => $user_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-lg-12 mb-5">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-user mr-2"></i>
                                وضعیت‌ها
                            </legend>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-check form-check-switchery form-check-switchery-double">
                                        <label class="form-check-label">
                                            فعال
                                            <input type="checkbox" class="form-check-input-switchery"
                                                   name="inp-user-active-status"
                                                <?= $validator->setCheckbox('inp-user-active-status', 'on', true); ?>>
                                            غیرفعال
                                        </label>
                                    </div>
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
                                <div class="form-group col-lg-12">
                                    <label>
                                        <i class="text-danger">*</i>
                                        موبایل:
                                    </label>
                                    <div class="d-flex">
                                        <input type="text" required class="form-control"
                                               placeholder="11 رقمی"
                                               name="inp-user-mobile"
                                               value="<?= $validator->setInput('inp-user-mobile'); ?>">
                                        <button type="button"
                                                class="btn btn-outline-success btn-icon ml-2 icon icon-info3"
                                                data-popup="popover"
                                                data-trigger="focus" data-placement="right"
                                                data-content="موبایل به عنوان نام کاربری خواهد بود."></button>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>
                                        <i class="text-danger">*</i>
                                        کلمه عبور:
                                    </label>
                                    <div class="d-flex">
                                        <input type="password" required class="form-control"
                                               name="inp-user-password"
                                               placeholder="حداقل ۸ کاراکتر و شامل یک حرف">
                                        <button type="button"
                                                class="btn btn-outline-success btn-icon ml-2 icon icon-info3"
                                                data-popup="popover"
                                                data-trigger="focus" data-placement="right"
                                                data-content="حداقل ۸ کاراکتر و شامل یک حرف"></button>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>
                                        <i class="text-danger">*</i>
                                        تکرار کلمه عبور:
                                    </label>
                                    <input type="password" required class="form-control" placeholder="تکرار کلمه عبور"
                                           name="inp-user-re-password">
                                </div>
                                <div class="form-group col-lg-12">
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
                                                <?= $validator->setSelect('inp-user-role', $role['name']); ?>>
                                                <?= $role['description']; ?>
                                                <?php if (DB_YES == $role['is_admin']): ?>
                                                    (دسترسی به پنل ادمین)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-lg-12">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>
                                اطلاعات شخصی (اختیاری)
                            </legend>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>نام:</label>
                                    <input type="text" class="form-control" placeholder="فقط حروف فارسی"
                                           name="inp-user-first-name"
                                           value="<?= $validator->setInput('inp-user-first-name'); ?>">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>نام خانوادگی:</label>
                                    <input type="text" class="form-control" placeholder="فقط حروف فارسی"
                                           name="inp-user-last-name"
                                           value="<?= $validator->setInput('inp-user-last-name'); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>پست الکترونیکی:</label>
                                        <input type="text" placeholder="example@mail.com" class="form-control"
                                               name="inp-user-email"
                                               value="<?= $validator->setInput('inp-user-email'); ?>">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
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