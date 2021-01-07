<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مدیریت اسلایدر']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده اسلایدها کنید.</span>

                <button type="button"
                        class="btn btn-success"
                        data-toggle="modal"
                        data-target="#modal_form_add_slide">
                    افزودن اسلاید جدید
                    <i class="icon-image2 ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"image"},{"data":"link"},{"data":"title"},{"data":"sub_title"},{"data":"priority"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.slider.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>لینک</th>
                <th>عنوان</th>
                <th>زیر عنوان</th>
                <th>اولویت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>لینک</th>
                <th>عنوان</th>
                <th>زیر عنوان</th>
                <th>اولویت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Add slide modal -->
    <div id="modal_form_add_slide" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن اسلاید جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_add_slide">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="text-center">
                                    <label>
                                        <span class="text-danger">*</span>
                                        انتخاب تصویر:
                                    </label>
                                    <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto"
                                         data-toggle="modal"
                                         data-target="#modal_efm">
                                        <input type="hidden" name="inp-add-slide-img" value="">
                                        <div class="img-placeholder-icon-container">
                                            <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                            <div class="img-placeholder-num bg-warning text-white">
                                                <i class="icon-plus2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>عنوان:</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-add-slide-title">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>زیر عنوان:</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-add-slide-sub-title">
                            </div>
                            <div class="col-lg-8 form-group">
                                <label>لینک:</label>
                                <input type="text" placeholder="http://" class="form-control ltr"
                                       name="inp-add-slide-link">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>اولویت:</label>
                                <input type="number" placeholder="از نوع عددی" class="form-control"
                                       name="inp-add-slide-priority">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن اسلاید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add slide modal -->

    <!-- Edit slide modal -->
    <div id="modal_form_edit_slide" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">ویرایش اسلاید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_edit_slide">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="text-center">
                                    <label>
                                        <span class="text-danger">*</span>
                                        انتخاب تصویر:
                                    </label>
                                    <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto"
                                         data-toggle="modal"
                                         data-target="#modal_efm">
                                        <input type="hidden" name="inp-edit-slide-img" value="">
                                        <div class="img-placeholder-icon-container">
                                            <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                            <div class="img-placeholder-num bg-warning text-white">
                                                <i class="icon-plus2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>عنوان:</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-edit-slide-title">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>زیر عنوان:</label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-edit-slide-sub-title">
                            </div>
                            <div class="col-lg-8 form-group">
                                <label>لینک:</label>
                                <input type="text" placeholder="http://" class="form-control ltr"
                                       name="inp-edit-slide-link">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>اولویت:</label>
                                <input type="number" placeholder="از نوع عددی" class="form-control"
                                       name="inp-edit-slide-priority">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ویرایش اسلاید</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- /edit slide modal -->

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->
</div>
