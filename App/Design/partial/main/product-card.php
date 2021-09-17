<div class="item">
    <div class="product_wrap">
        <?php if (isset($item['festival_discount'])): ?>
            <span class="pr_flash bg-danger">جشنواره</span>
        <?php elseif (DB_YES == $item['is_special']): ?>
            <span class="pr_flash">ویژه</span>
        <?php elseif ($item['created_at'] >= (time() - TIME_THREE_DAYS_IN_SECONDS)): ?>
            <span class="pr_flash bg-success">جدید</span>
        <?php endif; ?>

        <div class="product_img">
            <a href="<?= url('home.product.show', [
                'id' => $item['product_id'],
                'slug' => $item['slug'],
            ]); ?>" <?= ($new_tab ?? false) ? 'target="_blank"' : ''; ?>>
                <img src="<?= (config()->get('settings.default_image_placeholder.value') != '') ? url('image.show', ['filename' => config()->get('settings.default_image_placeholder.value')]) : ''; ?>"
                     data-src="<?= url('image.show') . $item['image']; ?>"
                     alt="<?= $item['title']; ?>" class="lazy">
            </a>

            <div class="product_action_box">
                <ul class="list_none pr_action_btn">
                    <li class="add-to-cart">
                        <a href="javascript:void(0);"
                           class="__add_to_cart_btn"
                           data-cart-item-code="<?= $item['code']; ?>">
                            <i class="icon-basket-loaded"></i>
                            افزودن به سبد خرید
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('home.product.show', [
                            'id' => $item['product_id'],
                            'slug' => $item['slug'],
                        ]); ?>">
                            <i class="icon-eye"></i>
                            مشاهده
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

            <?php if (get_product_availability($item)): ?>
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
                        <span class="price_symbole">تومان</span>
                    </span>
                    <?php if ($hasDiscount): ?>
                        <del>
                            <?= local_number(number_format($item['price'])); ?>
                            <span class="price_symbole">تومان</span>
                        </del>
                        <div class="on_sale">
                            <span>
                                ٪
                                <?php if (isset($item['festival_discount'])): ?>
                                    <?= local_number($item['festival_discount']); ?>
                                <?php else: ?>
                                    <?= local_number(get_percentage($item['discounted_price'], $item['price'])); ?>
                                <?php endif; ?>
                                 تخفیف
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="product_price">
                    <span class="badge badge-danger d-block py-3">ناموجود</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
