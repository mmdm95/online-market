<!-- Highlighting rows and columns -->
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">
            سفارشات کاربر
        </h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
    </div>

    <table class="table table-bordered table-hover datatable-highlight"
           data-columns='[{"data":"code"}, {"data":"name"}, {"data":"mobile"}, {"data":"place"}, {"data":"pay_status"}, {"data":"price"}, {"data":"send_status"}, {"data":"order_date"}, {"data":"operations"}]'
           data-ajax-url="<?= url('admin.user.order.dt.view', ['user_id' => $user_id])->getRelativeUrlTrimmed(); ?>">
        <thead>
        <tr>
            <th>کد سفارش</th>
            <th>نام گیرنده</th>
            <th>شماره تماس گیرنده</th>
            <th>محل ارسال</th>
            <th>وضعیت پرداخت</th>
            <th>قیمت(تومان)</th>
            <th>وضعیت ارسال</th>
            <th>تاریخ سفارش</th>
            <th class="text-center">عملیات</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>کد سفارش</th>
            <th>نام گیرنده</th>
            <th>شماره تماس گیرنده</th>
            <th>محل ارسال</th>
            <th>وضعیت پرداخت</th>
            <th>قیمت(تومان)</th>
            <th>وضعیت ارسال</th>
            <th>تاریخ سفارش</th>
            <th class="text-center">عملیات</th>
        </tr>
        </tfoot>
    </table>
</div>
<!-- /highlighting rows and columns -->