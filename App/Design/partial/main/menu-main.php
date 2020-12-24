<header class="header_wrap">
    <div class="middle-header dark_skin">
        <div class="container">
            <div class="nav_block">
                <a class="navbar-brand" href="<?= url('home.index'); ?>">
                    <img class="logo_light"
                         src="<?= url('image.show') . \config()->get('settings.logo_light.value'); ?>" alt="logo"/>
                    <img class="logo_dark" src="<?= url('image.show') . \config()->get('settings.logo.value'); ?>"
                         alt="logo"/>
                </a>
                <div class="contact_phone order-md-last">
                    <i class="linearicons-phone-wave"></i>
                    <span><?= local_number(\config()->get('settings.main_phone.value')) ?></span>
                </div>
                <div class="product_search_form">
                    <form action="<?= url('home.search')->getRelativeUrlTrimmed(); ?>" method="get">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="custom_select">
                                    <select class="first_null" name="search-categories-select-inp">
                                        <option value="-1">همه دسته ها</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <input class="form-control" placeholder="جستجوی محصول ..." required="required" type="text">
                            <button type="submit" class="search_btn"><i class="linearicons-magnifier"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom_header light_skin main_menu_uppercase bg_dark mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-3">
                    <div class="categories_wrap">
                        <button type="button" data-toggle="collapse" data-target="#navCatContent" aria-expanded="false"
                                class="categories_btn">
                            <i class="linearicons-menu"></i><span>همه دسته بندی ها </span>
                        </button>
                        <div id="navCatContent" class="nav_cat navbar collapse">
                            <ul>
                                <?php
                                $arr = array_slice($menu, 0, 10);
                                $othersArr = array_slice($menu, 10);
                                ?>
                                <?php foreach ($arr as $m): ?>
                                    <?php
                                    $hasChildren = count($m['children'] ?? []);
                                    ?>

                                    <li class="dropdown dropdown-mega-menu">
                                        <a class="dropdown-item nav-link <?= $hasChildren ? 'dropdown-toggler' : 'nav_item'; ?>"
                                           href="#"
                                            <?= $hasChildren ? 'data-toggle="dropdown"' : ''; ?>>
                                            <span><?= $m['name']; ?></span>
                                        </a>

                                        <?php if ($hasChildren): ?>
                                            <div class="dropdown-menu">
                                                <ul class="mega-menu d-lg-flex">
                                                    <?php
                                                    $hasImage = isset($menu_images[$m['id']]['image']);
                                                    $menuColSize = !$hasImage ? 'col-lg-12' : 'col-lg-7';
                                                    $menuSubColSize = !$hasImage ? 'col-lg-4' : 'col-lg-6';
                                                    ?>

                                                    <li class="mega-menu-col <?= $menuColSize; ?>">
                                                        <ul class="d-lg-flex">
                                                            <?php foreach ($m['children'] as $children2): ?>
                                                                <?php
                                                                $hasChildren2 = count($children2['children'] ?? []);
                                                                ?>
                                                                <li class="mega-menu-col <?= $menuSubColSize; ?>">
                                                                    <ul>
                                                                        <li class="dropdown-header">
                                                                            <a class="nav-link nav_item"
                                                                               href="#">
                                                                                <?= $children2['name']; ?>
                                                                            </a>
                                                                        </li>
                                                                        <?php if ($hasChildren2): ?>
                                                                            <?php foreach ($children2['children'] as $children3): ?>
                                                                                <li>
                                                                                    <a class="dropdown-item nav-link nav_item"
                                                                                       href="#">
                                                                                        <?= $children3['name']; ?>
                                                                                    </a>
                                                                                </li>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </ul>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </li>

                                                    <?php if ($hasImage): ?>
                                                        <li class="mega-menu-col col-lg-5">
                                                            <div class="header-banner2">
                                                                <a href="#">
                                                                    <img src="<?= url('image.show') . $menu_images[$m['id']]['image']; ?>"
                                                                         alt="<?= $m['name']; ?>">
                                                                </a>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                                <?php if (count($othersArr)): ?>
                                    <li>
                                        <ul class="more_slide_open">
                                            <?php foreach ($othersArr as $m): ?>
                                                <?php
                                                $hasChildren = count($m['children'] ?? []);
                                                ?>

                                                <li class="dropdown dropdown-mega-menu">
                                                    <a class="dropdown-item nav-link <?= $hasChildren ? 'dropdown-toggler' : 'nav_item'; ?>"
                                                       href="#"
                                                        <?= $hasChildren ? 'data-toggle="dropdown"' : ''; ?>>
                                                        <i class="flaticon-friendship"></i>
                                                        <span><?= $m['name']; ?></span>
                                                    </a>

                                                    <?php if ($hasChildren): ?>
                                                        <div class="dropdown-menu">
                                                            <ul class="mega-menu d-lg-flex">
                                                                <?php
                                                                $hasImage = isset($menu_images[$m['id']]['image']);
                                                                $menuColSize = !$hasImage ? 'col-lg-12' : 'col-lg-7';
                                                                $menuSubColSize = !$hasImage ? 'col-lg-4' : 'col-lg-6';
                                                                ?>

                                                                <li class="mega-menu-col <?= $menuColSize; ?>">
                                                                    <ul class="d-lg-flex">
                                                                        <?php foreach ($m['children'] as $children2): ?>
                                                                            <?php
                                                                            $hasChildren2 = count($children2['children'] ?? []);
                                                                            ?>
                                                                            <li class="mega-menu-col <?= $menuSubColSize; ?>">
                                                                                <ul>
                                                                                    <li class="dropdown-header">
                                                                                        <a class="nav-link nav_item"
                                                                                           href="#">
                                                                                            <?= $children2['name']; ?>
                                                                                        </a>
                                                                                    </li>
                                                                                    <?php if ($hasChildren2): ?>
                                                                                        <?php foreach ($children2['children'] as $children3): ?>
                                                                                            <li>
                                                                                                <a class="dropdown-item nav-link nav_item"
                                                                                                   href="#">
                                                                                                    <?= $children3['name']; ?>
                                                                                                </a>
                                                                                            </li>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </ul>
                                                                            </li>
                                                                        <?php endforeach; ?>
                                                                    </ul>
                                                                </li>

                                                                <?php if ($hasImage): ?>
                                                                    <li class="mega-menu-col col-lg-5">
                                                                        <div class="header-banner2">
                                                                            <a href="#">
                                                                                <img src="<?= url('image.show') . $menu_images[$m['id']]['image']; ?>"
                                                                                     alt="<?= $m['name']; ?>">
                                                                            </a>
                                                                        </div>
                                                                    </li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <?php if (count($othersArr)): ?>
                                <div class="more_categories">دسته بندی های بیشتر</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-8 col-sm-6 col-9">
                    <nav class="navbar navbar-expand-lg">
                        <button class="navbar-toggler side_navbar_toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSidetoggle" aria-expanded="false">
                            <span class="ion-android-menu"></span>
                        </button>
                        <div class="collapse navbar-collapse mobile_side_menu" id="navbarSidetoggle">
                            <ul class="navbar-nav">
                                <?php
                                $topMenu = \config()->get('settings.top_menu.value') ?? [];
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
                                            <div class="dropdown-menu">
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
                                <a href="<?= url('home.login'); ?>" class="nav-link">
                                    <i class="linearicons-user"></i>
                                </a>
                            </li>
                            <li class="dropdown cart_dropdown" id="__cart_main_container">
                                <?= $cart_section; ?>
                            </li>
                        </ul>

                        <div class="pr_search_icon">
                            <a href="javascript:void(0);" class="nav-link pr_search_trigger">
                                <i class="linearicons-magnifier"></i>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>