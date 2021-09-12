<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست صفحات']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span class="mb-2 mb-lg-0">با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش صفحات ثابت کنید.</span>

                <div class="ml-0 ml-lg-3 d-block d-lg-flex">
                    <a href="<?= url('admin.static.page.add'); ?>"
                       class="btn bg-primary mb-2 mb-sm-0 d-block d-sm-inline-block">
                        افزودن صفحه ثابت جدید
                        <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"title"},{"data":"writer"},{"data":"created_at"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.static.page.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>عنوان مطلب</th>
                <th>نویسنده</th>
                <th>اضافه شده در تاریخ</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>عنوان مطلب</th>
                <th>نویسنده</th>
                <th>اضافه شده در تاریخ</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
