<header class="header_wrap fixed-top header_with_topbar">
    <div class="top-header">
        <div class="custom-container container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <ul class="contact_detail text-center text-lg-left">
                            <li>
                                <i class="linearicons-phone"></i>
                                <span><?= local_number(\config()->get('settings.main_phone.value')); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php if (!url()->contains(url('home.finish')->getRelativeUrlTrimmed())): ?>
                        <div class="text-center text-md-right">
                            <ul class="header_list">
                                <?php if (auth_home()->isLoggedIn()): ?>
                                    <li>
                                        <a href="<?= url('user.index'); ?>">
                                            <i class="linearicons-clipboard-user"></i>
                                            <span>پنل کاربری</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= url('home.logout'); ?>">
                                            <i class="linearicons-power-switch"></i>
                                            <span>خروج</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a href="<?= url('home.login'); ?>">
                                            <i class="linearicons-user"></i>
                                            <span>ورود</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= url('home.signup'); ?>">
                                            <i class="linearicons-user-plus"></i>
                                            <span>ثبت نام</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom_header dark_skin main_menu_uppercase">
        <div class="custom-container container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="<?= url('home.index'); ?>">
                    <img class="logo_light"
                         src="<?= url('image.show') . \config()->get('settings.logo_light.value'); ?>" alt="logo"/>
                    <img class="logo_dark" src="<?= url('image.show') . \config()->get('settings.logo.value'); ?>"
                         alt="logo"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-expanded="false">
                    <span class="ion-android-menu"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <?php
                        $topMenu = \config()->get('settings.top_menu.value') ?: [];
                        ?>

                        <?php foreach ($topMenu as $t): ?>
                            <?php
                            $hasChildren = count($t['children'] ?? []);
                            ?>

                            <li class="dropdown">
                                <a <?= $hasChildren ? 'data-toggle="dropdown"' : ''; ?>
                                        href="<?= $t['link']; ?>"
                                        class="nav-link <?= $hasChildren ? 'dropdown-toggle' : 'nav_item'; ?>">
                                    <?= $t['name']; ?>
                                </a>

                                <?php if ($hasChildren): ?>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul>
                                            <?php foreach ($t['children'] as $c): ?>
                                                <li>
                                                    <a class="dropdown-item nav-link nav_item"
                                                       href="<?= $c['link']; ?>">
                                                        <?= $c['name']; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <ul class="navbar-nav attr-nav align-items-center">
                    <li>
                        <a href="javascript:void(0);" class="nav-link search_trigger">
                            <i class="linearicons-magnifier"></i>
                        </a>
                        <div class="search_wrap">
                            <span class="close-search"><i class="ion-ios-close-empty"></i></span>
                            <form action="<?= url('home.search')->getRelativeUrlTrimmed(); ?>" method="get">
                                <input type="text" autocomplete="off" placeholder="جستجو"
                                       class="form-control" id="search_input" name="q">
                                <button type="submit" class="search_icon"><i class="ion-ios-search-strong"></i></button>
                            </form>
                        </div>
                        <div class="search_overlay"></div>
                    </li>

                    <?php if (
                        !url()->contains(url('home.cart')->getRelativeUrlTrimmed()) &&
                        !url()->contains(url('home.checkout')->getRelativeUrlTrimmed())
                    ): ?>
                        <li class="dropdown cart_dropdown" id="__cart_main_container">
                            <?= $cart_section; ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>