<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده سفارشات']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":""},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.order.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>کد سیستم</th>
                <th>شماره فاکتور</th>
                <th>کاربر سفارش‌دهنده</th>
                <th>اطلاعات کامل گیرنده</th>
                <th>وضعیت سفارش</th>
                <th>تاریخ سفارش</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>محمدمهدی</td>
                <td><a href="#">دهقان منشادی</a></td>
                <td>
                    <button type="button" class="btn btn-dark" data-toggle="modal"
                            data-target="#modal_form_receiver_detail">
                        مشاهده
                    </button>
                </td>
                <td>۰۹۱۷۹۵۱۶۲۷۱</td>
                <td>
                    ۲ آبان ۱۳۹۹
                </td>
                <td><span class="badge badge-success">فعال</span></td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item"><i class="icon-pencil"></i>ویرایش</a>
                                <a href="#" class="dropdown-item"><i class="icon-trash"></i>حذف</a>
                                <a href="#" class="dropdown-item"><i class="icon-cart"></i>خریدها</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
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
                        <li class="list-group-item">
                            <div class="font-weight-semibold">نام گیرنده:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_full_name">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="font-weight-semibold">شماره تلفن:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_phone">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="font-weight-semibold">استان:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_province">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="font-weight-semibold">شهر:</div>
                            <div class="ml-auto text-info-800" id="__receiver_info_city">
                                خطا در بارگذاری
                            </div>
                        </li>
                        <li class="list-group-item">
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

</div>
<!-- /content area -->
