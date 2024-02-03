<?php

use Sim\Utils\StringUtil;

$p1 = $products[0] ?? [];
$p2 = $products[1] ?? [];
$p3 = $products[2] ?? [];

if (empty($p1) && empty($p2) && empty($p3)) {
    redirect(url('home.search'));
}

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="custom-container container">
            <div class="row">
                <div class="col-md-12">
                    <div class="compare_box">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr class="pr_image">
                                    <td class="row_title">تصویر محصول</td>
                                    <td class="row_img __compare_dialog_box_viewer" style="cursor: pointer;"
                                        data-toggle="modal" data-target="#__compare_products">
                                        <?php if (!empty($p1)): ?>
                                            <img src="<?= url('image.show', ['filename' => $p1['image']]); ?>"
                                                 alt="<?= $p1['title']; ?>" width="auto" height="160px"
                                                 data-product-id="<?= $p1['id']; ?>"
                                                 style="max-width: 100%;">
                                        <?php endif; ?>
                                    </td>
                                    <td class="row_img __compare_dialog_box_viewer" style="cursor: pointer;"
                                        data-toggle="modal" data-target="#__compare_products">
                                        <?php if (!empty($p2)): ?>
                                            <img src="<?= url('image.show', ['filename' => $p2['image']]); ?>"
                                                 alt="<?= $p2['title']; ?>" width="auto" height="160px"
                                                 data-product-id="<?= $p2['id']; ?>"
                                                 style="max-width: 100%;">
                                        <?php else: ?>
                                            <div class="text-muted">
                                                <i class="linearicons-picture" style="font-size: 4rem;"
                                                   aria-hidden="true"></i>
                                                <div class="mt-2">
                                                    انتخاب محصول
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="row_img __compare_dialog_box_viewer" style="cursor: pointer;"
                                        data-toggle="modal" data-target="#__compare_products">
                                        <?php if (!empty($p3)): ?>
                                            <img src="<?= url('image.show', ['filename' => $p3['image']]); ?>"
                                                 alt="<?= $p3['title']; ?>" width="auto" height="160px"
                                                 data-product-id="<?= $p2['id']; ?>"
                                                 style="max-width: 100%;">
                                        <?php else: ?>
                                            <div class="text-muted">
                                                <i class="linearicons-picture" style="font-size: 4rem;"
                                                   aria-hidden="true"></i>
                                                <div class="mt-2">
                                                    انتخاب محصول
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="pr_title">
                                    <td class="row_title">نام محصول</td>
                                    <td class="product_name">
                                        <?php if (!empty($p1)): ?>
                                            <a href="<?= url('home.product.show', [
                                                'id' => $p1['id'],
                                                'slug' => $p1['title'],
                                            ])->getRelativeUrl(); ?>"><?= $p1['title']; ?></a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="product_name">
                                        <?php if (!empty($p2)): ?>
                                            <a href="<?= url('home.product.show', [
                                                'id' => $p2['id'],
                                                'slug' => $p2['title'],
                                            ])->getRelativeUrl(); ?>"><?= $p2['title']; ?></a>
                                        <?php else: ?>
                                            <?php load_partial('main/parser/dash-icon'); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="product_name">
                                        <?php if (!empty($p3)): ?>
                                            <a href="<?= url('home.product.show', [
                                                'id' => $p3['id'],
                                                'slug' => $p3['title'],
                                            ])->getRelativeUrl(); ?>"><?= $p3['title']; ?></a>
                                        <?php else: ?>
                                            <?php load_partial('main/parser/dash-icon'); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="pr_stock">
                                    <td class="row_title"></td>

                                    <td class="row_stock text-left">
                                        <?php if (count($products_property[$p1['id']])): ?>
                                            <?php foreach ($products_property[$p1['id']] as $k => $item): ?>
                                                <div class="product_color_switch">
                                                    <div class="<?= 0 != $k ? 'border-top' : ''; ?>">
                                                        <?php if (!empty($item['color_hex']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES)): ?>
                                                            <div class="mb-1">
                                                                <?php if ($item['is_patterned_color'] == DB_NO): ?>
                                                                    <span class="active"
                                                                          data-color="<?= $item['color_hex']; ?>"></span>
                                                                <?php endif; ?>
                                                                <?= $item['color_name']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['size'])): ?>
                                                            <div class="mx-1">
                                                                سایز:
                                                                <?= $item['size']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['guarantee'])): ?>
                                                            <div class="mx-1">
                                                                گارانتی:
                                                                <?= $item['guarantee']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <?php if (DB_YES == $p1['show_coming_soon']): ?>
                                                        <span class="in-stock text-info mx-1">بزودی</span>
                                                    <?php elseif (DB_YES == $p1['call_for_more']): ?>
                                                        <span class="in-stock mx-1">برای اطلاعات بیشتر تماس بگیرید</span>
                                                    <?php elseif (get_product_availability([
                                                        'product_availability' => $p1['is_available'],
                                                        'is_available' => $item['is_available'],
                                                        'stock_count' => $item['stock_count'],
                                                        'max_cart_count' => $item['max_cart_count'],
                                                    ])): ?>
                                                        <?php
                                                        [$price, $hasDiscount] = get_discount_price($item);
                                                        ?>
                                                        <?php if ($hasDiscount): ?>
                                                            <del>
                                                                <?= local_number(number_format(StringUtil::toEnglish($item['price']))); ?>
                                                                <small>تومان</small>
                                                            </del>
                                                        <?php endif; ?>

                                                        <span class="price mx-1">
                                                            <?= local_number(number_format(StringUtil::toEnglish($price))); ?>
                                                            <small>تومان</small>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="out-stock mx-1">تمام شده</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="row_stock text-left">
                                        <?php if (!empty($p2) && count($products_property[$p2['id']])): ?>
                                            <?php foreach ($products_property[$p2['id']] as $k => $item): ?>
                                                <div class="product_color_switch">
                                                    <div class="<?= 0 != $k ? 'border-top' : ''; ?>">
                                                        <?php if (!empty($item['color_hex']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES)): ?>
                                                            <div class="mb-1">
                                                                <?php if ($item['is_patterned_color'] == DB_NO): ?>
                                                                    <span class="active"
                                                                          data-color="<?= $item['color_hex']; ?>"></span>
                                                                <?php endif; ?>
                                                                <?= $item['color_name']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['size'])): ?>
                                                            <div class="mx-1">
                                                                سایز:
                                                                <?= $item['size']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['guarantee'])): ?>
                                                            <div class="mx-1">
                                                                گارانتی:
                                                                <?= $item['guarantee']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <?php if (DB_YES == $p2['show_coming_soon']): ?>
                                                        <span class="in-stock text-info mx-1">بزودی</span>
                                                    <?php elseif (DB_YES == $p2['call_for_more']): ?>
                                                        <span class="in-stock mx-1">برای اطلاعات بیشتر تماس بگیرید</span>
                                                    <?php elseif (get_product_availability([
                                                        'product_availability' => $p2['is_available'],
                                                        'is_available' => $item['is_available'],
                                                        'stock_count' => $item['stock_count'],
                                                        'max_cart_count' => $item['max_cart_count'],
                                                    ])): ?>
                                                        <?php
                                                        [$price, $hasDiscount] = get_discount_price($item);
                                                        ?>
                                                        <?php if ($hasDiscount): ?>
                                                            <del>
                                                                <?= local_number(number_format(StringUtil::toEnglish($item['price']))); ?>
                                                                <small>تومان</small>
                                                            </del>
                                                        <?php endif; ?>

                                                        <span class="price mx-1">
                                                            <?= local_number(number_format(StringUtil::toEnglish($price))); ?>
                                                            <small>تومان</small>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="out-stock mx-1">تمام شده</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="row_stock text-left">
                                        <?php if (!empty($p3) && count($products_property[$p3['id']])): ?>
                                            <?php foreach ($products_property[$p3['id']] as $k => $item): ?>
                                                <div class="product_color_switch">
                                                    <div class="<?= 0 != $k ? 'border-top' : ''; ?>">
                                                        <?php if (!empty($item['color_hex']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES)): ?>
                                                            <div class="mb-1">
                                                                <?php if ($item['is_patterned_color'] == DB_NO): ?>
                                                                    <span class="active"
                                                                          data-color="<?= $item['color_hex']; ?>"></span>
                                                                <?php endif; ?>
                                                                <?= $item['color_name']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['size'])): ?>
                                                            <div class="mx-1">
                                                                سایز:
                                                                <?= $item['size']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['guarantee'])): ?>
                                                            <div class="mx-1">
                                                                گارانتی:
                                                                <?= $item['guarantee']; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <?php if (DB_YES == $p3['show_coming_soon']): ?>
                                                        <span class="in-stock text-info mx-1">بزودی</span>
                                                    <?php elseif (DB_YES == $p3['call_for_more']): ?>
                                                        <span class="in-stock mx-1">برای اطلاعات بیشتر تماس بگیرید</span>
                                                    <?php elseif (get_product_availability([
                                                        'product_availability' => $p3['is_available'],
                                                        'is_available' => $item['is_available'],
                                                        'stock_count' => $item['stock_count'],
                                                        'max_cart_count' => $item['max_cart_count'],
                                                    ])): ?>
                                                        <?php
                                                        [$price, $hasDiscount] = get_discount_price($item);
                                                        ?>
                                                        <?php if ($hasDiscount): ?>
                                                            <del>
                                                                <?= local_number(number_format(StringUtil::toEnglish($item['price']))); ?>
                                                                <small>تومان</small>
                                                            </del>
                                                        <?php endif; ?>

                                                        <span class="price mx-1">
                                                            <?= local_number(number_format(StringUtil::toEnglish($price))); ?>
                                                            <small>تومان</small>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="out-stock mx-1">تمام شده</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php load_partial('main/parser/dash-icon'); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php foreach ($properties as $property): ?>
                                    <tr class="pr_title">
                                        <td class="row_title"><?= $property['title']; ?></td>

                                        <td colspan="3" class="p-0">
                                            <?php foreach ($property['children'] as $child): ?>
                                                <table class="w-100 text-center">
                                                    <tr>
                                                        <td colspan="3"
                                                            class="row_title text-center"><?= $child['title']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            $p1Props = $child['properties'][$p1['id']] ?? null;
                                                            ?>
                                                            <?php if (!empty($p1Props)): ?>
                                                                <?php
                                                                $parts = explode(',', $p1Props);
                                                                $parts = array_map('trim', $parts);
                                                                ?>
                                                                <?php if (count($parts)): ?>
                                                                    <?php $counter = 0; ?>
                                                                    <?php foreach ($parts as $part): ?>
                                                                        <div class="p-2 <?= 0 != $counter++ ? 'border-top' : ''; ?>">
                                                                            <?= $part; ?>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <div class="px-2 py-3">
                                                                        <?php load_partial('main/parser/dash-icon'); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php load_partial('main/parser/dash-icon'); ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if (!empty($p2['id'])): ?>
                                                                <?php
                                                                $p2Props = $child['properties'][$p2['id']] ?? null;
                                                                ?>
                                                                <?php if (!empty($p2Props)): ?>
                                                                    <?php
                                                                    $parts = explode(',', $p2Props);
                                                                    $parts = array_map('trim', $parts);
                                                                    ?>
                                                                    <?php if (count($parts)): ?>
                                                                        <?php $counter = 0; ?>
                                                                        <?php foreach ($parts as $part): ?>
                                                                            <div class="p-2 <?= 0 != $counter++ ? 'border-top' : ''; ?>">
                                                                                <?= $part; ?>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <div class="px-2 py-3">
                                                                            <?php load_partial('main/parser/dash-icon'); ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php load_partial('main/parser/dash-icon'); ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php load_partial('main/parser/dash-icon'); ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if (!empty($p3['id'])): ?>
                                                                <?php
                                                                $p3Props = $child['properties'][$p3['id']] ?? null;
                                                                ?>
                                                                <?php if (!empty($p3Props)): ?>
                                                                    <?php
                                                                    $parts = explode(',', $p3Props);
                                                                    $parts = array_map('trim', $parts);
                                                                    ?>
                                                                    <?php if (count($parts)): ?>
                                                                        <?php $counter = 0; ?>
                                                                        <?php foreach ($parts as $part): ?>
                                                                            <div class="p-2 <?= 0 != $counter++ ? 'border-top' : ''; ?>">
                                                                                <?= $part; ?>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <div class="px-2 py-3">
                                                                            <?php load_partial('main/parser/dash-icon'); ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php load_partial('main/parser/dash-icon'); ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php load_partial('main/parser/dash-icon'); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="pr_remove">
                                    <td class="row_title"></td>
                                    <td class="row_remove">
                                        <a href="<?= url('home.compare', [
                                            'p1' => $p2['id'] ?? false, 'p2' => $p3['id'] ?? false, 'p3' => false
                                        ])->getRelativeUrlTrimmed(); ?>">
                                            <span>حذف</span>
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                    <td class="row_remove">
                                        <?php if (count($p2)): ?>
                                            <a href="<?= url('home.compare', [
                                                'p1' => $p1['id'] ?? false, 'p2' => $p3['id'] ?? false, 'p3' => false
                                            ])->getRelativeUrlTrimmed(); ?>">
                                                <span>حذف</span>
                                                <i class="fa fa-times"></i>
                                            </a>
                                        <?php else: ?>
                                            <?php load_partial('main/parser/dash-icon'); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="row_remove">
                                        <?php if (count($p3)): ?>
                                            <a href="<?= url('home.compare', [
                                                'p1' => $p1['id'] ?? false, 'p2' => $p2['id'] ?? false, 'p3' => false
                                            ])->getRelativeUrlTrimmed(); ?>">
                                                <span>حذف</span>
                                                <i class="fa fa-times"></i>
                                            </a>
                                        <?php else: ?>
                                            <?php load_partial('main/parser/dash-icon'); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
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

    <!-- COMPARE PRODUCTS POP-UP -->
    <?php load_partial('main/parser/popup-compare-products', [
        'category_id' => $category_info['id'],
        'category_name' => $category_info['name'],
    ]); ?>
    <!-- COMPARE PRODUCTS POP-UP -->

</div>
<!-- END MAIN CONTENT -->