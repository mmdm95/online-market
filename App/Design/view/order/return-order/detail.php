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
        <div class="card-header bg-transparent header-elements-inline">
            <h6 class="card-title">آیتم‌های مورد درخواست جهت مرجوعی</h6>
        </div>
        <div class="card-body">
            <!--            <div class="row">-->
            <!--                <div class="col-sm-6">-->
            <!--                    <div class="mb-4">-->
            <!--                        <img src="../../../../global_assets/images/logo_demo.png" class="mb-3 mt-2" alt="" style="width: 120px;">-->
            <!--                        <ul class="list list-unstyled mb-0">-->
            <!--                            <li>2269 Elba Lane</li>-->
            <!--                            <li>Paris, France</li>-->
            <!--                            <li>888-555-2311</li>-->
            <!--                        </ul>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!---->
            <!--                <div class="col-sm-6">-->
            <!--                    <div class="mb-4">-->
            <!--                        <div class="text-sm-right">-->
            <!--                            <h4 class="text-primary mb-2 mt-md-2">Invoice #49029</h4>-->
            <!--                            <ul class="list list-unstyled mb-0">-->
            <!--                                <li>Date: <span class="font-weight-semibold">January 12, 2015</span></li>-->
            <!--                                <li>Due date: <span class="font-weight-semibold">May 12, 2015</span></li>-->
            <!--                            </ul>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--            <div class="d-md-flex flex-md-wrap">-->
            <!--                <div class="mb-4 mb-md-2">-->
            <!--                    <span class="text-muted">Invoice To:</span>-->
            <!--                    <ul class="list list-unstyled mb-0">-->
            <!--                        <li><h5 class="my-2">Rebecca Manes</h5></li>-->
            <!--                        <li><span class="font-weight-semibold">Normand axis LTD</span></li>-->
            <!--                        <li>3 Goodman Street</li>-->
            <!--                        <li>London E1 8BF</li>-->
            <!--                        <li>United Kingdom</li>-->
            <!--                        <li>888-555-2311</li>-->
            <!--                        <li><a href="#">rebecca@normandaxis.ltd</a></li>-->
            <!--                    </ul>-->
            <!--                </div>-->
            <!---->
            <!--                <div class="mb-2 ml-auto">-->
            <!--                    <span class="text-muted">Payment Details:</span>-->
            <!--                    <div class="d-flex flex-wrap wmin-md-400">-->
            <!--                        <ul class="list list-unstyled mb-0">-->
            <!--                            <li><h5 class="my-2">Total Due:</h5></li>-->
            <!--                            <li>Bank name:</li>-->
            <!--                            <li>Country:</li>-->
            <!--                            <li>City:</li>-->
            <!--                            <li>Address:</li>-->
            <!--                            <li>IBAN:</li>-->
            <!--                            <li>SWIFT code:</li>-->
            <!--                        </ul>-->
            <!---->
            <!--                        <ul class="list list-unstyled text-right mb-0 ml-auto">-->
            <!--                            <li><h5 class="font-weight-semibold my-2">$8,750</h5></li>-->
            <!--                            <li><span class="font-weight-semibold">Profit Bank Europe</span></li>-->
            <!--                            <li>United Kingdom</li>-->
            <!--                            <li>London E1 8BF</li>-->
            <!--                            <li>3 Goodman Street</li>-->
            <!--                            <li><span class="font-weight-semibold">KFH37784028476740</span></li>-->
            <!--                            <li><span class="font-weight-semibold">BPT4E</span></li>-->
            <!--                        </ul>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
        <div class="table-responsive">
            <table class="table table-lg">
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