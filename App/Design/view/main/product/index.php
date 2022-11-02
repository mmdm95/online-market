<?php

use Sim\Utils\ArrayUtil;
use Sim\Utils\StringUtil;

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="custom-container container">
            <div class="row" id="__sticky_sidebar_container">
                <div class="col-lg-9">
                    <div class="row align-items-center mb-4 pb-1">
                        <div class="col-12">
                            <div class="product_header">
                                <div class="product_header_left">
                                    <div class="custom_select">
                                        <?php
                                        $sort = ArrayUtil::get($_GET, 'sort', PRODUCT_ORDERING_NEWEST);
                                        ?>
                                        <select class="form-control form-control-sm" id="__product_sort">
                                            <?php foreach (PRODUCT_ORDERINGS as $k => $ordering): ?>
                                                <option value="<?= $k; ?>" <?= $sort == $k ? 'selected="selected"' : ''; ?>>
                                                    <?= $ordering ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row shop_container grid" id="__main_product_container"
                         data-category="<?= $category ?? '-1'; ?>">
                    </div>
                </div>
                <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                    <div class="sidebar" id="__the_sticky_sidebar">
                        <?php if (count($side_categories ?? [])): ?>
                            <div class="widget">
                                <h5 class="widget_title">دسته بندی ها</h5>
                                <ul class="widget_categories max-widget-height list-unstyled">
                                    <?php
                                    $extraParams = [];
                                    if (isset($festival)) {
                                        $extraParams = [
                                            'festival' => $festival,
                                        ];
                                    }
                                    ?>
                                    <?php foreach ($side_categories as $cat): ?>
                                        <li class="mb-1">
                                            <a href="<?= url('home.search', [
                                                'category' => $cat['id'],
                                                'category_slug' => StringUtil::slugify($cat['name']),
                                            ], $extraParams)->getRelativeUrlTrimmed(); ?>">
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
                        <div class="widget d-none">
                            <button class="btn btn-danger btn-block" id="__remove-all-filters">
                                <i class="linearicons-trash2" aria-hidden="true"></i>
                                حذف تمامی فیلترها
                            </button>
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
                        <?php if (count($dynamicAttrs ?? [])): ?>
                            <?php foreach ($dynamicAttrs as $id => $attr): ?>
                                <div class="widget">
                                    <h5 class="widget_title"><?= $attr['title']; ?></h5>
                                    <ul class="list_attrs max-widget-height list-unstyled">
                                        <?php
                                        $previousAttrs = ArrayUtil::get($_GET, 'attrs_' . $id, []);
                                        $previousAttrs = !is_array($previousAttrs) ? [] : $previousAttrs;
                                        $previousAttrs = array_map('urldecode', $previousAttrs);
                                        ?>
                                        <?php foreach ($attr['values'] as $id2 => $value): ?>
                                            <li class="mb-1">
                                                <?php if ($attr['type'] == PRODUCT_SIDE_SEARCH_ATTR_TYPE_MULTI_SELECT): ?>
                                                    <div class="custome-checkbox">
                                                        <input class="form-check-input product_attr_switch-<?= $id2; ?>"
                                                               type="checkbox" id="attr_num_<?= $id2; ?>"
                                                               value="<?= $id2; ?>"
                                                               data-attr-id="<?= $id; ?>"
                                                            <?= in_array($id2, $previousAttrs) ? 'checked="checked"' : ''; ?>>
                                                        <label class="form-check-label"
                                                               for="attr_num_<?= $id2; ?>">
                                                            <span><?= $value; ?></span>
                                                        </label>
                                                    </div>
                                                <?php elseif ($attr['type'] == PRODUCT_SIDE_SEARCH_ATTR_TYPE_SINGLE_SELECT): ?>
                                                    <div class="custome-radio">
                                                        <input class="form-check-input product_attr_switch-<?= $id2; ?>"
                                                               name="attr_radio_<?= $id; ?>"
                                                               type="radio" id="attr_radio_num_<?= $id2; ?>"
                                                               value="<?= $id2; ?>"
                                                               data-attr-id="<?= $id; ?>"
                                                            <?= in_array($id2, $previousAttrs) ? 'checked="checked"' : ''; ?>>
                                                        <label class="form-check-label"
                                                               for="attr_radio_num_<?= $id2; ?>">
                                                            <span><?= $value; ?></span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (count($brands ?? [])): ?>
                            <div class="widget">
                                <h5 class="widget_title">برندها</h5>
                                <ul class="list_brand max-widget-height list-unstyled">
                                    <?php
                                    $previousBrands = ArrayUtil::get($_GET, 'brands', []);
                                    $previousBrands = !is_array($previousBrands) ? [] : $previousBrands;
                                    $previousBrands = array_map('urldecode', $previousBrands);
                                    ?>
                                    <?php foreach ($brands as $id => $brand): ?>
                                        <li class="mb-1">
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
                                    $previousSizes = is_string($previousSizes) ? [$previousSizes] : ($previousSizes ?: []);
                                    $previousSizes = array_map('urldecode', $previousSizes);
                                    $previousSizes = array_unique($previousSizes);
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
                                    $previousColors = is_string($previousColors) ? [$previousColors] : ($previousColors ?: []);
                                    $previousColors = array_map('urldecode', $previousColors);
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