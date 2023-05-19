<div class="item">
    <div class="product_wrap">
        <?php if (isset($item['festival_discount'])): ?>
            <span class="pr_flash bg-danger">جشنواره</span>
        <?php elseif (DB_YES == $item['is_special']): ?>
            <span class="pr_flash">ویژه</span>
        <?php elseif ($item['created_at'] >= (time() - TIME_THREE_DAYS_IN_SECONDS)): ?>
            <span class="pr_flash bg-success">جدید</span>
        <?php endif; ?>

        <?php
        $isComingSoon = DB_YES == $item['show_coming_soon'];
        $callForMore = DB_YES == $item['call_for_more'];
        $isAvailable = get_product_availability($item);
        ?>

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
                        <a href="<?= url('home.compare', [
                            'p1' => $item['product_id'],
                            'p2' => false,
                            'p3' => false,
                        ]); ?>">
                            <i class="icon-shuffle"></i>
                            مقایسه
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

            <?php if (!$isComingSoon && !$callForMore && $isAvailable): ?>
                <?php
                $discountExpire = getDiscountExpireTime($item);
                ?>
                <?php if (!empty($discountExpire)): ?>
                    <div class="flex-row text-center" countdown
                         data-date="<?= date('Y-m-d H:i:s', $discountExpire); ?>">
                        <div class="col">
                            <span data-days>0</span>
                            <small class="text-danger">روز</small>
                        </div>
                        <div class="col">
                            <span data-hours>0</span>
                            <small class="text-danger">ساعت</small>
                        </div>
                        <div class="col">
                            <span data-minutes>0</span>
                            <small class="text-danger">دقیقه</small>
                        </div>
                        <div class="col">
                            <span data-seconds>0</span>
                            <small class="text-danger">ثانیه</small>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
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

            <?php if ($isComingSoon): ?>
                <div class="product_price">
                    <span class="badge badge-info d-block py-3">بزودی</span>
                </div>
            <?php elseif ($callForMore): ?>
                <div class="product_price">
                    <span class="badge badge-success d-block py-3">برای اطلاعات بیشتر تماس بگیرید</span>
                </div>
            <?php elseif ($isAvailable): ?>
                <div class="product_price">
                    <?php
                    [$discountPrice, $hasDiscount] = get_discount_price($item);
                    ?>

                    <span class="price">
                        <?= local_number(number_format($discountPrice)); ?>
                        <span class="price_symbole">تومان</span>
                    </span>
                    <?php if ($hasDiscount): ?>
                        <del>
                            <?= local_number(number_format($item['price'])); ?>
                            <span class="price_symbole">تومان</span>
                        </del>
                        <div class="on_sale">
                            <span>
                                <?php if (isset($item['festival_discount'])): ?>
                                    ٪
                                    <?= local_number($item['festival_discount']); ?>
                                <?php else: ?>
                                    <?php
                                    $percentage = get_percentage($item['discounted_price'], $item['price']);
                                    ?>
                                    <?php if ($percentage < 1): ?>
                                        کمتر از ۱ درصد
                                    <?php else: ?>
                                        ٪
                                        <?= local_number($percentage); ?>
                                    <?php endif; ?>
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
