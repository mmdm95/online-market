<?php

use Sim\Utils\StringUtil;

?>

<?php
$hasItems = false;
foreach ($tabbed_slider['items'] ?? [] as $tab) {
    if (count($tab['items'] ?? [])) {
        $hasItems = true;
        break;
    }
}
?>
<?php if ($hasItems): ?>
    <div class="section small_pb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading_tab_header">
                        <div class="heading_s2">
                            <h2><?= $tabbed_slider['title']; ?></h2>
                        </div>
                        <div class="tab-style2">
                            <?php
                            $randomNum = StringUtil::randomString(9, StringUtil::RS_NUMBER);
                            ?>
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#tabmenubar<?= $randomNum; ?>" aria-expanded="false">
                                <span class="ion-android-menu"></span>
                            </button>
                            <ul class="nav nav-tabs justify-content-center justify-content-md-end __tabbed_slider_multi"
                                id="tabmenubar<?= $randomNum; ?>" role="tablist">
                                <?php $k = 0; ?>
                                <?php foreach ($tabbed_slider['items'] as $tab): ?>
                                    <?php if (count($tab['items'])): ?>
                                        <li class="nav-item">
                                            <a class="nav-link <?= 0 === $k ? 'active' : ''; ?>"
                                               id="tab<?= ($k + 1); ?>tab"
                                               data-toggle="tab"
                                               href="#tab<?= ($k + 1); ?>" role="tab"
                                               aria-controls="tab<?= ($k + 1); ?>"
                                               aria-selected=" <?= 0 === $k ? 'true' : 'false'; ?>">
                                                <?= $tab['name']; ?>
                                            </a>
                                        </li>
                                        <?php $k++; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="tab_slider">
                        <?php $k = 0; ?>
                        <?php foreach ($tabbed_slider['items'] as $tab): ?>
                            <?php if (count($tab['items'])): ?>
                                <div class="tab-pane fade <?= 0 === $k ? 'show active' : ''; ?>"
                                     id="tab<?= ($k + 1); ?>" role="tabpanel"
                                     aria-labelledby="<?= $tab['name']; ?>">
                                    <div class="product_slider carousel_slider owl-carousel owl-theme nav_style1"
                                         data-loop="true" data-dots="false" data-nav="true" data-margin="20"
                                         data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>
                                        <?php foreach ($tab['items'] as $item): ?>
                                            <div class="item">
                                                <div class="product_box text-center">
                                                    <?php if (isset($item['festival_discount'])): ?>
                                                        <span class="pr_flash bg-danger">جشنواره</span>
                                                    <?php elseif (DB_YES === $item['is_special']): ?>
                                                        <span class="pr_flash">ویژه</span>
                                                    <?php endif; ?>

                                                    <div class="product_img">
                                                        <a href="<?= url('home.product.show', [
                                                            'id' => $item['product_id'],
                                                            'slug' => $item['slug'],
                                                        ]); ?>">
                                                            <img src="<?= url('image.show') . $item['image']; ?>"
                                                                 alt="<?= $item['title']; ?>">
                                                        </a>
                                                        <div class="product_action_box">
                                                            <ul class="list_none pr_action_btn">
                                                                <!--                                                                <li>-->
                                                                <!--                                                                    <a href="">-->
                                                                <?= '';//url('home.compare');  ?>
                                                                <!--                                                                        <i class="icon-shuffle"></i>-->
                                                                <!--                                                                    </a>-->
                                                                <!--                                                                </li>-->
                                                                <li>
                                                                    <a href="<?= url('home.product.show', [
                                                                        'id' => $item['product_id'],
                                                                        'slug' => $item['slug'],
                                                                    ]); ?>">
                                                                        <i class="icon-eye"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product_info">
                                                        <h6 class="product_title">
                                                            <a href="<?= url('home.product.show', [
                                                                'id' => $item['product_id'],
                                                                'slug' => $item['slug'],
                                                            ]); ?>">
                                                                <?= $item['title']; ?>
                                                            </a>
                                                        </h6>
                                                        <?php if (DB_YES === $item['product_availability'] || DB_YES === $item['is_available']): ?>
                                                            <div class="product_price">
                                                                <?php
                                                                $hasDiscount = false;
                                                                $discountPrice = number_format($item['price']);

                                                                if (isset($item['festival_discount']) && 0 != $item['festival_discount']) {
                                                                    $hasDiscount = true;
                                                                    $discountPrice = number_format($item['price'] * (int)$item['festival_discount']);
                                                                } elseif (isset($item['discount_until']) && $item['discount_until'] >= time()) {
                                                                    $hasDiscount = true;
                                                                    $discountPrice = number_format($item['discounted_price']);
                                                                }
                                                                ?>

                                                                <span class="price">
                                                                    <?= local_number($discountPrice); ?>
                                                                    تومان
                                                                </span>
                                                                <?php if ($hasDiscount): ?>
                                                                    <del>
                                                                        <?= local_number(number_format($item['price'])); ?>
                                                                        تومان
                                                                    </del>
                                                                    <div class="on_sale">
                                                                        <span>
                                                                            ٪
                                                                            <?php if (isset($item['festival_discount'])): ?>
                                                                                <?= local_number($item['festival_discount']); ?>
                                                                            <?php else: ?>
                                                                                <?= local_number(get_percentage($item['price'], $item['discounted_price'])); ?>
                                                                            <?php endif; ?>
                                                                             تخفیف
                                                                        </span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="add-to-cart">
                                                                <a href="javascript:void(0);"
                                                                   class="btn btn-fill-out btn-radius __add_to_cart_btn"
                                                                   data-cart-item-code="<?= $item['code']; ?>">
                                                                    <i class="icon-basket-loaded"></i>
                                                                    افزودن به سبد خرید
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="product_price">
                                                                <span class="badge badge-danger d-block py-3">ناموجود</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php $k++; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>