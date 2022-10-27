<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مقادیر ویژگی‌های جستجو']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span class="mb-2 mb-lg-0">با استفاده از ستون عملیات می‌توانید اقدام به حذف و تغییر مقادیر ویژگی‌ها نمایید.</span>
                <button type="button" class="btn btn-success ml-3" data-toggle="modal"
                        data-target="#modal_form_add_attr_val">
                    افزودن مقدار به ویژگی‌ها
                    <i class="icon-list-numbered ml-2"></i>
                </button>
            </div>
        </div>

        <input type="hidden" value="<?= $attrId; ?>" name="inp-h-attr-id">

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"attr_name"},{"data":"attr_val"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.product.attr.value.dt.view', ['a_id' => $attrId])->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>ویژگی</th>
                <th>مقدار</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>ویژگی</th>
                <th>مقدار</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->



    <!-- Add attribute value modal -->
    <div id="modal_form_add_attr_val" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن مقدار به ویژگی</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_add_attr_val">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-9 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    مقدار ویژگی:
                                </label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-add-product-attr-val">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن مقدار</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add attribute value modal -->

    <!-- Edit attribute value modal -->
    <div id="modal_form_edit_attr_val" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">ویرایش مقدار ویژگی</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="#" id="__form_edit_attr_val">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-9 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    مقدار ویژگی:
                                </label>
                                <input type="text" placeholder="وارد کنید" class="form-control"
                                       name="inp-edit-product-attr-val">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ویرایش مقدار</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /edit attribute value modal -->
</div>
<!-- /content area -->
