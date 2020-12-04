<!-- Content area -->
<div class="content">
    <div class="card col-lg-8">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش جشنواره']); ?>

        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
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
                                                <input type="checkbox" class="form-check-input-switchery" checked>
                                                غیرفعال
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group col-6">
                                    <label>نام جشنواره:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید">
                                </div>
                                <div class="form-group col-6">
                                    <label>کوپن جشنواره:</label>
                                    <input type="text" class="form-control" placeholder="به طور مثال: nowrooz1400">
                                </div>
                                <div class="form-group col-6">
                                    <label>تاریخ شروع:</label>
                                    <input type="text" class="form-control persian-calender" placeholder="تاریخ شمسی" readonly="readonly">
                                </div>
                                <div class="form-group col-6">
                                    <label>تاریخ پایان جشنواره:</label>
                                    <input type="text" class="form-control persian-calender" placeholder="تاریخ شمسی" readonly="readonly">
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

