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
                    <div class="d-flex">
                        <?php if (isset($item['title']) || isset($item['image'])): ?>
                            <a href="<?= url('home.product.show', [
                                'id' => $item['product_id'],
                                'slug' => $item['slug'] ?? '',
                            ]); ?>">
                                <?php if (isset($item['image'])): ?>
                                    <img src="" data-src="<?= url('image.show') . $item['image']; ?>"
                                         alt="<?= $item['title'] ?? 'product ' . $item['code']; ?>"
                                         class="lazy float-none">
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                        <div>
                            <a href="<?= url('home.product.show', [
                                'id' => $item['product_id'],
                                'slug' => $item['slug'] ?? '',
                            ]); ?>">
                                <?= $item['title']; ?>
                            </a>
                            <div class="cart_quantity">
                                <?= local_number(number_format($item['qnt'])); ?>
                                <?= $item['unit_title']; ?>

                                <?php [$discountPrice, $hasDiscount] = get_discount_price($item); ?>
                                <?php if ($hasDiscount): ?>
                                    <del>
                                        <?= local_number(number_format($item['qnt'] * (int)$item['price'])); ?>
                                        <span class="cart_amount">
                                            <span class="price_symbole">تومان</span>
                                        </span>
                                    </del>
                                <?php endif; ?>
                                <div>
                                    <span class="text-success ml-1">
                                        <?= local_number(number_format($item['qnt'] * (int)$discountPrice)); ?>
                                        <span class="cart_amount">
                                            <span class="price_symbole">تومان</span>
                                        </span>
                                    </span>
                                </div>

                                <?php if (!empty($item['color_hex']) && ($item['show_color'] === DB_YES || $item['is_patterned_color'] === DB_YES)): ?>
                                    <div class="mt-2">
                                        <div class="product_color_switch d-inline-block mx-2">
                                            <?php if ($item['is_patterned_color'] === DB_NO): ?>
                                                <span style="background-color: <?= $item['color_hex']; ?>;"></span>
                                            <?php endif; ?>
                                            <div class="d-inline-block mr-2">
                                                <?= $item['color_name']; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item['size'])): ?>
                                    <div class="mt-2">
                                        <span class="product_size_switch mx-2">
                                            <span><?= $item['size']; ?></span>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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