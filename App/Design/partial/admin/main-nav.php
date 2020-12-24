<div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand">
        <a href="<?= url('home.index'); ?>" class="d-inline-block">
            <img src="<?= url('image.show') . config()->get('settings.logo_light.value'); ?>"
                 alt="<?= config()->get('settings.title.value'); ?>">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <span class="navbar-text ml-md-3 mr-md-auto">
				<span class="badge bg-success">آنلاین</span>
			</span>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
                    <i class="icon-headphones"></i>
                    <span class="d-md-none ml-2">تماس‌های بررسی نشده</span>
                    <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0">۳</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
                    <div class="dropdown-content-header">
                        <span class="font-weight-semibold">تیکت‌ها</span>
                    </div>

                    <div class="dropdown-content-body dropdown-scrollable">
                        <ul class="media-list">
                            <li class="media">
                                <div class="media-body">
                                    <div class="media-title">
                                        <a href="#">
                                            <span class="font-weight-semibold">محمدمهدی دهقان منشادی</span>
                                            <span class="text-muted float-right font-size-sm">۰۴:۵۸</span>
                                        </a>
                                    </div>

                                    <span class="text-muted">
                                        آیا فاکتور به شماره ۱۲۳ قابل بازگشت هست؟
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="dropdown-content-footer justify-content-center p-0">
                        <a href="#" class="bg-light text-grey w-100 py-2" data-popup="tooltip" title="مشاهده همه"><i
                                    class="icon-menu7 d-block top-0"></i></a>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="../../../../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle"
                         alt="">
                    <span>وحید ضیایی</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item"><i class="icon-user-plus"></i>اطلاعات من</a>
                    <a href="#" class="dropdown-item"><i class="icon-coins"></i>مدیریت مالی</a>
                    <a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> فاکتورهای جدید <span
                                class="badge badge-pill badge-info ml-auto">۵۸</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"><i class="icon-cog5"></i> تنظیمات</a>
                    <a href="#" class="dropdown-item"><i class="icon-switch2"></i> خروج</a>
                </div>
            </li>
        </ul>
    </div>
</div>