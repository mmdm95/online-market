
<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'کیف پول']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
            <tr>
                <th>کد سیستم</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>مانده</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>محمدمهدی</td>
                <td>دهقان منشادی</td>
                <td>
                    <span class="text-info-800">25.000.000</span>
                    تومان
                </td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item"><i class="icon-eye"></i>مشاهده</a>
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
