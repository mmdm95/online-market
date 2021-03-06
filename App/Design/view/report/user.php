<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده سفارشات']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <div class="card-body border-bottom">
            <div id="builder-basic" class="mb-3"></div>
            <div class="text-right">
                <button id="btn-filter" class="btn btn-primary mr-2">
                    <i class="icon-filter3 mr-2" aria-hidden="true"></i>
                    فیلتر اطلاعات
                </button>
                <button id="btn-reset" class="btn btn-warning">
                    <i class="icon-trash mr-2" aria-hidden="true"></i>
                    پاک کردن
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
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
                            data-target="#modal_form_reciver_detail">
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
            <!-- Vertical form modal -->
            <div id="modal_form_reciver_detail" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Multi column -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">اطلاعات گیرنده</h5>
                            </div>
                            <ul class="list-group list-group-flush border-top">
                                <li class="list-group-item">
                                    <span class="font-weight-semibold">نام گیرنده:</span>
                                    <div class="ml-auto">
                                        محمدمهدی دهقان منشادی
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <span class="font-weight-semibold">شماره تلفن:</span>
                                    <div class="ml-auto">
                                        09179516271
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <span class="font-weight-semibold">
                                        استان:
                                    </span>
                                    <div class="ml-auto">
                                        یزد
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <span class="font-weight-semibold">شهر:</span>
                                    <div class="ml-auto">
                                        یزد
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <span class="font-weight-semibold">
                                        آدرس کامل:
                                    </span>
                                    <div class="ml-auto">
                                        خیابان کاشانی کوچه لاله پلاک 35
                                    </div>
                                </li>
                            </ul>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                            </div>
                        </div>
                        <!-- /multi column -->
                    </div>
                </div>
            </div>
            <!-- /vertical form modal -->
            </tbody>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <script>
        (function ($) {
            'use strict';

            $(function () {
                $('#builder-basic').queryBuilder({
                    filters: [{
                        id: 'name',
                        label: 'Name',
                        type: 'string'
                    }, {
                        id: 'category',
                        label: 'Category',
                        type: 'integer',
                        input: 'select',
                        values: {
                            1: 'Books',
                            2: 'Movies',
                            3: 'Music',
                            4: 'Tools',
                            5: 'Goodies',
                            6: 'Clothes'
                        },
                        operators: ['equal', 'not_equal', 'in', 'not_in', 'is_null', 'is_not_null']
                    }, {
                        id: 'in_stock',
                        label: 'In stock',
                        type: 'integer',
                        input: 'radio',
                        values: {
                            1: 'Yes',
                            0: 'No'
                        },
                        operators: ['equal']
                    }, {
                        id: 'price',
                        label: 'Price',
                        type: 'double',
                        validation: {
                            min: 0,
                            step: 0.01
                        }
                    }, {
                        id: 'id',
                        label: 'Identifier',
                        type: 'string',
                        placeholder: '____-____-____',
                        operators: ['equal', 'not_equal'],
                        validation: {
                            format: /^.{4}-.{4}-.{4}$/
                        }
                    }],
                    lang: 'fa-IR',
                });

                $('#btn-reset').on('click', function () {
                    $('#builder-basic').queryBuilder('reset');
                });

                $('#btn-filter').on('click', function () {
                    var result = $('#builder-basic').queryBuilder('getSQL', 'named');

                    if (result.sql.length) {
                        alert(result.sql + '\n\n' + JSON.stringify(result.params, null, 2));
                    }
                });
            });
        })(jQuery);
    </script>
</div>
<!-- /content area -->
