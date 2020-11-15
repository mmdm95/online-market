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
                        <a href="#"><img src="../../../../global_assets/images/placeholders/placeholder.jpg" width="38"
                                         height="38" class="rounded-circle" alt=""></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">وحید ضیایی</div>
                        <div class="font-size-xs opacity-50 iransans-regular">
                            <i class="icon-user font-size-sm mr-1"></i>مدیر سایت
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
                    <i class="icon-menu" title="Main"></i></li>
                <li class="nav-item">
                    <a href="<?= url('admin.index'); ?>" class="nav-link">
                        <i class="icon-home4"></i>
                        <span>
									میز کار
                        </span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-users"></i> <span>کاربران</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="<?= url('admin.user.add'); ?>" class="nav-link active">
                                افزودن کاربر
                            </a></li>
                        <li class="nav-item"><a href="<?= url('admin.user.view'); ?>" class="nav-link">
                                مشاهده کاربران
                            </a></li>
                    </ul>
                </li>
                <!-- /main -->
                <!-- Shop -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">فروشگاه</div>
                    <i class="icon-menu" title="Main"></i></li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-lan2"></i> <span>مدیریت دسته‌بندی</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="<?= url('admin.category.add'); ?>" class="nav-link active">
                                افزودن دسته
                            </a></li>
                        <li class="nav-item"><a href="<?= url('admin.category.view'); ?>" class="nav-link">
                                مشاهده دسته‌ها
                            </a></li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-bucket"></i> <span>رنگ‌ها</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="<?= url('admin.color.add'); ?>" class="nav-link active">
                                افزودن رنگ جدید
                            </a></li>
                        <li class="nav-item"><a href="<?= url('admin.color.view'); ?>" class="nav-link">
                                لیست رنگ‌ها
                            </a></li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-credit-card"></i> <span>کوپن‌های تخفیف</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="<?= url('admin.coupon.add'); ?>" class="nav-link active">
                                افزودن کوپن تخفیف
                            </a></li>
                        <li class="nav-item"><a href="<?= url('admin.coupon.view'); ?>" class="nav-link">
                                مشاهده کوپن
                            </a></li>
                    </ul>
                </li>
                <!-- /Shop -->
                <!-- Connection -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">ارتباطات</div>
                    <i class="icon-menu" title="Main"></i></li>
                <li class="nav-item">
                    <a href="<?= url('admin.index'); ?>" class="nav-link">
                        <i class="icon-envelop3"></i>
                        <span>
									تماس‌ها
                        </span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-bucket"></i> <span>رنگ‌ها</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="<?= url('admin.color.add'); ?>" class="nav-link active">
                                افزودن رنگ جدید
                            </a></li>
                        <li class="nav-item"><a href="<?= url('admin.color.view'); ?>" class="nav-link">
                                لیست رنگ‌ها
                            </a></li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-credit-card"></i> <span>کوپن‌های تخفیف</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        <li class="nav-item"><a href="<?= url('admin.coupon.add'); ?>" class="nav-link active">
                                افزودن کوپن تخفیف
                            </a></li>
                        <li class="nav-item"><a href="<?= url('admin.coupon.view'); ?>" class="nav-link">
                                مشاهده کوپن
                            </a></li>
                    </ul>
                </li>
                <!-- /Connection -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>