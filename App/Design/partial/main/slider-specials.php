<?php if (count($special_slider ?? [])): ?>
    <div class="section pt-0 pb-0">
        <div class="custom-container container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading_tab_header">
                        <div class="heading_s2">
                            <h4>تخفیف‌های جشنواره</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="product_slider carousel_slider owl-carousel owl-theme nav_style3" data-loop="true"
                         data-dots="false" data-nav="true" data-margin="30"
                         data-responsive='{"0":{"items": "1"}, "650":{"items": "2"}, "1199":{"items": "2"}}'>
                        <?php foreach ($special_slider as $item): ?>
                            <?php
                            $isComingSoon = DB_YES == $item['show_coming_soon'];
                            $callForMore = DB_YES == $item['call_for_more'];
                            $isAvailable = get_product_availability($item);
                            ?>

                            <div class="item">
                                <div class="deal_wrap">
                                    <div class="product_img">
                                        <a href="<?= url('home.product.show', [
                                            'id' => $item['product_id'],
                                            'slug' => $item['slug'],
                                        ]); ?>">
                                            <img src="<?= (config()->get('settings.default_image_placeholder.value') != '') ? url('image.show', ['filename' => config()->get('settings.default_image_placeholder.value')]) : ''; ?>"
                                                 data-src="<?= url('image.show') . $item['image']; ?>"
                                                 alt="<?= $item['title']; ?>" class="lazy">
                                        </a>
                                    </div>
                                    <div class="deal_content">
                                        <div class="product_info">
                                            <h5 class="product_title">
                                                <a href="<?= url('home.product.show', [
                                                    'id' => $item['product_id'],
                                                    'slug' => $item['slug'],
                                                ]); ?>">
                                                    <?= $item['title']; ?>
                                                </a>
                                            </h5>

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
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="product_price">
                                                    <span class="badge badge-danger d-block py-3">ناموجود</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!$isComingSoon && !$callForMore && $isAvailable): ?>
                                            <?php
                                            $discountExpire = getDiscountExpireTime($item);
                                            ?>
                                            <?php if (!empty($discountExpire)): ?>
                                                <div class="d-flex text-center countdown_style4 mb-4" countdown
                                                     data-date="<?= date('Y-m-d H:i:s', $discountExpire); ?>">
                                                    <div class="col bg-light ml-2 py-2">
                                                        <span class="d-block h2" data-days>0</span>
                                                        <small class="text-danger">روز</small>
                                                    </div>
                                                    <div class="col bg-light ml-2 py-2">
                                                        <span class="d-block h2" data-hours>0</span>
                                                        <small class="text-danger">ساعت</small>
                                                    </div>
                                                    <div class="col bg-light ml-2 py-2">
                                                        <span class="d-block h2" data-minutes>0</span>
                                                        <small class="text-danger">دقیقه</small>
                                                    </div>
                                                    <div class="col bg-light ml-2 py-2">
                                                        <span class="d-block h2" data-seconds>0</span>
                                                        <small class="text-danger">ثانیه</small>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>