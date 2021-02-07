<!-- Content area -->
<div class="content">
    <!-- Fieldset legend -->
    <!-- 2 columns form -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'جزئیات سفارش']); ?>
        <div class="card-body">
            <fieldset>
                <legend class="font-weight-semibold">
                    <i class="icon-user mr-2"></i>
                    اطلاعات گیرنده
                </legend>
                <div class="row m-0">
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            نام و نام خانوادگی
                        </div>
                        <div class="text-info-800">
                            سعید گرامی فر
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شماره موبایل
                        </div>
                        <div class="text-info-800">
                            ۰۹۱۳۳۵۱۸۰۷۸
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            استان
                        </div>
                        <div class="text-info-800">
                            یزد
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شهر
                        </div>
                        <div class="text-info-800">
                            یزد
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            کدپستی
                        </div>
                        <div class="text-info-800">
                            ۸۹۱۶۷-۵۴۹۵۹
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            آدرس پستی
                        </div>
                        <div class="text-info-800">
                            یزد خیابان کاشانی کوچه لاله پلاک ۳۵
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mt-3">
                <legend class="font-weight-semibold">
                    <i class="icon-basket mr-2"></i>
                    جزئیات سفارش
                </legend>

                <div class="row m-0">
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شماره فاکتور
                        </div>
                        <div class="text-info-800">
                            ۸۹۱۶۷-۵۴۹۵۹
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            مبلغ قابل پرداخت
                        </div>
                        <div class="text-warning-400">
                            ۸۹۰,۰۰۰ تومان
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            تاریخ ثبت سفارش
                        </div>
                        <div class="text-green-800">
                            ۵ بهمن ۱۳۹۹
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            وضعیت سفارش
                        </div>
                        <span class="badge-dark p-1 rounded">در صف بررسی</span>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mt-3">
                <legend class="font-weight-semibold">
                    <i class="icon-credit-card mr-2"></i>
                    وضعیت مالی:
                </legend>

                <div class="row m-0">
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            وضعیت پرداخت
                        </div>
                        <span class="badge-success p-1 rounded">پرداخت شده</span>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            کد رهگیری
                        </div>
                        <strong class="text-danger-800">
                            dg-19203455
                        </strong>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            تاریخ پرداخت
                        </div>
                        <div class="text-green-800">
                            ۵ بهمن ۱۳۹۹
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            شیوه پرداخت
                        </div>
                        <div class="text-green-800">
                            درب منزل
                        </div>
                    </div>
                    <div class="col-lg-6 border py-2 px-3">
                        <div class="mb-2">
                            تاریخ پرداخت
                        </div>
                        <div class="text-danger-800">
                            <i class="icon-minus2" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <!-- Invoice template -->
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <h6 class="card-title">آیتم‌های خریداری شده</h6>
            <div class="header-elements">
                <button type="button" class="btn btn-sm btn-danger">
                    <i class="icon-file-pdf mr-2"></i>
                    دانلود فاکتور
                </button>
                <button type="button" class="btn btn-light btn-sm ml-3">
                    <i class="icon-printer mr-2"></i>
                    پرینت
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>مشخصات محصول</th>
                    <th>فی(به تومان)</th>
                    <th>تعداد</th>
                    <th>قیمت کل(به تومان)</th>
                    <th>قیمت نهایی(به تومان)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>۱</td>
                    <td>
                        <h6 class="mb-0">
                            <strong>
                                اسب سفید تک شاخ
                            </strong>
                        </h6>
                        <span class="text-muted">
                            رنگ آبی، با گارانتی سازگار سیستم
                        </span>
                    </td>
                    <td>
                        <strong>
                            ۱۵,۰۰۰
                        </strong>
                    </td>
                    <td>
                        <strong>
                            ۵
                        </strong>
                    </td>
                    <td>
                        <strong>
                            ۹۵,۰۰۰
                        </strong>
                    </td>
                    <td class="table-success">
                        <strong>
                            ۷۵,۰۰۰
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-body">
            <div class="d-md-flex flex-md-wrap">
                <div class="pt-2 pr-3 mb-3">
                    <h6 class="mb-3">کوپن تخفیف:</h6>

                    <ul class="list-unstyled text-muted">
                        <li>
                            <span class="badge badge-light badge-striped badge-striped-left border-left-success">
                            عید نوروز ۷۷
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                    <h6 class="mb-3">فاکتور نهایی:</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>مجموع مبالغ:</th>
                                <td class="text-right">۷۵.۰۰۰ تومان</td>
                            </tr>
                            <tr>
                                <th>
                                    تخفیف:
                                    <span class="font-weight-normal">(۱۰%)</span>
                                </th>
                                <td class="text-right">
                                    ۷۵.۰۰۰ تومان
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    مالیات:
                                    <span class="font-weight-normal">(۹% درصد مالیات بر ارزش افزوده)</span>
                                </th>
                                <td class="text-right">
                                    ۷۵.۰۰۰ تومان
                                </td>
                            </tr>
                            <tr>
                                <th>مبلغ قابل پرداخت:</th>
                                <td class="text-right text-primary">
                                    <h5 class="font-weight-semibold">۷۶۰۰۰ تومان</h5>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /invoice template -->
</div>
<!-- /content area -->