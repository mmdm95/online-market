<!-- Content area -->
<div class="content">
    <!-- Fieldset legend -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن کاربر']); ?>

        <div class="card-body">
            <form action="#">
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
                                            <input type="checkbox" class="form-check-input-switchery" checked
                                                   data-fouc>
                                            غیرفعال
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-6">
                        <fieldset>
                            <legend class="font-weight-semibold">
                                <i class="icon-user mr-2"></i>
                                اطلاعات کاربری
                            </legend>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <i class="icon icon-info3 text-info" data-toggle="popover"
                                       title="موبایل به عنوان نام کاربری خواهد بود."></i>
                                    <label>موبایل:</label>
                                    <input type="text" class="form-control" placeholder="11 رقمی">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>نقش کاربر:</label>
                                    <select data-placeholder="نقش کاربر در سایت"
                                            class="form-control form-control-select2" data-fouc>
                                        <option></option>
                                        <option value="Cambodia">عادی</option>
                                        <option value="Cameroon">مدیر</option>
                                        <option value="Canada">مدیر اصلی</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <i class="icon icon-info3 text-info" data-toggle="popover"
                                       title="حداقل ۸ کاراکتر و شامل یک حرف"></i>
                                    <label>رمز عبور:</label>
                                    <input type="password" class="form-control"
                                           placeholder="حداقل ۸ کاراکتر و شامل یک حرف">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>تکرار رمز عبور:</label>
                                    <input type="password" class="form-control" placeholder="تکرار رمز عبور">
                                </div>

                            </div>

                        </fieldset>
                    </div>
                    <div class="col-lg-6">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>
                                اطلاعات شخصی
                            </legend>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>نام:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>نام خانوادگی:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید">
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>پست الکترونیکی:</label>
                                        <input type="text" placeholder="example@mail.com" class="form-control">
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