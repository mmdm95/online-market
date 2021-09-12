<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'محصولات']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به مشاهده محصولات کنید.</span>
                <button type="button"
                        class="btn bg-success-700 ml-3" id="excelExport">
                    خروجی اکسل
                    <i class="icon-file-excel ml-2"></i>
                </button>
            </div>
        </div>

        <?php if (count($query_builder ?? [])): ?>
            <div class="card-body border-bottom">
                <div id="builder-basic-product" class="mb-3"></div>
                <div class="text-right">
                    <button id="btn-filter-product" class="btn btn-primary mr-2">
                        <i class="icon-filter3 mr-2" aria-hidden="true"></i>
                        فیلتر اطلاعات
                    </button>
                    <button id="btn-reset-product" class="btn btn-warning">
                        <i class="icon-trash mr-2" aria-hidden="true"></i>
                        پاک کردن
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"title"},{"data":"image"},{"data":"brand_name"},{"data":"category_name"},{"data":"in_stock"},{"data":"status"},{"data":"is_available"},{"data":"created_at"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.report.products.dt')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
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

    <script>
        (function ($) {
            'use strict';

            window.report_variable_filters = JSON.parse('<?= json_encode($query_builder ?? []); ?>');
        })(jQuery);
    </script>
</div>
<!-- /content area -->
