<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست جشنواره ها']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"title"},{"data":"start_date"},{"data":"end_date"},{"data":"status"},{"data":"main_status"},{"data":"created_at"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.festival.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام جشنواره</th>
                <th>تاریخ شروع</th>
                <th>تاریخ پایان</th>
                <th>وضعیت جشنواره</th>
                <th>جشنواره اصلی</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام جشنواره</th>
                <th>تاریخ شروع</th>
                <th>تاریخ پایان</th>
                <th>وضعیت جشنواره</th>
                <th>جشنواره اصلی</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
