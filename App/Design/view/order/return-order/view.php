<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده سفارشات']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"user"},{"data":"code"},{"data":"count"},{"data":"status"},{"data":"order_date"},{"data":"req_date"},{"data":"status_changed_by"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.return.order.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>کاربر مرجوع کننده</th>
                <th>کد سفارش</th>
                <th>تعداد آیتم مرجوعی</th>
                <th>وضعیت مرجوع</th>
                <th>تاریخ سفارش</th>
                <th>تاریخ درخواست مرجوعی</th>
                <th>تغییر وضعیت توسط</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>کاربر مرجوع کننده</th>
                <th>کد سفارش</th>
                <th>تعداد آیتم مرجوعی</th>
                <th>وضعیت مرجوع</th>
                <th>تاریخ سفارش</th>
                <th>تاریخ درخواست مرجوعی</th>
                <th>تغییر وضعیت توسط</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
