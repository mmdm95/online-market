<?php

use Sim\Utils\StringUtil;

?>

<a class="nav-link cart_trigger"
   href="#"
   data-toggle="dropdown">
    <i class="linearicons-bag2"></i>
    <span class="cart_count"><?= local_number($count ?? 0); ?></span>
</a>

<div class="cart_box cart_right dropdown-menu dropdown-menu-right">
    <?php if (count($items ?? [])): ?>
        <ul class="cart_list">
            <?php foreach ($items ?? [] as $item): ?>
                <li>
                    <a href="javascript:void(0);" class="__remove_from_cart_btn item_remove"
                       data-cart-item-code="<?= $item['code']; ?>">
                        <i class="ion-close"></i>
                    </a>
                    <?php if (isset($item['title']) || isset($item['image'])): ?>
                        <a href="<?= url('home.product.show', [
                            'id' => $item['product_id'],
                            'slug' => $item['slug'] ?? '',
                        ]); ?>">
                            <?php if (isset($item['image'])): ?>
                                <img src="" data-src="<?= url('image.show') . $item['image']; ?>"
                                     alt="<?= $item['title'] ?? 'product ' . $item['code']; ?>" class="lazy">
                            <?php endif; ?>
                            <?= $item['title']; ?>
                        </a>
                    <?php endif; ?>
                    <span class="cart_quantity">
                        <?= local_number(number_format($item['qnt'])); ?>
                        عدد
                        <?= local_number(number_format($item['qnt'] * (int)$item['price'])); ?>
                        <span class="cart_amount">
                            <span class="price_symbole">تومان</span>
                        </span>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="cart_footer">
            <p class="cart_total">
                <strong>جمع:</strong>
                <?= local_number(number_format(StringUtil::toEnglish($total_amount ?? 0))); ?>
                <span class="cart_price"> <span class="price_symbole">تومان</span></span>
            </p>
            <p class="cart_buttons">
                <a href="<?= url('home.cart'); ?>" class="btn btn-fill-line view-cart">
                    سبد خرید
                </a>
            </p>
        </div>
    <?php else: ?>
        <div class="cart_total text-center">
            هیچ محصولی در سبد خرید وجود ندارد
        </div>
    <?php endif; ?>
</div>