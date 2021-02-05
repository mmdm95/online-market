<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'خریداران محصولات']); ?>

        <div class="card-body">
            <p>
                این موارد ممکن است شامل کاربرانی که وجود ندارند نیز باشد.
                <small class="text-warning">
                    (اگر کاربر وجود نداشته باشد، مشاهده جزئیات در ستون عملیات برای او غیر فعال است.)
                </small>
            </p>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"first_name"},{"data":"last_name"},{"data":"mobile"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.product.dt.buyer.users')->getRelativeUrl() . $product_id; ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>موبایل</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>موبایل</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'سفارشات شامل محصولات']); ?>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"code"},{"data":"province"},{"data":"city"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.product.dt.buyer.orders')->getRelativeUrl() . $product_id; ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>کد سفارش</th>
                <th>شهر</th>
                <th>استان</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>کد سفارش</th>
                <th>شهر</th>
                <th>استان</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
