<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'سؤالات متداول']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش سؤالات کنید.</span>
                <button type="button"
                        class="btn btn-success ml-3"
                        data-toggle="modal"
                        data-target="#modal_form_add_faq">
                    افزودن سؤال جدید
                    <i class="icon-question7 ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"question"},{"data":"answer"},{"data":"tags"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.faq.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>صورت سؤال</th>
                <th>پاسخ سؤال</th>
                <th>برچسب‌ها</th>
                <th>وضعیت نمایش</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>صورت سؤال</th>
                <th>پاسخ سؤال</th>
                <th>برچسب‌ها</th>
                <th>وضعیت نمایش</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Add faq modal -->
    <div id="modal_form_add_faq" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن سؤال جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="#" id="__form_add_faq">
                    <div class="modal-body">
                        <div class="form-group text-right">
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    نمایش سؤال
                                    <input type="checkbox" class="form-check-input-switchery"
                                           checked name="inp-add-faq-status">
                                    عدم نمایش سؤال
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                سؤال:
                            </label>
                            <input type="text"
                                   placeholder="متن سؤال را وارد کنید"
                                   class="form-control"
                                   name="inp-add-faq-q">
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                برچسب‌ها:
                            </label>
                            <input type="text"
                                   placeholder="برچسب‌های مربوط به سؤال"
                                   class="form-control tags-input"
                                   name="inp-add-faq-tags">
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                پاسخ:
                            </label>
                            <textarea name="inp-add-faq-a"
                                      cols="30"
                                      rows="10"
                                      placeholder="پاسخ سؤال را وارد کنید"
                                      class="form-control cntEditor"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن سؤال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add faq modal -->

    <!-- Edit faq modal -->
    <div id="modal_form_edit_faq" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">ویرایش واحد</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="#" id="__form_edit_faq">
                    <div class="modal-body">
                        <div class="form-group text-right">
                            <div class="form-check form-check-switchery form-check-switchery-double">
                                <label class="form-check-label">
                                    نمایش سؤال
                                    <input type="checkbox" class="form-check-input-switchery"
                                           name="inp-edit-faq-status">
                                    عدم نمایش سؤال
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                سؤال:
                            </label>
                            <input type="text"
                                   placeholder="متن سؤال را وارد کنید"
                                   class="form-control"
                                   name="inp-edit-faq-q">
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                برچسب‌ها:
                            </label>
                            <input type="text"
                                   placeholder="برچسب‌های مربوط به سؤال"
                                   class="form-control tags-input"
                                   name="inp-edit-faq-tags">
                        </div>
                        <div class="form-group">
                            <label>
                                <span class="text-danger">*</span>
                                پاسخ:
                            </label>
                            <textarea name="inp-edit-faq-a"
                                      id="inpEditFaqAEditor"
                                      cols="30"
                                      rows="10"
                                      placeholder="پاسخ سؤال را وارد کنید"
                                      class="form-control cntEditor"
                            ></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn btn-success">ویرایش سؤال</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /edit faq modal -->

    <?php load_partial('editor/browser-tiny-func'); ?>
</div>
