<!-- Content area -->
<div class="content">
    <!-- Fieldset legend -->
    <!-- 2 columns form -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'جزئیات سفارش']); ?>

        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-md-6">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-user mr-2"></i>
                                اطلاعات درخواست کننده
                            </legend>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>نام و نام خانوادگی:</label>
                                    <span class="text-info-800">
سعید گرامی فر
                                   </span>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>شماره موبایل:</label>
                                        <span class="text-info-800">
                                            ۰۹۱۳۳۵۱۸۰۷۸
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>استان:</label>
                                        <span class="text-info-800">
                                            یزد
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>شهر:</label>
                                        <span class="text-info-800">
                                            یزد
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>کدپستی:</label>
                                    <span class="text-info-800">
۸۹۱۶۷-۵۴۹۵۹
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>آدرس پستی:</label>
                                    <p class="text-info-800">
                                        یزد خیابان کاشانی کوچه لاله پلاک ۳۵
                                    </p>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-backspace2 mr-2"></i>
                                جزئیات ارجاع
                            </legend>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>شماره فاکتور:</label>
                                    <span class="text-info-800">
                                        <strong>
۸۹۱۶۷-۵۴۹۵۹
                                        </strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>مبلغ آیتم‌های بازگشتی:</label>
                                    <span class="text-warning-400">
                                        <strong>
۸۹۰.۰۰۰ تومان
                                        </strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>تاریخ ثبت سفارش:</label>
                                    <span class="text-green-800">
۵ بهمن ۱۳۹۹
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>تاریخ درخواست مرجوعی:</label>
                                    <span class="text-danger-800">
                                        <strong>
۵ بهمن ۱۳۹۹
                                        </strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>وضعیت ارجاع:</label>
                                    <span class="badge-dark p-1 rounded">
در صف بررسی
                                    </span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-credit-card mr-2"></i>
                                وضعیت مالی:
                            </legend>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>وضعیت پرداخت:</label>
                                    <span class="badge-success p-1 rounded">
پرداخت شده
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>کد رهگیری:</label>
                                    <span class="text-danger-800">
                                        <strong>
                                            dg-19203455
                                        </strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>تاریخ پرداخت:</label>
                                    <span class="text-green-800">
۵ بهمن ۱۳۹۹
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>شیوه پرداخت:</label>
                                    <span class="text-green-800">
درب منزل
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>تاریخ پرداخت:</label>
                                    <span class="text-danger-800">
-
                                    </span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-backspace2 mr-2"></i>
                                توضیحات کاربر:
                            </legend>

                            <div class="row">
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