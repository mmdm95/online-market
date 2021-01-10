<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'برندها']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش تصاویر کنید.</span>
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal_form_add_ins_image">
                    افزودن تصویر جدید
                    <i class="icon-markup ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"image"},{"data":"link"},{"data":"created_at"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.instagram.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>لینک</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>لینک</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Add image modal -->
    <div id="modal_form_add_ins_image" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن تصویر جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_add_instagram_image">
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
                                        <input type="hidden" name="inp-add-ins-img" value="">
                                        <div class="img-placeholder-icon-container">
                                            <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                            <div class="img-placeholder-num bg-warning text-white">
                                                <i class="icon-plus2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    لینک:
                                </label>
                                <input type="text" placeholder="https://" class="form-control ltr"
                                       name="inp-add-ins-link">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن تصویر</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add image modal -->

    <!-- Edit image modal -->
    <div id="modal_form_edit_ins_image" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">ویرایش تصویر</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_edit_instagram_image">
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
                                        <input type="hidden" name="inp-edit-ins-img" value="">
                                        <div class="img-placeholder-icon-container">
                                            <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                            <div class="img-placeholder-num bg-warning text-white">
                                                <i class="icon-plus2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    لینک:
                                </label>
                                <input type="text" placeholder="https://" class="form-control ltr"
                                       name="inp-edit-ins-link">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">افزودن تصویر</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /edit image modal -->

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->
</div>
