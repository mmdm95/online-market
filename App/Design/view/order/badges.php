<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'وضعیت سفارشات']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده وضعیت‌ها کنید.</span>
                <button type="button"
                        class="btn btn-success ml-3"
                        data-toggle="modal"
                        data-target="#modal_form_add_badges">
                    افزودن وضعیت جدید
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"title"},{"data":"color"},{"data":"allow_return"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.badge.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>عنوان وضعیت</th>
                <th>رنگ</th>
                <th>امکان مرجوعی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>عنوان وضعیت</th>
                <th>رنگ</th>
                <th>امکان مرجوعی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Add badge modal -->
    <div id="modal_form_add_badges" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن وضعیت جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="#" id="__form_add_badge">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    عنوان وضعیت:
                                </label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-add-badge-title">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="d-block">
                                    <span class="text-danger">*</span>
                                    انتخاب رنگ:
                                </label>
                                <div class="d-inline-block">
                                    <input type="text" class="form-control colorpicker-show-input"
                                           name="inp-add-badge-color"
                                           data-fouc>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="d-block">
                                    امکان مرجوع کردن کالا در این مرحله:
                                </label>
                                <div class="d-inline-block">
                                    <div class="form-group col-md-6">
                                        <div class="form-check form-check-switchery form-check-switchery-double mt-2">
                                            <label class="form-check-label">
                                                بله
                                                <input type="checkbox" class="form-check-input-switchery"
                                                       name="inp-add-badge-allow-return" checked="checked">
                                                خیر
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن وضعیت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add badge modal -->

    <!-- Edit badge modal -->
    <div id="modal_form_edit_badges" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">افزودن وضعیت</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_edit_badge">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    عنوان وضعیت:
                                </label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-edit-badge-title">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="d-block">
                                    <span class="text-danger">*</span>
                                    انتخاب رنگ:
                                </label>
                                <div class="d-inline-block">
                                    <input type="text" class="form-control colorpicker-show-input"
                                           name="inp-edit-badge-color"
                                           data-fouc>
                                </div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label class="d-block">
                                    امکان مرجوع کردن کالا در این مرحله:
                                </label>
                                <div class="d-inline-block">
                                    <div class="form-group col-md-6">
                                        <div class="form-check form-check-switchery form-check-switchery-double mt-2">
                                            <label class="form-check-label">
                                                بله
                                                <input type="checkbox" class="form-check-input-switchery"
                                                       name="inp-edit-badge-allow-return" checked="checked">
                                                خیر
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ویرایش وضعیت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /edit badge modal -->
</div>
