<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-right8"></i>
        </a>
        منو
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="#">
                            <img src="../../../../global_assets/images/placeholders/placeholder.jpg" width="38"
                                 height="38" class="rounded-circle" alt="">
                        </a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">وحید ضیایی</div>
                        <div class="font-size-xs opacity-50 iransans-regular">
                            <i class="icon-user font-size-sm mr-1"></i>
                            مدیر سایت
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">اصلی</div>
                    <i class="icon-menu" title="اصلی"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.index'); ?>" class="nav-link">
                        <i class="icon-home4"></i>
                        <span>میز کار</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-users"></i> <span>کاربران</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.user.add'); ?>" class="nav-link">
                                افزودن کاربر
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.user.view', ''); ?>" class="nav-link">
                                مشاهده کاربران
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /main -->

                <!-- Shop -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">فروشگاه</div>
                    <i class="icon-menu" title="فروشگاه"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-coin-dollar"></i> <span>روش‌های پرداخت</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.pay_method.add'); ?>" class="nav-link">
                                افزودن روش پرداخت جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.pay_method.view', ''); ?>" class="nav-link">
                                لیست روش‌های پرداخت
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-box-remove"></i> <span>روش‌های ارسال</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.send_method.add'); ?>" class="nav-link">
                                افزودن روش ارسال جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.send_method.view', ''); ?>" class="nav-link">
                                لیست روش‌های ارسال
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-bucket"></i> <span>رنگ‌ها</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.color.add'); ?>" class="nav-link">
                                افزودن رنگ جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.color.view', ''); ?>" class="nav-link">
                                لیست رنگ‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-price-tags2"></i> <span>برندها</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.brand.add'); ?>" class="nav-link">
                                افزودن برند جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.brand.view', ''); ?>" class="nav-link">
                                لیست برندها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-lan2"></i> <span>مدیریت دسته‌بندی</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.category.add'); ?>" class="nav-link">
                                افزودن دسته
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.category.view', ''); ?>" class="nav-link">
                                مشاهده دسته‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-gift"></i> <span>جشنواره‌ها</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.festival.add'); ?>" class="nav-link">
                                افزودن جشنواره
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.festival.view', ''); ?>" class="nav-link">
                                لیست جشنواره‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.unit.view', ''); ?>" class="nav-link">
                        <i class="icon-unicode"></i>
                        <span>واحد‌ها</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-percent"></i> <span>کوپن‌های تخفیف</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.coupon.add'); ?>" class="nav-link">
                                افزودن کوپن تخفیف
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.coupon.view', ''); ?>" class="nav-link">
                                مشاهده کوپن‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-box"></i> <span>محصولات</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.product.add'); ?>" class="nav-link">
                                افزودن محصول جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.product.view', ''); ?>" class="nav-link">
                                مشاهده محصولات
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /shop -->

                <!-- Financial and Orders -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">سفارشات و امور مالی</div>
                    <i class="icon-menu" title="سفارشات و امور مالی"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-wallet"></i> <span>کیف پول</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.wallet.view', ''); ?>" class="nav-link">
                                مشاهده کیف پول کاربران
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.wallet.deposit-type'); ?>" class="nav-link">
                                مدیریت انواع تراکنش ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-basket"></i> <span>سفارشات</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.order.view', ''); ?>" class="nav-link">
                                سفارشات ثبت شده
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.badge.view'); ?>" class="nav-link">
                                مدیریت وضعیت سفارشات
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.return.order.view'); ?>" class="nav-link">
                        <i class="icon-backspace2"></i>
                        <span>سفارشات مرجوعی</span>
                    </a>
                </li>
                <!-- /financial and orders -->

                <!-- Report -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">گذارش‌گیری</div>
                    <i class="icon-menu" title="گذارش‌گیری"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link">
                        <i class="icon-file-excel"></i>
                        <span>گذارش‌گیری از کاربران</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link">
                        <i class="icon-file-excel"></i>
                        <span>گذارش‌گیری از محصولات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link">
                        <i class="icon-file-excel"></i>
                        <span>گذارش‌گیری از کیف پول</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link">
                        <i class="icon-file-excel"></i>
                        <span>گذارش‌گیری از سفارشات</span>
                    </a>
                </li>
                <!-- /report -->

                <!-- Blog -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">صفحات</div>
                    <i class="icon-menu" title="صفحات"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-files-empty2"></i> <span>مطالب</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.blog.add'); ?>" class="nav-link">
                                افزودن مطلب
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.blog.view', ''); ?>" class="nav-link">
                                مشاهده مطالب
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack2"></i> <span>دسته‌بندی مطالب</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.blog.category.add'); ?>" class="nav-link">
                                افزودن دسته‌بندی مطلب
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.blog.category.view', ''); ?>" class="nav-link">
                                مشاهده دسته‌بندی مطالب
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack"></i> <span>صفحات ثابت</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.static.page.add'); ?>" class="nav-link">
                                افزودن صفحه ثابت
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.static.page.view', ''); ?>" class="nav-link">
                                مشاهده صفحات ثابت
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /blog -->

                <!-- Connection -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">ارتباطات</div>
                    <i class="icon-menu" title="ارتباطات"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.contact-us.view', ''); ?>" class="nav-link">
                        <i class="icon-envelop5"></i>
                        <span>تماس‌ها</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.complaints.view', ''); ?>" class="nav-link">
                        <i class="icon-balance"></i>
                        <span>شکایات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.faq.view', ''); ?>" class="nav-link">
                        <i class="icon-question7"></i>
                        <span>سؤالات متداول</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.newsletter.view', ''); ?>" class="nav-link">
                        <i class="icon-newspaper2"></i>
                        <span>خبرنامه</span>
                    </a>
                </li>
                <!-- /connection -->

                <!-- Others -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">سایر</div>
                    <i class="icon-menu" title="سایر"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.slider.view', ''); ?>" class="nav-link">
                        <i class="icon-image-compare"></i>
                        <span>مدیریت اسلایدشو</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.instagram.view', ''); ?>" class="nav-link">
                        <i class="icon-instagram"></i>
                        <span>مدیریت تصاویر اینستاگرام</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.sec_question.view', ''); ?>" class="nav-link">
                        <i class="icon-lock"></i>
                        <span>سؤالات امنیتی</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.file-manager'); ?>" class="nav-link">
                        <i class="icon-files-empty"></i>
                        <span>مدیریت فایل‌ها</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.setting'); ?>" class="nav-link">
                        <i class="icon-cog"></i>
                        <span>تنظیمات</span>
                    </a>
                </li>
                <!-- /others -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>