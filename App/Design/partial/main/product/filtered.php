<?php if (count($products ?? [])): ?>
    <div class="col-12">
        <div class="row">
            <?php
            $limit = config()->get('settings.product_each_page.value');
            $current = $pagination['current_page'];
            $total = $pagination['total'];
            $firstCount = ($current - 1) * $limit;
            $lastCount = ($current + 1) * $limit;
            ?>
            <div class="col-12 my-2">
                نمایش
                <?= $firstCount + 1; ?>
                تا
                <?= $total < $lastCount ? $total : $lastCount; ?>
                از مجموع
                <?= $total ?>
                رکورد
            </div>
        </div>
        <div class="row">
            <?php foreach ($products as $item): ?>
                <div class="col-md-4 col-6">
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
                                    <!--                                    <li>-->
                                    <!--                                        <a href="">-->
                                    <?= '';//url('home.compare');    ?>
                                    <!--                                            <i class="icon-shuffle"></i>-->
                                    <!--                                        </a>-->
                                    <!--                                    </li>-->
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

    <div class="col-12">
        <!-- START SECTION PAGINATION -->
        <?php load_partial('main/section-pagination', ['pagination' => $pagination ?? []]); ?>
        <!-- END SECTION PAGINATION -->
    </div>

<?php else: ?>
    <div class="col-12">
        <?php load_partial('main/not-found-rows'); ?>
    </div>
<?php endif; ?>