    <!-- Content area -->
    <div class="content">

        <!-- Highlighting rows and columns -->
        <div class="card">
            <?php load_partial('admin/card-header', ['header_title' => 'پیام‌ها']); ?>

            <div class="card-body">
                با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
            </div>

            <table class="table table-bordered table-hover datatable-highlight">
                <thead>
                <tr>
                    <th>#</th>
                    <th>نام و نام خانوادگی فرستنده</th>
                    <th>موضوع</th>
                    <th>تاریخ ارسال</th>
                    <th>وضعیت</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>۱</td>
                    <td>محمد مهدی دهقان</td>
                    <td>اخذ نمایندگی</td>
                    <td>۲۹ آبان ۱۳۹۹</td>
                    <td><span class="badge badge-danger">خوانده نشده</span></td>
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
