<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده کوپن‌های تخفیف']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده کوپن‌ها کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"code"},{"data":"title"},{"data":"price"},{"data":"min_price"},{"data":"max_price"},{"data":"start_date"},{"data":"end_date"},{"data":"used_from_whole"},{"data":"reusable_after"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.coupon.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>عنوان کوپن</th>
                <th>کد کوپن</th>
                <th>قیمت کوپن</th>
                <th>تاریخ شروع</th>
                <th>تاریخ پایان</th>
                <th>مبلغ حداقل فاکتور</th>
                <th>مبلغ حداکثر فاکتور</th>
                <th>تعداد استفاده شده / کل</th>
                <th>قابل استفاده بعد از</th>
                <th>وضعیت دسترسی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>عنوان کوپن</th>
                <th>کد کوپن</th>
                <th>قیمت کوپن</th>
                <th>تاریخ شروع</th>
                <th>تاریخ پایان</th>
                <th>مبلغ حداقل فاکتور</th>
                <th>مبلغ حداکثر فاکتور</th>
                <th>تعداد استفاده شده / کل</th>
                <th>قابل استفاده بعد از</th>
                <th>وضعیت دسترسی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
