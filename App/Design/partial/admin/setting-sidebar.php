<div class="card mb-2">
    <div class="card-header bg-transparent header-elements-inline">
        <span class="text-uppercase font-size-sm font-weight-semibold">انواع تنظیمات</span>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <ul class="nav nav-sidebar" data-nav-type="accordion">
            <li class="nav-item">
                <a href="<?= url('admin.setting.main')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    اصلی
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('admin.setting.sms')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    پیامک
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('admin.setting.contact')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    تماس
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('admin.setting.social')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    شبکه‌های اجتماعی
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('admin.setting.footer')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    فوتر/پاورقی
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('admin.setting.pages.index')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    صفحه اصلی
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('admin.setting.pages.about')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    صفحه درباره
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= url('admin.setting.other')->getRelativeUrl(); ?>" class="nav-link">
                    <i class="icon-dash"></i>
                    سایر
                </a>
            </li>
        </ul>
    </div>
</div>