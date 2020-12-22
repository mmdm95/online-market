<!-- Content area -->
<div class="content">
    <div class="card col-lg-12">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن مطلب جدید']); ?>

        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <fieldset class="col-6">
                                <legend class="font-weight-semibold">
                                    <i class="icon-info22 mr-2"></i>
                                    انتخاب تصویر مطلب
                                </legend>
                                <div class="img-placeholder-group">
                                    <div class="img-placeholder-custom">
                                        <div class="img-placeholder-icon-container">
                                            <i class="img-placeholder-icon icon-image2 text-indigo"></i>
                                            <span class="img-placeholder-num badge badge-pill bg-warning-400"><i
                                                        class="icon-plus2"></i></span>
                                        </div>
                                        <div>
                                            <input class="file-manager-image" type="hidden" name="" value="">
                                            <!-- <img src="" alt="image" class="img-placeholder-image"> -->
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="col-6">
                                <legend class="font-weight-semibold">
                                    <i class="icon-info22 mr-2"></i>
                                    وضعیت نمایش مطلب
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
                            <div class="form-group col-6">
                                <label>عنوان فارسی مطلب:</label>
                                <input type="text" class="form-control" placeholder="وارد کنید">
                            </div>
                            <div class="form-group col-6">
                                <label>دسته‌بندی مطلب:</label>
                                <select data-placeholder="دسته‌بندی اصلی را انتخاب کنید ..."
                                        class="form-control form-control-select2" data-fouc>
                                    <option></option>
                                    <option value="Cambodia">عادی</option>
                                    <option value="Cameroon">مدیر</option>
                                    <option value="Canada">مدیر اصلی</option>
                                </select>
                            </div>
                            <div class="form-group row col-12">
                                <label class="col-form-label col-lg-12">خلاصه مطلب:</label>
                                <div class="col-lg-12">
                                    <textarea rows="3" cols="3" class="form-control" placeholder="متن را اینجا وارد کنید ..."></textarea>
                                </div>
                            </div>
                            <div class="form-group row col-12">
                                <input type="text" value="Amsterdam,Washington,Sydney,Beijing" class="form-control tags-input" data-fouc>
                            </div>
                        </div>
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

