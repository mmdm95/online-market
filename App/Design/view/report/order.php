<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده سفارشات']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به مشاهده خرید‌های کاربر کنید.</span>
                <button type="button"
                        class="btn bg-success-700 ml-3" id="excelExport">
                    خروجی اکسل
                    <i class="icon-file-excel ml-2"></i>
                </button>
            </div>
        </div>

        <?php if (count($query_builder ?? [])): ?>
            <div class="card-body border-bottom">
                <div id="builder-basic-order" class="mb-3"></div>
                <div class="text-right">
                    <button id="btn-filter-order" class="btn btn-primary mr-2">
                        <i class="icon-filter3 mr-2" aria-hidden="true"></i>
                        فیلتر اطلاعات
                    </button>
                    <button id="btn-reset-order" class="btn btn-warning">
                        <i class="icon-trash mr-2" aria-hidden="true"></i>
                        پاک کردن
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"factor_code"},{"data":"user"},{"data":"user_mobile"},{"data":"info"},{"data":"order_status"},{"data":"order_date"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.report.orders.dt')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>شماره فاکتور</th>
                <th>کاربر سفارش‌دهنده</th>
                <th>شماره کاربر سفارش‌دهنده</th>
                <th>اطلاعات کامل گیرنده</th>
                <th>وضعیت سفارش</th>
                <th>تاریخ سفارش</th>
                <th>وضعیت ارسال</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>شماره فاکتور</th>
                <th>کاربر سفارش‌دهنده</th>
                <th>شماره کاربر سفارش‌دهنده</th>
                <th>اطلاعات کامل گیرنده</th>
                <th>وضعیت سفارش</th>
                <th>تاریخ سفارش</th>
                <th>وضعیت ارسال</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Receiver information -->
    <div id="modal_form_receiver_detail" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">اطلاعات گیرنده</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-bottom">
                            <div class="font-weight-semibold">نام گیرنده:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_full_name">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item border-bottom">
                            <div class="font-weight-semibold">شماره تلفن:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_phone">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item border-bottom">
                            <div class="font-weight-semibold">کد ملی:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_natnum">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item border-bottom">
                            <div class="font-weight-semibold">استان:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_province">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item border-bottom">
                            <div class="font-weight-semibold">شهر:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_city">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item border-bottom">
                            <div class="font-weight-semibold">کد پستی:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_postal_code">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="font-weight-semibold">آدرس کامل:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_address">
                                خطا در بارگذاری
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary px-4" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /receiver information -->

    <script>
        (function ($) {
            'use strict';

            window.report_variable_filters = JSON.parse('<?= json_encode($query_builder ?? []); ?>');
        })(jQuery);
    </script>
</div>
<!-- /content area -->
