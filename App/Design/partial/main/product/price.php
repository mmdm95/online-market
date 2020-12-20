<?php if (count($product ?? [])): ?>
    <?php if (DB_YES === $product['product_availability'] || DB_YES === $product['is_available']): ?>
        <div class="product_price">
            <?php
            $hasDiscount = false;
            $discountPrice = number_format($product['price']);

            if (isset($product['festival_discount']) && 0 != $product['festival_discount']) {
                $hasDiscount = true;
                $discountPrice = number_format($product['price'] * (int)$product['festival_discount']);
            } elseif (isset($product['discount_until']) && $product['discount_until'] >= time()) {
                $hasDiscount = true;
                $discountPrice = number_format($product['discounted_price']);
            }
            ?>

            <span class="price">
                 <?= local_number($discountPrice); ?>
                 تومان
            </span>
            <del>
                <?= local_number(number_format($product['price'])); ?>
                تومان
            </del>
            <div class="on_sale">
                <span>
                    ٪
                    <?php if (isset($product['festival_discount'])): ?>
                        <?= local_number($product['festival_discount']); ?>
                    <?php else: ?>
                        <?= local_number(get_percentage($product['price'], $product['discounted_price'])); ?>
                    <?php endif; ?>
                    تخفیف
                </span>
            </div>
        </div>
        <div class="pr_desc">
            <p>
                <?= $product['guarantee']; ?>
            </p>
        </div>
    <?php else: ?>
        <div class="product_price">
            <span class="badge badge-danger d-block py-3">ناموجود</span>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="product_price">
        <span class="text-danger d-block py-3">کالای مورد نظر نامعتبر است!</span>
    </div>
<?php endif; ?>