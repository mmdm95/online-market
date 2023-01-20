<?php if (count($product ?? [])): ?>
    <?php if (DB_YES == $product['show_coming_soon']): ?>
        <div class="product_price">
            <span class="badge badge-info d-block py-3">بزودی</span>
        </div>
    <?php elseif (DB_YES == $product['call_for_more']): ?>
        <div class="product_price">
            <span class="badge badge-success d-block py-3">برای اطلاعات بیشتر تماس بگیرید</span>
        </div>
    <?php elseif (get_product_availability($product)): ?>
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

        <?php
        $discountExpire = getDiscountExpireTime($product);
        ?>
        <?php if (!empty($discountExpire)): ?>
            <h5 class="countdown_time d-flex text-info alert alert-info" countdown
                 data-date="<?= date('Y-m-d H:i:s', $discountExpire); ?>">
                <div class="col">
                    <span data-days>0</span>
                    <small class="text-info">روز</small>
                </div>
                <div class="col">
                    <span data-hours>0</span>
                    <small class="text-info">ساعت</small>
                </div>
                <div class="col">
                    <span data-minutes>0</span>
                    <small class="text-info">دقیقه</small>
                </div>
                <div class="col">
                    <span data-seconds>0</span>
                    <small class="text-info">ثانیه</small>
                </div>
            </h5>

            <script type="text/javascript">
                $(function () {
                    $('.countdown_time').each(function () {
                        var endTime = $(this).data('time');
                        $(this).countdown(endTime);
                    });
                });
            </script>
        <?php endif; ?>
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