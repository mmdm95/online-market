<?php

use Sim\Utils\StringUtil;

?>

<!-- Content area -->
<div class="content">
    <div class="d-block d-lg-inline-block">
        <div class="d-block d-sm-flex mb-2">
            <span class="py-2 px-3 text-center bg-slate-700 d-block d-lg-inline-block rounded-left col"><?= $today_date; ?></span>
            <span class="py-2 px-3 text-center bg-slate d-block d-lg-inline-block ltr rounded-right"
                  id="simpleClock">0:00:00 AM</span>
        </div>
    </div>
    <div>
        <a href="<?= url('admin.contact-us.view', '')->getRelativeUrl(); ?>"
           class="btn bg-blue py-2 px-3 d-block d-md-inline-block mb-2 border-0">
            پیام‌های خوانده نشده:
            <?= StringUtil::toPersian($unread_contact_count); ?>
        </a>
        <a href="<?= url('admin.complaints.view', '')->getRelativeUrl(); ?>"
           class="btn bg-warning py-2 px-3 d-block d-md-inline-block ml-0 ml-md-2 mb-2 border-0">
            شکایات خوانده نشده:
            <?= StringUtil::toPersian($unread_complaint_count); ?>
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="mb-3 mt-2">
                <h5 class="mb-0 font-weight-semibold">
                    دسترسی سریع
                </h5>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.user.view', '')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-users icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            مدیریت کاربران
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.product.view', '')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-box icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            مدیریت محصولات
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.order.view', '')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-basket icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            سفارشات
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.blog.view', '')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-files-empty2 icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            مطالب
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.static.page.view', '')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-stack icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            صفحات ثابت
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.slider.view', '')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-image-compare icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            مدیریت اسلایدشو
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.file-manager')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-files-empty icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            مدیریت فایل‌ها
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="<?= url('admin.settings')->getRelativeUrl(); ?>"
               class="bg-white text-dark">
                <div class="card">
                    <div class="card-body text-center border-top-3 border-top-success">
                        <i class="icon-cog icon-2x mb-2" aria-hidden="true"></i>
                        <h6 class="font-weight-semibold mb-0">
                            تنظیمات
                        </h6>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <?php if (count($order_badges_count)): ?>
        <hr>

        <div class="row">
            <div class="col-12">
                <div class="mb-3 mt-2">
                    <h5 class="mb-0 font-weight-semibold">
                        سفارشات
                    </h5>
                    <span class="text-muted d-block">وضعیت سفارشات و تعداد آن‌ها در هر وضعیت</span>
                </div>
            </div>

            <?php foreach ($order_badges_count as $value): ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="card">
                        <a href="#"
                           class="p-1 border-2 rounded" style="color: <?= get_color_from_bg($value['color']); ?>; border-color: <?= $value['color']; ?> !important;">
                            <div class="p-2 rounded" style="background-color: <?= $value['color']; ?>;">
                                <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="font-weight-semibold mb-0">
                                            <?= StringUtil::toPersian($value['count']); ?>
                                        </h4>
                                    </div>
                                    <div>
                                        <span class="font-size-sm"><?= $value['title']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <hr>

    <div class="row">
        <div class="col-12">
            <div class="mb-3 mt-2">
                <h5 class="mb-0 font-weight-semibold">
                    گزارش کلی
                </h5>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-warning-300 d-flex align-items-center rounded">
                    <i class="icon-user icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($users_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">کاربران</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-orange d-flex align-items-center rounded">
                    <i class="icon-user icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($user_admin_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">کاربر ادمین</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-orange-800 d-flex align-items-center rounded">
                    <i class="icon-user icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($user_normal_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">کاربر عادی</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-pink-600 d-flex align-items-center rounded">
                    <i class="icon-user icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($user_deactivate_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">کاربر غیرفعال</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-pink-400 d-flex align-items-center rounded">
                    <i class="icon-coin-dollar icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($payment_method_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">روش پرداخت</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-violet-300 d-flex align-items-center rounded">
                    <i class="icon-bucket icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($color_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">رنگ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-violet-600 d-flex align-items-center rounded">
                    <i class="icon-price-tags icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($brand_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">برند</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-purple d-flex align-items-center rounded">
                    <i class="icon-lan2 icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($category_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">دسته‌بندی</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-purple-300 d-flex align-items-center rounded">
                    <i class="icon-gift icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($festival_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">جشنواره</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-indigo-300 d-flex align-items-center rounded">
                    <i class="icon-unicode icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($unit_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">تعداد واحد</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-indigo d-flex align-items-center rounded">
                    <i class="icon-percent icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($coupon_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">کوپن تخفیف</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-primary-600 d-flex align-items-center rounded">
                    <i class="icon-box icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($product_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">محصول</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-primary-300 d-flex align-items-center rounded">
                    <i class="icon-basket icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($order_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">سفارش</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-info-300 d-flex align-items-center rounded">
                    <i class="icon-files-empty2 icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($blog_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">مطلب</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-info d-flex align-items-center rounded">
                    <i class="icon-stack2 icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($blog_category_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">دسته‌بندی مطلب</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-info-700 d-flex align-items-center rounded">
                    <i class="icon-stack icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($static_page_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">نوشته ثابت</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-teal d-flex align-items-center rounded">
                    <i class="icon-envelop5 icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($contact_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">تماس</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-teal-300 d-flex align-items-center rounded">
                    <i class="icon-balance icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($complaint_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">شکایت</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-success-300 d-flex align-items-center rounded">
                    <i class="icon-question7 icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($faq_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">سؤال متداول</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-success d-flex align-items-center rounded">
                    <i class="icon-newspaper2 icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($newsletter_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">خبرنامه</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-green d-flex align-items-center rounded">
                    <i class="icon-image-compare icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($slide_show_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">اسلاید</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-green-300 d-flex align-items-center rounded">
                    <i class="icon-instagram icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($instagram_image_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">تصویر اینستاگرام</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card d-flex flex-row p-1 align-items-center">
                <div class="p-3 bg-brown-300 d-flex align-items-center rounded">
                    <i class="icon-lock icon-half-x count-square-icon" aria-hidden="true"></i>
                </div>
                <div class="p-2 col">
                    <div class="d-block d-sm-flex d-md-block d-xl-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-semibold mb-0">
                                <?= StringUtil::toPersian($security_question_count); ?>
                            </h4>
                        </div>
                        <div>
                            <span class="text-muted font-size-sm">سؤال امنیتی</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /content area -->

<script>
    (function ($) {
        'use strict';

        $(function () {
            var myVar = setInterval(function () {
                myTimer();
            }, 1000);

            function myTimer() {
                var d = new Date();
                $('#simpleClock').html(d.toLocaleTimeString());
            }
        });
    })(jQuery);
</script>