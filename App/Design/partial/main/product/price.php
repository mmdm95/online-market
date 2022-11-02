<?php if (count($product ?? [])): ?>
    <?php if (get_product_availability($product)): ?>
        <div class="product_price">
            <?php
            [$discountPrice, $hasDiscount] = get_discount_price($product);
            $discountPrice = number_format($discountPrice);
            ?>

            <span class="price">
                 <?= local_number($discountPrice); ?>
                 <span class="price_symbole">تومان</span>
            </span>
            <?php if ($hasDiscount): ?>
                <del>
                    <?= local_number(number_format($product['price'])); ?>
                    <span class="price_symbole">تومان</span>
                </del>
                <div class="on_sale">
                    <?= $product['festival_discount']; ?>
                    <span>
                        ٪
                        <?php if (isset($product['festival_discount'])): ?>
                            <?= local_number($product['festival_discount']); ?>
                        <?php else: ?>
                            <?= local_number(get_percentage($product['discounted_price'], $product['price'])); ?>
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
<?php else: ?>
    <div class="product_price">
        <span class="text-danger d-block py-3">کالای مورد نظر نامعتبر است!</span>
    </div>
<?php endif; ?>