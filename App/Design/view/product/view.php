<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'محصولات']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده محصولات کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"title"},{"data":"image"},{"data":"brand_name"},{"data":"category_name"},{"data":"in_stock"},{"data":"status"},{"data":"is_available"},{"data":"created_at"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.product.dt.view')->getRelativeUrlTrimmed(); ?>">
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

</div>
<!-- /content area -->
