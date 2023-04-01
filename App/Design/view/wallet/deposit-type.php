<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'انواع تراکنش‌های کیف پول']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش انواع تراکنش‌ها کنید.</span>

                <button type="button"
                        class="btn btn-success ml-3"
                        data-toggle="modal"
                        data-target="#modal_form_add_type">
                    افزودن نوع تراکنش جدید
                    <i class="icon-plus2 ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"title"},{"data":"description"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.deposit-type.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>توضیح</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>توضیح</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>

        <!-- Add type modal -->
        <div id="modal_form_add_type" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">افزودن نوع تراکنش</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form action="#" id="__form_add_deposit_type">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>
                                        <span class="text-danger">*</span>
                                        عنوان:
                                    </label>
                                    <input type="text" placeholder="وارد کنید" class="form-control"
                                           name="inp-add-deposit-type-title">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>
                                        <span class="text-danger">*</span>
                                        توضیح:
                                    </label>
                                    <input type="text" placeholder="وارد کنید" class="form-control"
                                           name="inp-add-deposit-type-desc">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn btn-primary">افزودن نوع تراکنش</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /add type modal -->

        <!-- Edit type modal -->
        <div id="modal_form_edit_type" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title">ویرایش نوع تراکنش</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form action="#" id="__form_edit_deposit_type">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>
                                        <span class="text-danger">*</span>
                                        عنوان:
                                    </label>
                                    <input type="text" placeholder="وارد کنید" class="form-control"
                                           name="inp-edit-deposit-type-title">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>
                                        <span class="text-danger">*</span>
                                        توضیح:
                                    </label>
                                    <input type="text" placeholder="وارد کنید" class="form-control"
                                           name="inp-edit-deposit-type-desc">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn btn-success">ویرایش نوع تراکنش</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /edit type modal -->
    </div>
</div>
