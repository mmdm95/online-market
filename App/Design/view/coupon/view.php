<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده کوپن‌های تخفیف']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
            <tr>
                <th>#</th>
                <th>عنوان کوپن</th>
                <th>کد کوپن</th>
                <th>تاریخ شروع / پایان</th>
                <th>مبلغ حداقل / حداکثر فاکتور</th>
                <th>تعداد استفاده شده / کل</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>تخفیف عیدانه</td>
                <td>newrooz1399</td>
                <td>
                            <span class="text-green-600">
                                ۱۳۳/۰۸/۱۰
                            </span>
                    -
                    <span class="text-danger-400">
                                ۱۳۳/۰۸/۲۳
                            </span>
                </td>
                <td>
                            <span class="text-green-600">
                                300,000
                            </span>
                    -
                    <span class="text-danger-400">
                                15,000,000
                            </span>
                    تومان
                </td>
                <td>
                            <span class="text-info-600">
                                ۵۰
                            </span>
                    /
                    <span class="text-danger-400">
                                ۶۰
                            </span>
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
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
