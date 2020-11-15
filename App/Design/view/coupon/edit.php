<!-- Content area -->
<div class="content">

    <!-- Fieldset legend -->
    <!-- 2 columns form -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش کوپن تخفیف']); ?>

        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-user mr-2"></i>
                                وضعیت‌ها
                            </legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check form-check-switchery form-check-switchery-double">
                                        <label class="form-check-label">
                                            نمایش
                                            <input type="checkbox" class="form-check-input-switchery" checked
                                                   data-fouc>
                                            عدم نمایش
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <div class="row">
                                <div class="form-group col-md-4">

                                    <label>کد کوپن تخفیف:</label>
                                    <input type="text" class="form-control" placeholder="ترکیبی از حروف انگلیسی و اعداد">
                                </div>
                                <div class="form-group col-md-4">

                                    <label>عنوان کوپن تخفیف:</label>
                                    <input type="text" class="form-control" placeholder="کوپن عیدانه">
                                </div>
                                <div class="form-group col-md-4">

                                    <label>تعداد قابل استفاده کوپن:</label>
                                    <input type="text" class="form-control" placeholder="عدد">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>حداقل مبلغ فاکتور برای استفاده از کد تخفیف:</label>
                                    <input type="text" class="form-control" placeholder="تومان">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>حداکثر مبلغ فاکتور برای استفاده از کد تخفیف:</label>
                                    <input type="text" class="form-control" placeholder="تومان">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>تاریخ شروع استفاده:</label>
                                    <input type="text" class="form-control" placeholder="کوپن عیدانه">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>تاریخ پایان استفاده:</label>
                                    <input type="text" class="form-control" placeholder="کوپن عیدانه">
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">ذخیره اطلاعات <i
                                class="icon-floppy-disks ml-2"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- /2 columns form -->
</div>
<!-- /content area -->