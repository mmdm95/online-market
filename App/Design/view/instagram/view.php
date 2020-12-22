<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'برندها']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
            <div class="col-md-2 float-right">
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal_form_add_brand">
                    افزودن برند جدید
                    <i class="icon-markup ml-2"></i>
                </button>
            </div>
            <!-- Vertical form modal -->
            <div id="modal_form_add_brand" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">افزودن برند جدید</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form action="#">
                            <div class="modal-body">
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
                                    <fieldset class="col-6 rtl">
                                        <legend class="font-weight-semibold">
                                            <i class="icon-info22 mr-2"></i>
                                            وضعیت بزند
                                        </legend>
                                        <div class="form-group col-12 text-right">
                                            <div class="form-check form-check-switchery form-check-switchery-double">
                                                <label class="form-check-label">
                                                    انتشار
                                                    <input type="checkbox" class="form-check-input-switchery" checked>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 text-right">
                                            <div class="form-check form-check-switchery form-check-switchery-double">
                                                <label class="form-check-label">
                                                    نمایش اسلایدر
                                                    <input type="checkbox" class="form-check-input-switchery" checked>
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>نام فارسی برند</label>
                                            <input type="text" placeholder="وارد کنید" class="form-control">
                                        </div>

                                        <div class="col-sm-6">
                                            <label>نام لاتین برند</label>
                                            <input type="text" placeholder="وارد کنید" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                                <button type="submit" class="btn bg-primary">افزودن</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /vertical form modal -->
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر برند</th>
                <th>نام فارسی</th>
                <th>نام انگلیسی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>
                    <img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" width="36" height="36" alt="">
                </td>
                <td>هوآوی</td>
                <td>Huawei</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#modal_form_edit_unit" data-toggle="modal" class="dropdown-item">
                                    <i class="icon-pencil"></i>ویرایش</a>
                                <a href="#" class="dropdown-item"><i class="icon-trash"></i>حذف</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Vertical form modal -->
        <div id="modal_form_edit_unit" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ویرایش واحد جدید</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>نام واحد</label>
                                        <input type="text" placeholder="وارد کنید" class="form-control">
                                    </div>

                                    <div class="col-sm-6">
                                        <label>علامت اختصاری(انگلیسی)</label>
                                        <input type="text" placeholder="وارد کنید" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn bg-primary">افزودن</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /vertical form modal -->
    </div>
</div>
