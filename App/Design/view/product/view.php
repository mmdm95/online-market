<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'محصولات']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span class="mb-2 mb-lg-0">با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده محصولات کنید.</span>

                <div class="ml-0 ml-lg-3 d-block d-lg-flex">
                    <a href="<?= url('admin.product.add'); ?>"
                       class="btn bg-primary mb-2 mb-sm-0 d-block d-sm-inline-block">
                        افزودن محصول جدید
                        <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-column-defs='[{"searchable": false, "orderable": false, "targets": 0}]'
               data-order='[[1, "desc"]]'
               data-columns='[{"data":"chkId"},{"data":"id"},{"data":"title"},{"data":"image"},{"data":"brand_name"},{"data":"category_name"},{"data":"in_stock"},{"data":"status"},{"data":"is_available"},{"data":"created_at"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.product.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>
                    <div class="form-check form-check-inline" id="SelectAllItems">
                        <label class="form-check-label">
                            <input type="checkbox"
                                   class="form-check-input-styled"
                                   data-fouc>
                        </label>
                    </div>
                </th>
                <th>#</th>
                <th>عنوان</th>
                <th>تصویر</th>
                <th>برند</th>
                <th>دسته‌بندی</th>
                <th>تعداد موجود</th>
                <th>وضعیت نمایش</th>
                <th>وضعیت موجودی</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th></th>
                <th>#</th>
                <th>عنوان</th>
                <th>تصویر</th>
                <th>برند</th>
                <th>دسته‌بندی</th>
                <th>تعداد موجود</th>
                <th>وضعیت نمایش</th>
                <th>وضعیت موجودی</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Clickable menu -->
    <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right" data-fab-toggle="click" id="tableFabMenu">
        <li>
            <a href="javascript:void(0);" class="fab-menu-btn btn btn-dark btn-float rounded-round btn-icon">
                <i class="fab-icon-open icon-menu"></i>
                <i class="fab-icon-close icon-cross2"></i>
            </a>

            <ul class="fab-menu-inner">
                <li>
                    <div class="fab-label-light" data-fab-label="تغییر دسته‌جمعی مشخصات">
                        <a href="javascript:void(0);" class="btn bg-white text-warning rounded-round btn-icon btn-float"
                           id="productBatchEdit"
                           data-submit-url="<?= url('admin.product.batch-edit')->getRelativeUrlTrimmed(); ?>">
                            <i class="icon-pencil3"></i>
                        </a>
                        <span class="badge bg-success-600 shadow-2 selItemsCount">0</span>
                    </div>
                </li>
                <li>
                    <div class="fab-label-light" data-fab-label="تغییر دسته‌جمعی قیمت">
                        <a href="javascript:void(0);" class="btn bg-white text-purple rounded-round btn-icon btn-float"
                           id="productBatchEditPrice"
                           data-submit-url="<?= url('admin.product.batch-edit-price')->getRelativeUrlTrimmed(); ?>">
                            <i class="icon-chart"></i>
                        </a>
                        <span class="badge bg-success-600 shadow-2 selItemsCount">0</span>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
    <!-- /clickable menu -->

</div>
<!-- /content area -->
