<!-- Content area -->
<div class="content">
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش دسته‌بندی']); ?>

        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
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
                                                <input type="checkbox" class="form-check-input-switchery" checked>
                                                عدم نمایش
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group col-12">
                                    <label>نام دسته‌بندی:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید">
                                </div>
                                <div class="form-group col-12">
                                    <label>دسته‌بندی والد:</label>
                                    <select data-placeholder="دسته‌بندی اصلی را انتخاب کنید ..."
                                            class="form-control form-control-select2" data-fouc>
                                        <option></option>
                                        <option value="Cambodia">عادی</option>
                                        <option value="Cameroon">مدیر</option>
                                        <option value="Canada">مدیر اصلی</option>
                                    </select>
                                </div>
                                <div class="form-group col-12">
                                    <label>اولویت دسته:</label>
                                    <div class="d-flex">
                                        <input type="password" class="form-control"
                                               placeholder="حداقل ۸ کاراکتر و شامل حروف و اعداد">
                                        <button type="button"
                                                class="btn btn-outline-success btn-icon ml-2 icon icon-info3"
                                                data-popup="popover"
                                                data-trigger="focus" data-placement="right"
                                                data-content="اولویت، محل نمایش دسته را در منو مشخص خواهد کرد."></button>
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

