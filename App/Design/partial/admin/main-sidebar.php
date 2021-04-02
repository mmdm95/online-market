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
        <div class="sidebar-user-material">
            <div class="sidebar-user-material-body">
                <div class="card-body text-center">
                    <a href="#">
                        <img src="<?= asset_path('image/avatars/avatars-default.png', false); ?>"
                             class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
                    </a>
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0 text-white text-shadow-dark">وحید ضیایی</h6>
                            <span class="font-size-sm text-white text-shadow-dark">مدیر سایت</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <a href="<?= url('admin.setting.main')->getRelativeUrl(); ?>" class="text-white">
                                <i class="icon-cog3"></i>
                            </a>
                        </div>
                    </div>

                    <a href="<?= url('home.index')->getRelativeUrl(); ?>"
                       target="_blank"
                       class="btn border text-white btn-block mt-3">
                        نمایش سایت
                    </a>
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
                    <a href="<?= url('admin.index'); ?>"
                       class="nav-link <?= url()->getRelativeUrlTrimmed() == url('admin.index')->getRelativeUrlTrimmed() ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="میز کار"
                       data-placement="left">
                        <i class="icon-home4"></i>
                        <span>میز کار</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-users"></i> <span>کاربران</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.user.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.user.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن کاربر
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.user.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.user.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
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
                            <a href="<?= url('admin.pay_method.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.pay_method.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن روش پرداخت جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.pay_method.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.pay_method.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                لیست روش‌های پرداخت
                            </a>
                        </li>
                    </ul>
                </li>
                <!--                <li class="nav-item nav-item-submenu">-->
                <!--                    <a href="#" class="nav-link"><i class="icon-box-remove"></i> <span>روش‌های ارسال</span></a>-->
                <!---->
                <!--                    <ul class="nav nav-group-sub">-->
                <!--                        <li class="nav-item">-->
                <!--                            <a href="" class="nav-link">-->
                <?= '';//url('admin.send_method.add');        ?>
                <!--                                افزودن روش ارسال جدید-->
                <!--                            </a>-->
                <!--                        </li>-->
                <!--                        <li class="nav-item">-->
                <!--                            <a href="" class="nav-link">-->
                <?= '';//url('admin.send_method.view', '');        ?>
                <!--                                لیست روش‌های ارسال-->
                <!--                            </a>-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!--                </li>-->
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-bucket"></i> <span>رنگ‌ها</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.color.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.color.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن رنگ جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.color.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.color.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                لیست رنگ‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-price-tags2"></i> <span>برندها</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.brand.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.brand.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن برند جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.brand.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.brand.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                لیست برندها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-lan2"></i> <span>مدیریت دسته‌بندی</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.category.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.category.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن دسته
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.category.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.category.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                مشاهده دسته‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-gift"></i> <span>جشنواره‌ها</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.festival.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.festival.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن جشنواره
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.festival.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.festival.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                لیست جشنواره‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.unit.view', ''); ?>" class="nav-link"
                       data-popup="tooltip"
                       data-original-title="واحد‌ها"
                       data-placement="left">
                        <i class="icon-unicode"></i>
                        <span>واحد‌ها</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-percent"></i> <span>کوپن‌های تخفیف</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.coupon.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.coupon.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن کوپن تخفیف
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.coupon.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.coupon.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                مشاهده کوپن‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-box"></i> <span>محصولات</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.product.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.product.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن محصول جدید
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.product.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.product.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
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
                            <a href="<?= url('admin.wallet.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.wallet.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                مشاهده کیف پول کاربران
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.deposit-type.view'); ?>"
                               class="nav-link <?= url()->contains(url('admin.deposit-type.view')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                مدیریت انواع تراکنش ها
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-basket"></i> <span>سفارشات</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.order.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.order.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                سفارشات ثبت شده
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.badge.view'); ?>"
                               class="nav-link <?= url()->contains(url('admin.badge.view')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                مدیریت وضعیت سفارشات
                            </a>
                        </li>
                    </ul>
                </li>
                <!--                <li class="nav-item">-->
                <!--                    <a href="" class="nav-link"-->
                <?= '';//url('admin.return.order.view');    ?>
                <!--                       data-popup="tooltip"-->
                <!--                       data-original-title="سفارشات مرجوعی"-->
                <!--                       data-placement="left">-->
                <!--                        <i class="icon-backspace2"></i>-->
                <!--                        <span>سفارشات مرجوعی</span>-->
                <!--                    </a>-->
                <!--                </li>-->
                <!-- /financial and orders -->

                <!-- Report -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">گزارش‌گیری</div>
                    <i class="icon-menu" title="گزارش‌گیری"></i>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link"
                       data-popup="tooltip"
                       data-original-title="گزارش‌گیری از کاربران"
                       data-placement="left">
                        <i class="icon-archive"></i>
                        <span>گزارش‌گیری از کاربران</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link"
                       data-popup="tooltip"
                       data-original-title="گزارش‌گیری از محصولات"
                       data-placement="left">
                        <i class="icon-archive"></i>
                        <span>گزارش‌گیری از محصولات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link"
                       data-popup="tooltip"
                       data-original-title="گزارش‌گیری از کیف پول"
                       data-placement="left">
                        <i class="icon-archive"></i>
                        <span>گزارش‌گیری از کیف پول</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url(''); ?>" class="nav-link"
                       data-popup="tooltip"
                       data-original-title="گزارش‌گیری از سفارشات"
                       data-placement="left">
                        <i class="icon-archive"></i>
                        <span>گزارش‌گیری از سفارشات</span>
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
                            <a href="<?= url('admin.blog.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.blog.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن مطلب
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.blog.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.blog.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                مشاهده مطالب
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack2"></i> <span>دسته‌بندی مطالب</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.blog.category.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.blog.category.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن دسته‌بندی مطلب
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.blog.category.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.blog.category.view')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                مشاهده دسته‌بندی مطالب
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack"></i> <span>صفحات ثابت</span></a>

                    <ul class="nav nav-group-sub">
                        <li class="nav-item">
                            <a href="<?= url('admin.static.page.add'); ?>"
                               class="nav-link <?= url()->contains(url('admin.static.page.add')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
                                افزودن صفحه ثابت
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= url('admin.static.page.view', ''); ?>"
                               class="nav-link <?= url()->contains(url('admin.static.page.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>">
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
                    <a href="<?= url('admin.contact-us.view', ''); ?>"
                       class="nav-link <?= url()->contains(url('admin.contact-us.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="تماس‌ها"
                       data-placement="left">
                        <i class="icon-envelop5"></i>
                        <span>تماس‌ها</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.complaints.view', ''); ?>"
                       class="nav-link <?= url()->contains(url('admin.complaints.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="شکایات"
                       data-placement="left">
                        <i class="icon-balance"></i>
                        <span>شکایات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.faq.view', ''); ?>"
                       class="nav-link <?= url()->contains(url('admin.faq.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="سؤالات متداول"
                       data-placement="left">
                        <i class="icon-question7"></i>
                        <span>سؤالات متداول</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.newsletter.view', ''); ?>"
                       class="nav-link <?= url()->contains(url('admin.newsletter.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="خبرنامه"
                       data-placement="left">
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
                    <a href="<?= url('admin.slider.view', ''); ?>"
                       class="nav-link <?= url()->contains(url('admin.slider.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="مدیریت اسلایدشو"
                       data-placement="left">
                        <i class="icon-image-compare"></i>
                        <span>مدیریت اسلایدشو</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.instagram.view', ''); ?>"
                       class="nav-link <?= url()->contains(url('admin.instagram.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="مدیریت تصاویر اینستاگرام"
                       data-placement="left">
                        <i class="icon-instagram"></i>
                        <span>مدیریت تصاویر اینستاگرام</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.sec_question.view', ''); ?>"
                       class="nav-link <?= url()->contains(url('admin.sec_question.view', '')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="سؤالات امنیتی"
                       data-placement="left">
                        <i class="icon-lock"></i>
                        <span>سؤالات امنیتی</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.file-manager'); ?>"
                       class="nav-link <?= url()->contains(url('admin.file-manager')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="مدیریت فایل‌ها"
                       data-placement="left">
                        <i class="icon-files-empty"></i>
                        <span>مدیریت فایل‌ها</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.setting.main'); ?>"
                       class="nav-link <?= url()->contains(url('admin.setting.main')->getRelativeUrlTrimmed()) ? 'active' : ''; ?>"
                       data-popup="tooltip"
                       data-original-title="تنظیمات"
                       data-placement="left">
                        <i class="icon-cog"></i>
                        <span>تنظیمات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin.logout'); ?>"
                       class="nav-link"
                       data-popup="tooltip"
                       data-original-title="خروج"
                       data-placement="left">
                        <i class="icon-switch2"></i>
                        <span>خروج</span>
                    </a>
                </li>
                <!-- /others -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>