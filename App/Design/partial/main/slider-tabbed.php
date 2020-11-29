<?php if (count($tabbed_slider['items'] ?? [])): ?>
    <div class="section small_pb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading_tab_header">
                        <div class="heading_s2">
                            <h2><?= $tabbed_slider['title']; ?></h2>
                        </div>
                        <div class="tab-style2">
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#tabmenubar" aria-expanded="false">
                                <span class="ion-android-menu"></span>
                            </button>
                            <ul class="nav nav-tabs justify-content-center justify-content-md-end" id="tabmenubar"
                                role="tablist">
                                <?php foreach ($tabbed_slider['items'] as $k => $tab): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= 0 === $k ? 'active' : ''; ?>" id="tab<?= ($k + 1); ?>tab"
                                           data-toggle="tab"
                                           href="#tab<?= ($k + 1); ?>" role="tab" aria-controls="tab<?= ($k + 1); ?>"
                                           aria-selected=" <?= 0 === $k ? 'true' : 'false'; ?>">
                                            <?= $tab['name']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="tab_slider">
                        <?php foreach ($tabbed_slider['items'] as $k => $tab): ?>
                            <div class="tab-pane fade <?= 0 === $k ? 'show active' : ''; ?>"
                                 id="tab<?= ($k + 1); ?>" role="tabpanel"
                                 aria-labelledby="<?= $tab['name']; ?>">
                                <div class="product_slider carousel_slider owl-carousel owl-theme nav_style1"
                                     data-loop="true" data-dots="false" data-nav="true" data-margin="20"
                                     data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>
                                    <?php foreach ($tab['items'] as $item): ?>
                                        <div class="item">
                                            <div class="product_box text-center">
                                                <div class="product_img">
                                                    <a href="shop-product-detail.html">
                                                        <img src="assets/images/furniture_img4.jpg"
                                                             alt="furniture_img4">
                                                    </a>
                                                    <div class="product_action_box">
                                                        <ul class="list_none pr_action_btn">
                                                            <li>
                                                                <a href="shop-compare.html" class="popup-ajax">
                                                                    <i class="icon-shuffle"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="shop-quick-view.html" class="popup-ajax">
                                                                    <i class="icon-eye"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product_info">
                                                    <h6 class="product_title">
                                                        <a href="shop-product-detail.html">صندلی ناهارخوری</a>
                                                    </h6>
                                                    <div class="product_price">
                                                        <span class="price">69000 تومان</span>
                                                        <del>89000 تومان</del>
                                                    </div>
                                                    <div class="rating_wrap">
                                                        <div class="rating">
                                                            <div class="product_rate" style="width:70%"></div>
                                                        </div>
                                                        <span class="rating_num">(22)</span>
                                                    </div>
                                                    <div class="pr_desc">
                                                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و
                                                            با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه
                                                            روزنامه.</p>
                                                    </div>
                                                    <div class="add-to-cart">
                                                        <a href="#" class="btn btn-fill-out btn-radius">
                                                            <i class="icon-basket-loaded"></i>
                                                            افزودن به سبد خرید
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>