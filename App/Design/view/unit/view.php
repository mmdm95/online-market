<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'واحدها']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده واحد مورد نظر کنید.</span>
                <button type="button"
                        class="btn btn-success ml-3"
                        data-toggle="modal"
                        data-target="#modal_form_add_unit">
                    افزودن واحد جدید
                    <i class="icon-unicode ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"}, {"data":"name"}, {"data":"abbr"}, {"data":"operations"}]'
               data-ajax-url="<?= url('admin.unit.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>علامت اختصاری(انگلیسی)</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>علامت اختصاری(انگلیسی)</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Add unit modal -->
    <div id="modal_form_add_unit" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن واحد جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_add_unit">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>نام واحد</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-add-unit-title">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>علامت اختصاری(انگلیسی)</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-add-unit-sign">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn bg-primary">افزودن واحد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add unit modal -->

    <!-- Edit unit modal -->
    <div id="modal_form_edit_unit" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">ویرایش واحد</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_edit_unit">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>نام واحد</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-edit-unit-title">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>علامت اختصاری(انگلیسی)</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-edit-unit-sign">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn bg-success">ویرایش واحد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /edit unit modal -->
</div>
