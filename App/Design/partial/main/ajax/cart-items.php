<?php

use Sim\Utils\StringUtil;

$cart = cart();
$items = $cart->getItems();

?>

<?php if (count($items)): ?>
    <table class="table">
        <thead>
        <tr>
            <th class="product-thumbnail text-left">&nbsp</th>
            <th class="product-name text-left">محصول</th>
            <th class="product-price text-left">قیمت</th>
            <th class="product-quantity text-left">تعداد</th>
            <th class="product-subtotal text-left">جمع</th>
            <th class="product-remove text-left">حذف</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <?php $price = (float)get_discount_price($item)[0]; ?>
            <tr>
                <td class="product-thumbnail">
                    <a href="<?= url('home.product.show', [
                        'id' => $item['product_id'],
                        'slug' => $item['slug'] ?? '',
                    ])->getRelativeUrl(); ?>">
                        <img src="<?= url('image.show', ['filename' => $item['image']])->getRelativeUrl(); ?>"
                             alt="<?= $item['title']; ?>">
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

                    <div class="d-flex flex-wrap">
                        <div class="m-2">
                            <small class="mx-1">در دسته:</small>
                            <small>
                                <a href="<?= url('home.search', [
                                    'category' => $item['category_id'],
                                    'category_slug' => StringUtil::slugify($item['category_name']),
                                ])->getRelativeUrl(); ?>" class="mr-4 text-info">
                                    <?= $item['category_name'] ?>
                                </a>
                            </small>
                        </div>

                        <div class="m-2">
                            <small class="mx-1">برند:</small>
                            <small>
                                <span><?= $item['brand_fa_name']; ?></span>
                            </small>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap">
                        <?php if (!empty($item['color_hex'])): ?>
                            <div class="m-2">
                                <div class="product_color_switch d-inline-block mx-2">
                                    <span style="background-color: <?= $item['color_hex']; ?>;"></span>
                                    <div class="d-inline-block mr-2">
                                        <?= $item['color_name']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['size'])): ?>
                            <div class="m-2">
                                        <span class="product_size_switch mx-2">
                                            <span><?= $item['size']; ?></span>
                                        </span>
                            </div>
                        <?php endif; ?>
                    </div>
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
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="empty-cart-container">
                <div class="empty-cart">
                    <div class="d-flex flex-column flex-lg-row align-items-center col">
                        <i class="linearicons-cart-empty"></i>
                        <span class="mt-3 ml-0 mt-lg-0 ml-lg-3">سبد خرید شما خالی است</span>
                    </div>
                    <a href="<?= url('home.search'); ?>" class="btn btn-info mt-3 ml-0 mt-lg-0 ml-lg-3">
                        ادامه خرید
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>