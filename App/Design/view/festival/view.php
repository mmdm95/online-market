<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست جشنواره‌ها']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span class="mb-2 mb-lg-0">با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده جشنواره‌ها کنید.</span>

                <div class="ml-0 ml-lg-3 d-block d-lg-flex">
                    <a href="<?= url('admin.festival.add'); ?>"
                       class="btn bg-primary mb-2 mb-sm-0 d-block d-sm-inline-block">
                        افزودن جشنواره جدید
                        <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
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
