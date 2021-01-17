<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'سؤالات امنیتی']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده سؤالات امنیتی کنید.</span>
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal_form_add_sec_question">
                    افزودن سؤال جدید
                    <i class="icon-question3 ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"question"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.sec_question.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>سؤال</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>سؤال</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Add security question modal -->
    <div id="modal_form_add_sec_question" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن واحد جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_add_sec_question">
                    <div class="modal-body">
                        <div class="form-group text-right">
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    نمایش سؤال
                                    <input type="checkbox" class="form-check-input-switchery"
                                           checked name="inp-add-sec-question-status">
                                    عدم نمایش سؤال
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                سؤال امنیتی:
                            </label>
                            <input type="text"
                                   placeholder="متن سؤال را وارد کنید"
                                   class="form-control"
                                   name="inp-add-sec-question-q">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن سؤال امنیتی</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add security question modal -->

    <!-- Edit security question modal -->
    <div id="modal_form_edit_sec_question" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">ویرایش واحد جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_edit_sec_question">
                    <div class="modal-body">
                        <div class="form-group text-right">
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    نمایش سؤال
                                    <input type="checkbox" class="form-check-input-switchery"
                                           checked name="inp-edit-sec-question-status">
                                    عدم نمایش سؤال
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                سؤال امنیتی:
                            </label>
                            <input type="text"
                                   placeholder="متن سؤال را وارد کنید"
                                   class="form-control"
                                   name="inp-edit-sec-question-q">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ویرایش سؤال امنیتی</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /edit security question modal -->
</div>
