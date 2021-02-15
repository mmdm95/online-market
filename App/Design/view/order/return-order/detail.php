<!-- Content area -->
<div class="content">
    <!-- Fieldset legend -->
    <!-- 2 columns form -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'جزئیات سفارش']); ?>

        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-xl-6">
                        <fieldset>
                            <legend class="font-weight-semibold">
                                <i class="icon-user mr-2"></i>
                                اطلاعات درخواست کننده
                            </legend>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>نام و نام خانوادگی:</label>
                                    <div class="text-info-800">
                                        سعید گرامی فر
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>شماره موبایل:</label>
                                    <div class="text-info-800">
                                        ۰۹۱۳۳۵۱۸۰۷۸
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>استان:</label>
                                    <div class="text-info-800">
                                        یزد
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>شهر:</label>
                                    <div class="text-info-800">
                                        یزد
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>کدپستی:</label>
                                    <div class="text-info-800">
                                        ۸۹۱۶۷-۵۴۹۵۹
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>آدرس پستی:</label>
                                    <p class="text-info-800">
                                        یزد خیابان کاشانی کوچه لاله پلاک ۳۵
                                    </p>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-xl-6">
                        <fieldset>
                            <legend class="font-weight-semibold">
                                <i class="icon-backspace2 mr-2"></i>
                                جزئیات ارجاع
                            </legend>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>شماره فاکتور:</label>
                                    <div class="text-info-800">
                                        <strong>
                                            ۸۹۱۶۷-۵۴۹۵۹
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>مبلغ آیتم‌های بازگشتی:</label>
                                    <div class="text-warning-400">
                                        <strong>
                                            ۸۹۰.۰۰۰ تومان
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>تاریخ ثبت سفارش:</label>
                                    <div class="text-green-800">
                                        ۵ بهمن ۱۳۹۹
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>تاریخ درخواست مرجوعی:</label>
                                    <div class="text-danger-800">
                                        <strong>
                                            ۵ بهمن ۱۳۹۹
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>وضعیت ارجاع:</label>
                                    <div class="badge badge-dark rounded">
                                        در صف بررسی
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-xl-6">
                        <fieldset>
                            <legend class="font-weight-semibold">
                                <i class="icon-credit-card mr-2"></i>
                                وضعیت مالی
                            </legend>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>وضعیت پرداخت:</label>
                                    <div class="badge badge-success rounded">
                                        پرداخت شده
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>کد رهگیری:</label>
                                    <div class="text-danger-800">
                                        <strong>
                                            dg-19203455
                                        </strong>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>تاریخ پرداخت:</label>
                                    <div class="text-green-800">
                                        ۵ بهمن ۱۳۹۹
                                    </div>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>شیوه پرداخت:</label>
                                    <div class="text-green-800">
                                        درب منزل
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>تاریخ پرداخت:</label>
                                    <div class="text-danger-800">
                                        -
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-xl-6">
                        <fieldset>
                            <legend class="font-weight-semibold">
                                <i class="icon-backspace2 mr-2"></i>
                                توضیحات کاربر
                            </legend>

                            <div class="form-group">
                                <p class="p-2 bg-grey-800">
                                    با سلام به علت خط و خشی که روی گوشی بود تصمیم گرفتم کالا را مرجوع کنم.
                                </p>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Invoice template -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'آیتم‌های مورد درخواست جهت مرجوعی']); ?>

        <div class="table-responsive">
            <table class="table">
                <thead class="bg-light">
                <tr>
                    <th>محصول خریداری شده</th>
                    <th>فی(به تومان)</th>
                    <th>تعداد</th>
                    <th>قیمت نهایی(به تومان)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
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
                            ۱۵.۰۰۰
                        </strong>
                    </td>
                    <td>
                        <strong>
                            ۵
                        </strong>
                    </td>
                    <td>
                        <span class="font-weight-semibold">
                           <strong>
                            ۷۵.۰۰۰
                            </strong>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /invoice template -->
</div>
<!-- /content area -->