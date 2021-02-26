<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- Receiver info -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>اطلاعات گیرنده</h3>
        </div>
        <div class="row m-0">
            <div class="col-lg-12 border border-light px-3 py-2 text-center">
                <label class="m-0">
                    محمد مهدی دهقان منشادی
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    استان:
                </small>
                <label class="mb-1">
                    فارس
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    شهر:
                </small>
                <label class="mb-1">
                    آباده
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    شماره تماس:
                </small>
                <label class="mb-1">
                    09179516271
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    کد پستی:
                </small>
                <label class="mb-1">
                    0123456789
                </label>
            </div>
            <div class="col-lg-12 border border-light px-3 py-2 text-center">
                <label class="m-0">
                    ۴۵ متری شهید چمران، خیابان جام جم، کوچه سوم انتهای کوچه سمت راست
                </label>
            </div>
        </div>
    </div>
</div>
<!-- /receiver info -->

<!-- Order info -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>جزئیات سفارش</h3>
        </div>
        <div class="row m-0">
            <div class="col-lg-12">
                <small class="mb-1">
                    شماره فاکتور:
                </small>
                <label class="mb-1 en-font">
                    05512935867
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    مبلغ قابل پرداخت:
                </small>
                <label class="mb-1 text-success">
                    <?= StringUtil::toPersian(number_format(StringUtil::toEnglish('55000'))); ?>
                    <small>تومان</small>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    تاریخ ثبت سفارش:
                </small>
                <label class="mb-1">
                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, time()); ?>
                </label>
            </div>
            <div class="col-lg-12 border border-light px-3 py-2">
                <small class="d-block text-center mb-1">
                    وضعیت سفارش
                </small>
                <label class="d-block text-center p-2 rounded bg-warning text-white">
                    نامشخص
                </label>
            </div>
        </div>
    </div>
</div>
<!-- /order info -->

<!-- Payment info -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>وضعیت مالی</h3>
        </div>
        <div class="row m-0">
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    وضعیت پرداخت:
                </small>
                <label class="mb-1">
                    <?php load_partial('admin/parser/payment-status', [
                        'status' => 12,
                        'extra_padding' => true,
                    ]); ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    کد رهگیری:
                </small>
                <label class="mb-1 text-danger">
                    <i class="linearicons-minus" aria-hidden="true"></i>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    تاریخ پرداخت:
                </small>
                <label class="mb-1">
                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, time()); ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    شیوه پرداخت:
                </small>
                <label class="mb-1">
                    <?php load_partial('admin/parser/payment-method-type', ['type' => 12]); ?>
                </label>
            </div>
            <div class="col-lg-12 border border-light px-3 py-2 text-center">
                <button class="btn p-0" data-toggle="collapse" data-target="#paymentHistory">
                    تاریخچه تراکنش
                    <i class="linearicons-chevron-down ml-2 mr-0" aria-hidden="true"></i>
                </button>
            </div>

            <!-- All payments -->
            <div id="paymentHistory" class="col-12 p-0 collapse bg-light">
                <div class="table-responsive pt-3">
                    <table class="table mb-3">
                        <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>توضیحات</th>
                            <th>درگاه</th>
                            <th>وضعیت</th>
                            <th>مبلغ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <?= Jdf::jdate(DEFAULT_TIME_FORMAT, time()); ?>
                            </td>
                            <td>
                                پرداخت موفق
                            </td>
                            <td>
                                بانک ملی
                            </td>
                            <td>
                                <i class="linearicons-checkmark-circle text-success icon-2x" aria-hidden="true"></i>
                            </td>
                            <td>
                                <?= StringUtil::toPersian(number_format(StringUtil::toEnglish('55000'))); ?>
                                <small>تومان</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /all payments -->
        </div>
    </div>
</div>
<!-- /payment info -->

