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
