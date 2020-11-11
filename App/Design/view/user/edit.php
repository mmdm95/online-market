<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <?php load_partial('admin/main-sidebar'); ?>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4><i class="icon-arrow-right6 mr-2"></i> <span class="font-weight-semibold">
                            مدیریت کاربران</span>

                    </h4>
                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="<?= url('admin.index'); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                            خانه</a>
                        <a href="<?= url('admin.user.view'); ?>" class="breadcrumb-item">کاربران</a>
                        <span class="breadcrumb-item active">ویرایش کاربر</span>
                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>
            </div>
        </div>
        <!-- /page header -->
        <!-- Content area -->
        <div class="content">

            <!-- Fieldset legend -->
            <!-- 2 columns form -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">ویرایش کاربر</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

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
                            <div class="col-md-6">
                                <fieldset>
                                    <legend class="font-weight-semibold"><i class="icon-user mr-2"></i>
                                        اطلاعات کاربری
                                    </legend>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <i class="icon icon-info3 text-info" data-toggle="popover"
                                               title="موبایل به عنوان نام کاربری خواهد بود."></i>
                                            <label>موبایل:</label>
                                            <input type="text" class="form-control" placeholder="11 رقمی">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>نقش کاربر:</label>
                                                <select data-placeholder="نقش کاربر در سایت"
                                                        class="form-control form-control-select2" data-fouc>
                                                    <option></option>
                                                    <option value="Cambodia">عادی</option>
                                                    <option value="Cameroon">مدیر</option>
                                                    <option value="Canada">مدیر اصلی</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <i class="icon icon-info3 text-info" data-toggle="popover"
                                               title="حداقل ۸ کاراکتر و شامل یک حرف"></i>
                                            <label>رمز عبور:</label>
                                            <input type="password" class="form-control"
                                                   placeholder="حداقل ۸ کاراکتر و شامل یک حرف">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>تکرار رمز عبور:</label>
                                            <input type="password" class="form-control" placeholder="تکرار رمز عبور">
                                        </div>

                                    </div>

                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>
                                        اطلاعات شخصی
                                    </legend>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>نام:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>نام خانوادگی:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید">
                                        </div>
                                        <div class="col-md-6">
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
                            <button type="submit" class="btn btn-primary">ذخیره اطلاعات <i
                                        class="icon-floppy-disks ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /2 columns form -->
        </div>
        <!-- /content area -->
        <!-- Footer -->
        <?php load_partial('admin/footer'); ?>
        <!-- /footer -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->

