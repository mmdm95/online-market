<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="custom-container container">
            <div class="row">
                <input type="hidden" value="<?= $product['product_id']; ?>" id="__current_product_id">

                <!-- START GALLERY -->
                <div class="col-lg-6 col-md-6 mb-5 mb-md-0">
                    <div class="product-image vertical_gallery">
                        <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-vertical="true"
                             data-vertical-swiping="true" data-slides-to-show="5" data-slides-to-scroll="5"
                             data-infinite="false">
                            <?php if (count($gallery ?? [])): ?>
                                <?php foreach ($gallery ?? [] as $k => $item): ?>
                                    <div class="item">
                                        <a href="javascript:void(0);"
                                           class="product_gallery_item <?= 0 == $k ? 'active' : ''; ?>"
                                           data-image="<?= url('image.show') . $item['image']; ?>"
                                           data-zoom-image="<?= url('image.show') . $item['image']; ?>">
                                            <img src="<?= url('image.show') . $item['image']; ?>"
                                                 alt="image gallery">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="item">
                                    <a href="javascript:void(0);"
                                       class="product_gallery_item active"
                                       data-image="<?= url('image.show') . $product['image']; ?>"
                                       data-zoom-image="<?= url('image.show') . $product['image']; ?>">
                                        <img src="<?= url('image.show') . $product['image']; ?>"
                                             alt="image gallery">
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="product_img_box">
                            <?php if (count($gallery ?? [])): ?>
                                <img id="product_img" src='<?= url('image.show') . $gallery[0]['image']; ?>'
                                     data-zoom-image="<?= url('image.show') . $gallery[0]['image']; ?>"
                                     alt="<?= $product['title']; ?>"/>
                            <?php else: ?>
                                <img id="product_img" src='<?= url('image.show') . $product['image']; ?>'
                                     data-zoom-image="<?= url('image.show') . $product['image']; ?>"
                                     alt="<?= $product['title']; ?>"/>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="product_img_zoom" title="Zoom">
                                <span class="linearicons-zoom-in"></span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END GALLERY -->

                <!-- START MAIN INFO -->
                <div class="col-lg-6 col-md-6">
                    <div class="pr_detail">
                        <div class="product_description">
                            <h4 class="product_title">
                                <a href="<?= url('home.product.show', [
                                    'id' => $product['product_id'],
                                    'slug' => $product['slug'],
                                ]); ?>">
                                    <?= $product['title']; ?>
                                </a>
                            </h4>

                            <!-- START PRODUCT PRICE -->
                            <div id="__product_price_container">
                            </div>
                            <!-- END PRODUCT PRICE -->

                            <!-- START BRIEF PROPERTY -->
                            <?php
                            $babyProperties = explode(',', $product['baby_property']);
                            $babyProperties = array_filter($babyProperties, function ($val) {
                                return !empty(trim($val));
                            });
                            ?>
                            <?php if (count($babyProperties)): ?>
                                <div class="product_sort_info my-3">
                                    <ul>
                                        <?php foreach ($babyProperties as $property): ?>
                                            <li>
                                                <i class="linearicons-check"></i>
                                                <?= trim($property); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <!-- END BRIEF PROPERTY -->

                            <!-- START CHANGEABLE STUFFS -->
                            <div>
                                <select name="changeable-stuffs"
                                        class="form-control d-block selectric_dropdown selectric-dropdown-products selectric_dropdown_changeable_stuffs">
                                    <?php $counter = 1; ?>
                                    <?php foreach ($colors_and_sizes as $k => $prd): ?>
                                        <option value='<?= $prd['code']; ?>' <?= 0 === $k ? 'selected="selected"' : ''; ?>
                                                data-color-hex="<?= is_value_checked($prd['show_color']) ? $prd['color_hex'] : ''; ?>"
                                                data-color-name="<?= (is_value_checked($prd['show_color']) || is_value_checked($prd['is_patterned_color'])) ? $prd['color_name'] : ''; ?>"
                                                data-size="<?= $prd['size']; ?>"
                                                data-guarantee="<?= $prd['guarantee']; ?>">

                                            <?php
                                            $isColorShowable = !empty($item['color_hex']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES);
                                            ?>
                                            <?php if ($isColorShowable): ?>
                                                <?= $prd['color_name']; ?>
                                            <?php endif; ?>
                                            <?php if (trim($prd['size']) != ''): ?>
                                                <?= ($isColorShowable ? ' - ' : '') . $prd['size']; ?>
                                            <?php endif; ?>
                                            <?php if (trim($prd['guarantee']) != ''): ?>
                                                <?= ($isColorShowable && trim($prd['size']) != '' ? ' - ' : '') . $prd['guarantee']; ?>
                                            <?php endif; ?>

                                            <?php if (!$isColorShowable && trim($prd['size']) == '' && trim($prd['guarantee']) == ''): ?>
                                                <?php if (count($colors_and_sizes) > 1): ?>
                                                    محصول شماره
                                                    <?= $counter++; ?>
                                                <?php else: ?>
                                                    محصول انتخابی
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- END CHANGEABLE STUFFS -->
                        </div>

                        <hr/>

                        <div class="cart_extra">
                            <div class="cart-product-quantity">
                                <div class="quantity">
                                    <input type="button" value="-" class="minus">
                                    <input type="text" name="quantity" value="1" title="Qty"
                                           class="qty" size="4" data-max-cart-count="1">
                                    <input type="button" value="+" class="plus">
                                </div>
                            </div>
                            <div class="cart_btn">
                                <button class="btn btn-fill-out btn-addtocart __add_to_cart_btn"
                                        id="__main_add_to_cart" type="button">
                                    <i class="icon-basket-loaded"></i>
                                    افزودن به سبد خرید
                                </button>
                                <a class="add_compare"
                                   href="<?= url('home.compare', ['p1' => $product['product_id']]); ?>"
                                   data-toggle="tooltip" data-placement="top" title="لیست مقایسه">
                                    <i class="linearicons-shuffle"></i>
                                </a>
                                <a class="add_wishlist <?= $is_in_wishlist ? 'active' : ''; ?>"
                                   href="javascript:void(0);" data-toggle="tooltip"
                                   data-placement="top" title="لیست علاقه مندی ها"
                                   data-product-id="<?= $product['product_id']; ?>">
                                    <span class="d-flex justify-content-center align-items-cneter p-1">
                                        <i class="linearicons-bookmark2"></i>
                                    </span>
                                </a>
                            </div>
                        </div>

                        <hr/>

                        <ul class="product-meta">
                            <!-- START BRAND NAME -->
                            <li>
                                برند:
                                <a href="<?= url('home.search', [], [
                                    'brands[]' => $product['brand_id'],
                                ]); ?>">
                                    <?= $product['brand_name']; ?>
                                </a>
                            </li>
                            <!-- END BRAND NAME -->

                            <!-- START KEYWORDS -->
                            <?php
                            $keywords = $product['keywords'];
                            $keywords = explode(',', $keywords);
                            $keywords = array_filter($keywords, function ($val) {
                                return !empty(trim($val));
                            });
                            ?>
                            <?php if (count($keywords)): ?>
                                <li>
                                    برچسب:
                                    <?php
                                    $len = count($keywords);
                                    ?>
                                    <?php for ($i = 0; $i < $len; ++$i): ?>
                                        <a href="<?= url('home.search', null, [
                                            'tag' => $keywords[$i],
                                        ]); ?>" rel="tag">
                                            <?= $keywords[$i]; ?>
                                        </a>
                                        <?php if ($i !== ($len - 1)): ?>
                                            ,
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </li>
                            <?php endif; ?>
                            <!-- END KEYWORDS -->
                        </ul>

                        <!-- START SHARE BUTTONS -->
                        <div class="product_share">
                            <span>اشتراک:</span>
                            <?php
                            $shareLink = rtrim(get_base_url(), '\\/') . url('home.product.show', [
                                    'id' => $product['product_id'],
                                    'slug' => $product['slug'],
                                ])->getRelativeUrl();
                            ?>
                            <ul class="social_icons">
                                <li>
                                    <a href="https://t.me/share/url?url=<?= $shareLink; ?>"
                                       onClick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                       target="_blank" title="اشتراک گذاری در تلگرام"
                                       data-toggle="tooltip" data-placement="top">
                                        <i class="linearicons-paper-plane"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/?url=<?= $shareLink; ?>"
                                       onClick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                       target="_blank" title="اشتراک گذاری در اینستاگرام"
                                       data-toggle="tooltip" data-placement="top">
                                        <i class="ion-social-instagram-outline"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="whatsapp://send?text=<?= $shareLink; ?>"
                                       data-action="share/whatsapp/share"
                                       onClick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                       target="_blank" title="اشتراک گذاری در واتس‌اَپ"
                                       data-toggle="tooltip" data-placement="top">
                                        <i class="ion-social-whatsapp-outline"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END SHARE BUTTONS -->
                    </div>
                </div>
                <!-- END MAIN INFO -->

            </div>

            <!-- START DIVIDER -->
            <div class="row">
                <div class="col-12">
                    <div class="large_divider clearfix"></div>
                </div>
            </div>
            <!-- END DIVIDER -->

            <!-- START RELATED PRODUCTS -->
            <?php load_partial('main/slider-general', [
                'info' => [
                    'title' => 'محصولات مرتبط',
                ],
                'slider' => $related_products ?? [],
                'slider_loop' => 'false',
            ]) ?>
            <!-- END RELATED PRODUCTS -->

            <!-- START DIVIDER -->
            <div class="row">
                <div class="col-12">
                    <div class="small_divider"></div>
                    <div class="divider"></div>
                    <div class="medium_divider"></div>
                </div>
            </div>
            <!-- END DIVIDER -->

            <div class="row">
                <div class="col-12">
                    <div class="tab-style3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="Description-tab" data-toggle="tab" href="#Description"
                                   role="tab" aria-controls="Description" aria-selected="true">بررسی اجمالی</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Additional-info-tab" data-toggle="tab" href="#Additional-info"
                                   role="tab" aria-controls="Additional-info" aria-selected="false">مشخصات فنی</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Reviews-tab" data-toggle="tab" href="#Reviews" role="tab"
                                   aria-controls="Reviews" aria-selected="false">
                                    نظرات (
                                    <?= local_number($comments_count ?? ''); ?>
                                    )
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content shop_info_tab">

                            <!-- START PRODUCT DESCRIPTION -->
                            <div class="tab-pane fade show active" id="Description" role="tabpanel"
                                 aria-labelledby="Description-tab">
                                <?= $product['body'] ?: 'هیچ توضیحی وجود ندارد.'; ?>
                            </div>
                            <!-- END PRODUCT DESCRIPTION -->

                            <!-- START PRODUCT PROPERTIES -->
                            <div class="tab-pane fade" id="Additional-info" role="tabpanel"
                                 aria-labelledby="Additional-info-tab">
                                <?php
                                $properties = json_decode($product['properties'], true);
                                $properties = is_array($properties) ? $properties : [];
                                ?>

                                <?php if (count($properties)): ?>
                                    <?php foreach ($properties as $property): ?>
                                        <h5 class="mt-5 mb-3 text-info"><?= $property['title']; ?></h5>

                                        <?php if (isset($property['children']) && is_array($property['children']) && count($property['children']) > 0): ?>
                                            <table class="table">
                                                <?php foreach ($property['children'] as $child): ?>
                                                    <tr>
                                                        <td>
                                                            <?= $child['title']; ?>
                                                        </td>
                                                        <td class="p-0">
                                                            <?php if (trim($child['properties']) != ''): ?>
                                                                <?php
                                                                $parts = explode(',', $child['properties']);
                                                                $parts = array_map('trim', $parts);
                                                                ?>
                                                                <?php if (count($parts)): ?>
                                                                    <?php $counter = 0; ?>
                                                                    <?php foreach ($parts as $part): ?>
                                                                        <div class="p-2 <?= 0 != $counter ? 'border-top' : ''; ?>">
                                                                            <?= $part; ?>
                                                                        </div>
                                                                        <?php ++$counter; ?>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <div class="px-2 py-3">
                                                                        <?php load_partial('main/parser/dash-icon'); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <div class="px-2 py-3">
                                                                    <?php load_partial('main/parser/dash-icon'); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-info">هیچ ویژگی‌ای یافت نشد</span>
                                <?php endif; ?>
                            </div>
                            <!-- END PRODUCT PROPERTIES -->

                            <!-- START PRODUCT COMMENTS -->
                            <div class="tab-pane fade" id="Reviews" role="tabpanel" aria-labelledby="Reviews-tab">
                                <div class="comments">
                                    <h5 class="product_tab_title mb-5">
                                        <?= local_number($comments_count ?? ''); ?>
                                        نظر برای
                                        <span><?= $product['title']; ?></span>
                                    </h5>
                                    <?php if ($product['allow_commenting'] == DB_YES): ?>
                                        <?php if ($is_commenting_allowed): ?>
                                            <div class="review_form field_form">
                                                <h5>ارسال نظرات</h5>
                                                <form class="row mt-3" id="__form_product_detail_comment">
                                                    <div class="form-group col-md-6">
                                                        <input required="required" placeholder="نام خود را وارد کنید *"
                                                               class="form-control" name="inp-comment-name"
                                                            <?= isset($user['first_name']) && !empty(trim($user['first_name'])) ? 'readonly="readonly"' : ''; ?>
                                                               type="text" value="<?= $user['first_name'] ?: ''; ?>">
                                                    </div>
                                                    <div class="form-group col-12">
                                                        <textarea required="required" placeholder="نظر شما *"
                                                                  class="form-control" rows="4"
                                                                  name="inp-comment-message"></textarea>
                                                    </div>

                                                    <div class="form-group col-12">
                                                        <button type="submit" class="btn btn-fill-out" name="submit"
                                                                value="Submit">
                                                            ارسال نظر
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center">
                                                <span>برای ارسال نظر، ابتدا باید به پنل کاربری خود وارد شوید.</span>
                                                <a class="btn btn-fill-line ml-3" href="<?= url('home.login', null, [
                                                    'back_url' => url('home.product.show', [
                                                        'id' => $product['product_id'],
                                                        'slug' => $product['slug'],
                                                    ])->getRelativeUrl(),
                                                ]); ?>">
                                                    وارد شوید
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <h5>امکان ارسال نظر برای این محصول، وجود ندارد.</h5>
                                    <?php endif; ?>

                                    <!-- START DIVIDER -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="small_divider"></div>
                                            <div class="divider"></div>
                                            <div class="small_divider"></div>
                                        </div>
                                    </div>
                                    <!-- END DIVIDER -->

                                    <div id="__product_comments_container">
                                    </div>
                                </div>
                            </div>
                            <!-- END PRODUCT COMMENTS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->