<?php

use Sim\Utils\StringUtil;

?>

<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>کالاهای مورد علاقه</h3>
        </div>
        <div class="card-body">
            <?php if (count($favorites)): ?>
                <div class="table-responsive wishlist_table">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name">محصول</th>
                            <th class="product-price">قیمت</th>
                            <th class="product-stock-status">وضعیت</th>
                            <th class="product-add-to-cart"></th>
                            <th class="product-remove">حذف</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($favorites as $favorite): ?>
                            <tr>
                                <td class="product-thumbnail">
                                    <a href="<?= url('home.product.show', [
                                        'id' => $favorite['product_id'],
                                        'slug' => $favorite['slug']
                                    ]); ?>">
                                        <img src=""
                                             data-src="<?= url('image.show', ['filename' => $favorite['image']]); ?>"
                                             alt="<?= $favorite['title']; ?>" class="lazy">
                                    </a>
                                </td>
                                <td class="product-name" data-title="<?= $favorite['title']; ?>">
                                    <a href="<?= url('home.product.show', [
                                        'id' => $favorite['product_id'],
                                        'slug' => $favorite['slug']
                                    ]); ?>">
                                        <?php if (!empty($favorite['color_name'])): ?>
                                            <div class="mx-2 my-1">
                                                <span class="p-2 d-inline-block rounded-pill"
                                                      style="background-color: <?= $favorite['color_hex']; ?>;"></span>
                                                <?= $favorite['color_name']; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($favorite['size'])): ?>
                                            <div class="mx-2 my-1"><?= $favorite['size']; ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($favorite['guarantee'])): ?>
                                            <div class="d-block my-1 mx-2"><?= $favorite['guarantee']; ?></div>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td class="product-price" data-title="قیمت">
                                    <?php
                                    [$discountPrice, $hasDiscount] = get_discount_price($favorite);
                                    ?>
                                    <?php if ($hasDiscount): ?>
                                        <del>
                                            <?= local_number(number_format(StringUtil::toEnglish($favorite['price']))); ?>
                                            <span class="price_symbole">تومان</span>
                                        </del>
                                    <?php endif; ?>
                                    <?= local_number(number_format(StringUtil::toEnglish($discountPrice))); ?>
                                    <span class="price_symbole">تومان</span>
                                </td>
                                <td class="product-stock-status" data-title="وضعیت محصول">
                                    <?php
                                    $availability = get_product_availability($favorite);
                                    ?>
                                    <?php if ($availability): ?>
                                        <span class="badge badge-pill badge-success">موجود</span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-danger">ناموجود</span>
                                    <?php endif; ?>
                                </td>
                                <td class="product-add-to-cart">
                                    <button type="button"
                                            class="btn btn-fill-out btn-sm __add_to_cart_btn"
                                            data-cart-item-code="<?= $favorite['code']; ?>">
                                        <i class="icon-basket-loaded"></i>
                                        افزودن به سبد خرید
                                    </button>
                                </td>
                                <td class="product-remove" data-title="حذف">
                                    <a href="javascript:void(0);" class="__item_remover_btn"
                                       data-remove-url="<?= url('ajax.user.favorite.remove')->getRelativeUrl(); ?>"
                                       data-remove-id="<?= $favorite['favorite_id']; ?>"
                                       data-toggle="tooltip" data-original-title="حذف از فهرست"
                                       data-placement="right">
                                        <i class="ti-close" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <?php load_partial('main/not-found-rows', ['show_border' => false]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>