<!-- Order items -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>آیتم‌های سفارش</h3>
        </div>
        <div class="card-body position-relative">
            <div class="order-detail-order-items-more">
                <button class="btn pl-3 pb-3 pr-0 pt-0 dropdown-toggle no-icon" data-toggle="dropdown">
                    <i class="ti-more-alt fa-rotate-90 m-0" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu px-3">
                    <div>
                        <a href="#" class="d-block p-2">
                            خرید مجدد کالا
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-block d-lg-flex m-0 align-items-start">
                <div>
                    <a href="#">
                        <img src="<?= url('image.show', ['filename' => 'products/1.jpg']); ?>"
                             alt=""
                             class="mx-0 mx-lg-3"
                             width="160px" height="auto">
                    </a>
                </div>
                <div class="col">
                    <div>
                        <div class="d-flex justify-content-between pr-4">
                            <a href="#">
                                <h6>
                                    ماگ سفری بارک مدل SK520
                                </h6>
                            </a>
                            <div>
                                <span class="badge badge-danger px-2 py-1">مرجوع شده</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="product_color_badge">
                                <span class="mr-2" style="background-color: #000"></span>
                                <div class="d-inline-block">مشکی</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <i class="icon-size-fullscreen mr-2 ml-1" aria-hidden="true"></i>
                            ایکس لارج
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <i class="linearicons-shield-check mr-2 ml-1" aria-hidden="true"></i>
                            گارانتی اصالت و سلامت فیزیکی کالا
                        </div>
                    </div>
                    <ul class="list-inline list-inline-dotted my-3">
                        <li class="list-inline-item my-1">
                            <small>قیمت واحد:</small>
                            <label class="m-0">
                                250,000
                                <small>تومان</small>
                            </label>
                        </li>
                        <li class="list-inline-item my-1">
                            <small>تعداد:</small>
                            <label class="m-0">
                                2
                            </label>
                        </li>
                        <li class="list-inline-item my-1">
                            <small>تخفیف:</small>
                            <label class="m-0">
                                20,000
                                <small>تومان</small>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body position-relative border-top">
            <div class="order-detail-order-items-more">
                <button class="btn pl-3 pb-3 pr-0 pt-0 dropdown-toggle no-icon" data-toggle="dropdown">
                    <i class="ti-more-alt fa-rotate-90 m-0" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu px-3">
                    <div>
                        <a href="#" class="d-block p-2">
                            ثبت نظر
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="d-block p-2">
                            خرید مجدد کالا
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-block d-lg-flex m-0 align-items-start">
                <div>
                    <a href="#">
                        <img src="<?= url('image.show', ['filename' => 'products/1.jpg']); ?>"
                             alt=""
                             class="mx-0 mx-lg-3"
                             width="160px" height="auto">
                    </a>
                </div>
                <div class="col">
                    <div>
                        <div class="d-flex justify-content-between pr-4">
                            <a href="#">
                                <h6>
                                    ماگ سفری بارک مدل SK520
                                </h6>
                            </a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="product_color_badge">
                                <span class="mr-2" style="background-color: #000"></span>
                                <div class="d-inline-block">مشکی</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <i class="icon-size-fullscreen mr-2 ml-1" aria-hidden="true"></i>
                            ایکس لارج
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <i class="linearicons-shield-check mr-2 ml-1" aria-hidden="true"></i>
                            گارانتی اصالت و سلامت فیزیکی کالا
                        </div>
                    </div>
                    <ul class="list-inline list-inline-dotted my-3">
                        <li class="list-inline-item my-1">
                            <small>قیمت واحد:</small>
                            <label class="m-0">
                                250,000
                                <small>تومان</small>
                            </label>
                        </li>
                        <li class="list-inline-item my-1">
                            <small>تعداد:</small>
                            <label class="m-0">
                                2
                            </label>
                        </li>
                        <li class="list-inline-item my-1">
                            <small>تخفیف:</small>
                            <label class="m-0">
                                20,000
                                <small>تومان</small>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /order items -->