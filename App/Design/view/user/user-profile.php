<!-- Content area -->
<div class="content">

    <!-- 2 columns form -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده کاربر']); ?>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <fieldset>
                        <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>
                            اطلاعات شخصی
                        </legend>

                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <img class="img-user-profile"
                                             src="<?= asset_path('image/avatars/' . $user['image'], false); ?>"
                                             alt="تصویر کاربری">
                                    </div>
                                    <div class="ml-3">
                                        <div>
                                            <?php if (!empty($user['first_name']) || !empty($user['last_name'])): ?>
                                                <h6 class="text-orange d-inline-block">
                                                    <?= trim($user['first_name'] . ' ' . $user['last_name']); ?>
                                                </h6>
                                                -
                                            <?php endif; ?>
                                            <span class="text-green-800"><?= local_number($user['username']); ?></span>
                                        </div>
                                        <div class="text-grey">
                                            <?= implode(', ', $user['roles']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label>پست الکترونیکی:</label>
                                <input type="text" placeholder="" class="form-control" readonly
                                       value="<?= $user['email']; ?>">
                            </div>
                            <div class="col-lg-12 form-group">
                                <label>شماره شبا:</label>
                                <input type="text" placeholder="" class="form-control" readonly
                                       value="<?= $user['shaba_number']; ?>">
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-lg-6">
                    <input type="hidden" value="<?= $user['id']; ?>" data-user-id="true">
                    <fieldset>
                        <legend class="font-weight-semibold pt-0"><i class="icon-user mr-2"></i>
                            اطلاعات کاربر
                        </legend>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <div class="form-check form-check-switchery form-check-switchery-double">
                                    <label class="d-block">
                                        وضعیت کاربر:
                                    </label>
                                    <label class="form-check-label">
                                        فعال
                                        <input type="checkbox" class="form-check-input-switchery" disabled
                                            <?= is_value_checked($user['is_activated']) ? 'checked="checked"' : ''; ?>>
                                        غیرفعال
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <div class="form-check form-check-switchery form-check-switchery-double">
                                    <label class="d-block">
                                        وضعیت ورود:
                                    </label>
                                    <label class="form-check-label">
                                        باز
                                        <input type="checkbox" class="form-check-input-switchery" disabled
                                            <?= !is_value_checked($user['is_login_locked']) ? 'checked="checked"' : ''; ?>>
                                        بسته
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <div class="form-check form-check-switchery form-check-switchery-double">
                                    <label class="d-block">
                                        وضعیت فعالیت:
                                    </label>
                                    <label class="form-check-label">
                                        اجازه دارد
                                        <input type="checkbox" class="form-check-input-switchery" disabled
                                            <?= !is_value_checked($user['ban']) ? 'checked="checked"' : ''; ?>>
                                        منع
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <label for="banDesc" class="d-block">
                                    علت منع فعالیت:
                                </label>
                                <textarea class="form-control form-control-min-height"
                                          readonly
                                          placeholder=""
                                          id="banDesc"
                                          cols="30"
                                          rows="10"><?= $user['ban_desc']; ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <!-- /2 columns form -->

    <!-- Highlighting rows and columns -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">
                آدرس‌های کاربر
            </h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش آدرس‌های کاربر کنید.</span>
                <button type="button"
                        class="btn btn-danger ml-3"
                        data-toggle="modal"
                        data-target="#modal_form_address_add">
                    افزودن آدرس
                    <i class="icon-truck ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight" id="__datatable_addr_view"
               data-columns='[{"data":"id"}, {"data":"full_name"}, {"data":"province"}, {"data":"city"}, {"data":"postal_code"}, {"data":"mobile"}, {"data":"address"}, {"data":"operations"}]'
               data-ajax-url="<?= url('admin.addr.dt.view', ['user_id' => $user['id']])->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام گیرنده</th>
                <th>استان</th>
                <th>شهر</th>
                <th>کدپستی</th>
                <th>موبایل</th>
                <th>آدرس</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام گیرنده</th>
                <th>استان</th>
                <th>شهر</th>
                <th>کدپستی</th>
                <th>موبایل</th>
                <th>آدرس</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <?php load_partial('admin/table/user-orders', ['user_id' => $user['id']]); ?>

    <!-- Add address modal -->
    <div id="modal_form_address_add" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن آدرس جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_add_address">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>نام و نام خانوادگی گیرنده:</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-add-address-full-name">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>موبایل گیرنده:</label>
                                <input type="text" placeholder="09xxxxxxxxx"
                                       class="form-control"
                                       name="inp-add-address-mobile">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <label>استان:</label>
                                <select data-placeholder="استان را انتخاب کنید."
                                        class="form-control form-control-select2-searchable city-loader-select"
                                        data-city-select-target="#addAddressCitySelect"
                                        name="inp-add-address-province"
                                        data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>" disabled selected>انتخاب کنید</option>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label>شهر:</label>
                                <select data-placeholder="شهر را انتخاب کنید."
                                        id="addAddressCitySelect"
                                        name="inp-add-address-city"
                                        class="form-control form-control-select2-searchable"
                                        data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>" disabled selected>انتخاب کنید</option>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label>کد پستی:</label>
                                <input type="text" placeholder="xxxxxxxxxx" class="form-control"
                                       maxlength="10"
                                       name="inp-add-address-postal-code">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <label>آدرس پستی:</label>
                                <input type="text"
                                       placeholder="آدرس کامل پستی را در اینجا وارد کنید"
                                       class="form-control"
                                       name="inp-add-address-addr">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن آدرس</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add address modal -->

    <!-- Edit address modal -->
    <div id="modal_form_address_edit" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">ویرایش آدرس</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_edit_address">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>نام و نام خانوادگی گیرنده:</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-edit-address-full-name">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>موبایل گیرنده:</label>
                                <input type="text" placeholder="09xxxxxxxxx"
                                       class="form-control"
                                       name="inp-edit-address-mobile">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <label>استان:</label>
                                <select data-placeholder="استان را انتخاب کنید."
                                        class="form-control form-control-select2-searchable city-loader-select"
                                        data-city-select-target="#editAddressCitySelect"
                                        name="inp-edit-address-province"
                                        data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>" disabled selected>انتخاب کنید</option>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label>شهر:</label>
                                <select data-placeholder="شهر را انتخاب کنید."
                                        id="editAddressCitySelect"
                                        name="inp-edit-address-city"
                                        class="form-control form-control-select2-searchable" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>" disabled selected>انتخاب کنید</option>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label>کد پستی:</label>
                                <input type="text" placeholder="xxxxxxxxxx" class="form-control"
                                       maxlength="10"
                                       name="inp-edit-address-postal-code">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <label>آدرس پستی:</label>
                                <input type="text"
                                       placeholder="آدرس کامل پستی را در اینجا وارد کنید"
                                       class="form-control"
                                       name="inp-edit-address-addr">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ویرایش آدرس</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /edit address  modal -->

</div>
<!-- /content area -->