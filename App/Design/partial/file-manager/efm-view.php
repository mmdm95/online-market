<div class="row">
    <?php if (isset($the_options) && isset($the_options['allow_upload']) && $the_options['allow_upload']): ?>
        <div class="form-group col-12">
            <div id="file_drop_target">
                <div class="my-2">
                    <input name="recommendations" type="file" class="d-none" multiple
                           id="file-uploader">
                    <label for="file-uploader" class="ml-5"
                           style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                        فایلی انتخاب نشده است
                    </label>
                    <label for="file-uploader" class="action btn bg-pink ml-2"
                           style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                        انتخاب فایل
                    </label>
                </div>
                <strong class="d-block my-3">یا</strong>
                <span class="d-block my-2">فایل را کشیده و اینجا رها کنید</span>
            </div>

            <div id="upload_progress"></div>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <div class="row flex-row-reverse">
            <div class="form-group col-lg-6 order-2">
                <label for="dirsearch" class="d-block">
                    جستجو در پوشه فعلی:
                </label>
                <div class="form-group">
                    <div class="form-group-feedback form-group-feedback-left">
                        <input id="dirsearch" class="form-control" type="text"
                               value="" placeholder="جستجو">
                        <div class="form-control-feedback form-control-feedback-lg">
                            <i class="icon-search4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($the_options['allow_create_folder']): ?>
                <div class="form-group col-lg-6 order-1">
                    <form action="?" method="post" id="mkdir">
                        <label for="dirname" class="d-block">
                            ساخت پوشه جدید:
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="form-group-feedback form-group-feedback-left">
                                    <input id="dirname" class="form-control" type="text" name="name"
                                           value="" placeholder="نام لاتین پوشه را وارد کنید">
                                    <div class="form-control-feedback form-control-feedback-lg">
                                        <i class="icon-folder text-muted"></i>
                                    </div>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-icon">
                                        ساخت پوشه
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12 order-3 mb-1">
        <div id="breadcrumb"></div>
    </div>
</div>

<div class="table-responsive">
    <table id="table" class="table text-left">
        <thead class="bg-green-600 border-radius">
        <tr>
            <th class="sort_desc">نام</th>
            <th>اندازه</th>
            <th>تاریخ ایجاد</th>
            <th>دسترسی ها</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody id="list">
        </tbody>
    </table>
</div>