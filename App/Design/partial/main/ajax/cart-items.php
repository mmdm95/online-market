<?php

use Sim\Utils\StringUtil;

$cart = cart();
$items = $cart->getItems();

?>

<table class="table">
    <thead>
    <tr>
        <th class="product-thumbnail">&nbsp;</th>
        <th class="product-name">محصول</th>
        <th class="product-price">قیمت</th>
        <th class="product-quantity">تعداد</th>
        <th class="product-subtotal">جمع</th>
        <th class="product-remove">حذف</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <?php $price = (float)get_discount_price($item)[0]; ?>
        <tr>
            <td class="product-thumbnail">
                <a href="<?= url('home.product.show', [
                    'id' => $item['product_id'],
                    'slug' => $item['slug'],
                ])->getRelativeUrl(); ?>">
                    <img src="" data-src="<?= url('image.show', ['filename' => $item['image']])->getRelativeUrl(); ?>"
                         alt="<?= $item['title']; ?>" class="lazy">
                </a>
            </td>
            <td class="product-name" data-title="محصول">
                <a href="<?= url('home.product.show', [
                    'id' => $item['product_id'],
                    'slug' => $item['slug'],
                ])->getRelativeUrl(); ?>">
                    <?= $item['title']; ?>
                </a>

                <br>

                <small class="mx-1">در دسته</small>
                <a href="<?= url('home.search', [
                    'category' => $item['category_id'],
                    'category_slug' => StringUtil::slugify($item['category_name']),
                ])->getRelativeUrl(); ?>" class="mr-4">
                    <?= $item['category_name'] ?>
                </a>

                <small class="mx-1">برند</small>
                <span><?= $item['brand_fa_name']; ?></span>
            </td>
            <td class="product-price" data-title="قیمت">
                <?php if (0 != $price): ?>
                    <?= number_format(StringUtil::toEnglish($price)); ?>
                    <small>تومان</small>
                <?php else: ?>
                    رایگان
                <?php endif; ?>
            </td>
            <td class="product-quantity" data-title="تعداد">
                <div class="quantity">
                    <input type="button" value="-" class="minus">
                    <input type="text" name="quantity" value="<?= $item['qnt'] ?>"
                           data-max-cart-count="<?= $item['max_cart_count']; ?>"
                           data-cart-item-code="<?= $item['code']; ?>"
                           title="Qty" class="qty ltr" size="4">
                    <input type="button" value="+" class="plus">
                </div>
            </td>
            <td class="product-subtotal" data-title="جمع">
                <?php if (0 != $price): ?>
                    <?= number_format(StringUtil::toEnglish($item['qnt'] * $price)); ?>
                    <small>تومان</small>
                <?php else: ?>
                    رایگان
                <?php endif; ?>
            </td>
            <td class="product-remove" data-title="حذف">
                <a href="javascript:void(0);" class="__remove_from_cart_btn"
                   data-cart-item-code="<?= $item['code']; ?>">
                    <i class="ti-close"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>