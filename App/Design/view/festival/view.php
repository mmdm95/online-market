<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست جشنواره ها']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
            <tr>
                <th>#</th>
                <th>نام جشنواره</th>
                <th>کد</th>
                <th>تاریخ</th>
                <th>وضعیت جشنواره</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>بهار ۱۴۰۰</td>
                <td>
                    <strong class="text-info">
                        nowrooz1400
                    </strong>
                </td>
                <td>
                    <strong>
                        <span class="text-green-800" >۱۳۹۹/۰۹/۱۳</span>
                        الی
                        <span class="text-danger-800">۱۳۹۹/۱۰/۱۳</span>
                    </strong>
                </td>
                <td>
                    <span class="badge badge-danger">
                        غیرفعال
                    </span>
                </td>

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

</div>
<!-- /content area -->
