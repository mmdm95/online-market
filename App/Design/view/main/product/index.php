<?php

use Sim\Utils\ArrayUtil;
use Sim\Utils\StringUtil;

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <div class="row" id="__sticky_sidebar_container">
                <div class="col-lg-9">
                    <div class="row align-items-center mb-4 pb-1">
                        <div class="col-12">
                            <div class="product_header">
                                <div class="product_header_left">
                                    <div class="custom_select">
                                        <select class="form-control form-control-sm" id="__product_sort">
                                            <?php foreach (PRODUCT_ORDERINGS as $k => $ordering): ?>
                                                <option value="<?= $k; ?>"><?= $ordering ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="product_header_right">
                                    <div class="products_view">
                                        <a href="javascript:void(0);" class="shorting_icon grid"><i class="ti-view-grid"></i></a>
                                        <a href="javascript:void(0);" class="shorting_icon list active"><i class="ti-layout-list-thumb"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row shop_container list" id="__main_product_container"
                         data-category="<?= $category ?? '-1'; ?>">
                    </div>
                </div>
                <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                    <div class="sidebar" id="__the_sticky_sidebar">
                        <?php if (count($side_categories ?? [])): ?>
                            <div class="widget">
                                <h5 class="widget_title">دسته بندی ها</h5>
                                <ul class="widget_categories max-widget-height">
                                    <?php foreach ($side_categories as $cat): ?>
                                        <li>
                                            <a href="<?= url('home.search', [
                                                'category' => $cat['id'],
                                                'category_slug' => StringUtil::slugify($cat['name']),
                                            ])->getRelativeUrlTrimmed(); ?>">
                                                <span class="categories_name"><?= $cat['name']; ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="widget">
                            <h5 class="widget_title">جستجو در محصولات</h5>
                            <div class="search_form">
                                <form action="<?= url('home.blog'); ?>" method="get" id="__product_search">
                                    <input class="form-control" placeholder="جستجو..."
                                           type="text" name="inp-product-search-side"
                                           value="<?= ArrayUtil::get($_GET, 'q', ''); ?>">
                                    <button type="submit" title="جستجو" class="btn icon_search"
                                            name="submit" value="Submit">
                                        <i class="ion-ios-search-strong"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php if (0 !== ($max_price ?? 0)): ?>
                            <?php
                            $previousPrices = ArrayUtil::get($_GET, 'price', []);
                            $previousMinPrice = $previousPrices['min'] ?? 0;
                            $previousMaxPrice = $previousPrices['max'] ?? null;
                            ?>
                            <div class="widget">
                                <h5 class="widget_title">فیلتر قیمت</h5>
                                <div class="filter_price">
                                    <div id="price_filter" data-min="0" data-max="<?= $max_price; ?>"
                                         data-min-value="<?= $previousMinPrice ?>"
                                         data-max-value="<?= $previousMaxPrice ?? $max_price; ?>"
                                         data-price-sign="تومان"></div>
                                    <div class="price_range">
                                        <span>قیمت: <span id="flt_price"></span></span>
                                        <input type="hidden" id="price_second" value="<?= $previousMaxPrice ?? ''; ?>">
                                        <input type="hidden" id="price_first"
                                               value="<?= $previousMinPrice && 0 !== $previousMinPrice ? $previousMinPrice : ''; ?>">
                                    </div>
                                    <button type="button" disabled class="mt-3 btn btn-info btn-block"
                                            id="__search_product_price_filter">
                                        اعمال فیلتر
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (count($brands ?? [])): ?>
                            <div class="widget">
                                <h5 class="widget_title">برندها</h5>
                                <ul class="list_brand max-widget-height">
                                    <?php
                                    $previousBrands = ArrayUtil::get($_GET, 'brands', []);
                                    $previousBrands = !is_array($previousBrands) ? [] : $previousBrands;
                                    ?>
                                    <?php foreach ($brands as $id => $brand): ?>
                                        <li>
                                            <div class="custome-checkbox">
                                                <input class="form-check-input product_brand_switch"
                                                       type="checkbox" id="<?= 'brand_num_' . $id; ?>"
                                                       value="<?= $brand['id']; ?>"
                                                    <?= in_array($brand['id'], $previousBrands) ? 'checked="checked"' : ''; ?>>
                                                <label class="form-check-label" for="<?= 'brand_num_' . $id; ?>">
                                                    <span><?= $brand['name']; ?></span>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (count($sizes ?? [])): ?>
                            <div class="widget">
                                <h5 class="widget_title">سایزها</h5>
                                <div class="max-widget-height">
                                    <?php
                                    $previousSizes = ArrayUtil::get($_GET, 'size', []);
                                    ?>
                                    <?php foreach ($sizes as $size): ?>
                                        <span class="product_size_switch product_size_switch_multi">
                                            <span class="<?= in_array($size, $previousSizes) ? 'active' : ''; ?>"
                                                  data-value="<?= $size; ?>"><?= $size; ?></span>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (count($colors ?? [])): ?>
                            <div class="widget">
                                <h5 class="widget_title">رنگ ها</h5>
                                <div class="max-widget-height">
                                    <?php
                                    $previousColors = ArrayUtil::get($_GET, 'color', []);
                                    ?>
                                    <?php foreach ($colors as $color): ?>
                                        <div class="product_color_switch product_color_switch_multi d-inline-block d-lg-flex justify-content-between">
                                            <div class="d-none d-lg-block <?= in_array($color['hex'], $previousColors) ? 'active' : ''; ?>">
                                                <?= $color['name']; ?>
                                            </div>
                                            <span data-color="<?= $color['hex']; ?>" data-toggle="tooltip"
                                                  data-placement="top" title="<?= $color['name']; ?>"></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="widget">
                            <div class="d-flex align-items-center">
                                <input class="mswitch" type="checkbox"
                                       name="availableProducts" id="available_product"
                                    <?= is_value_checked(ArrayUtil::get($_GET, 'is_available', '')) ? 'checked="checked"' : ''; ?>>
                                <label for="available_product" class="ml-3 my-0 user-select-none">کالاهای موجود</label>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="d-flex align-items-center">
                                <input class="mswitch" type="checkbox"
                                       name="offerProducts" id="offer_product"
                                    <?= is_value_checked(ArrayUtil::get($_GET, 'is_special', '')) ? 'checked="checked"' : ''; ?>>
                                <label for="offer_product" class="ml-3 my-0 user-select-none">پیشنهادهای ویژه</label>
                            </div>
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