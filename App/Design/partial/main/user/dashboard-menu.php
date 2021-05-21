<div class="col-lg-3 col-md-4">
    <div id="__theia_sticky_sidebar">
        <div class="dashboard_content user_mini_info">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($user['first_name']) || !empty($user['last_name'])): ?>
                        <?php
                        $name = trim($user['first_name'] . ' ' . $user['last_name']);
                        ?>
                    <?php endif; ?>
                    <img src="" data-src="<?= asset_path('image/avatars/' . $user['image'], false); ?>"
                         alt="<?= $name ?: $user['username']; ?>" class="lazy">
                    <div class="text-center p-2">
                        <?= $name; ?>
                        <div class="mt-2">
                            <small>
                                <?= $user['username']; ?>
                            </small>
                        </div>
                    </div>
                    <a href="<?= url('user.info')->getRelativeUrl(); ?>"
                       class="btn btn-light btn-block rounded-0">
                        ویرایش مشخصات
                    </a>
                </div>
            </div>
        </div>
        <div class="dashboard_menu default-gap">
            <ul class="nav nav-tabs flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= url()->getRelativeUrl() == url('user.index')->getRelativeUrl() ? 'active' : ''; ?>"
                       href="<?= url('user.index'); ?>">
                        <i class="ti-layout-grid2"></i>
                        داشبورد
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url()->getRelativeUrl() == url('user.orders')->getRelativeUrl() ? 'active' : ''; ?>"
                       href="<?= url('user.orders'); ?>">
                        <i class="ti-shopping-cart-full"></i>
                        سفارشات
                    </a>
                </li>
                <!--                <li class="nav-item">-->
                <!--                    <a class="nav-link "-->
                <?= '';//url()->getRelativeUrl() == url('user.return-order')->getRelativeUrl() ? 'active' : '';  ?>
                <!--                       href="">-->
                <?= '';//url('user.return-order');  ?>
                <!--                        <i class="linearicons-cart-remove"></i>-->
                <!--                        مرجوع سفارش-->
                <!--                    </a>-->
                <!--                </li>-->
                <li class="nav-item">
                    <a class="nav-link <?= url()->getRelativeUrl() == url('user.wallet')->getRelativeUrl() ? 'active' : ''; ?>"
                       href="<?= url('user.wallet'); ?>">
                        <i class="icon-wallet"></i>
                        کیف پول
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url()->getRelativeUrl() == url('user.comments')->getRelativeUrl() ? 'active' : ''; ?>"
                       href="<?= url('user.comments'); ?>">
                        <i class="ti-comments"></i>
                        نظرات
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url()->getRelativeUrl() == url('user.addresses')->getRelativeUrl() ? 'active' : ''; ?>"
                       href="<?= url('user.addresses'); ?>">
                        <i class="ti-location-pin"></i>
                        آدرس های من
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url()->getRelativeUrl() == url('user.favorite')->getRelativeUrl() ? 'active' : ''; ?>"
                       href="<?= url('user.favorite'); ?>">
                        <i class="ti-heart"></i>
                        کالاهای مورد علاقه
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url()->getRelativeUrl() == url('user.info')->getRelativeUrl() ? 'active' : ''; ?>"
                       href="<?= url('user.info'); ?>">
                        <i class="ti-id-badge"></i>
                        اطلاعات حساب
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('home.logout'); ?>">
                        <i class="ti-power-off"></i>
                        خروج
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>