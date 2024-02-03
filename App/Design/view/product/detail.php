<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- Content area -->
<div class="content">

    <div class="form-group text-right">
        <a href="<?= url('admin.product.buyer', ['id' => $product['id']])->getRelativeUrlTrimmed(); ?>"
           class="btn bg-indigo-400 d-block d-sm-inline-block">
            خریداران این محصول
            <i class="icon-coins ml-2" aria-hidden="true"></i>
        </a>
    </div>

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشخصات اولیه محصول']); ?>

        <div class="img-placeholder-custom mx-auto has-image">
            <img class="img-placeholder-image"
                 src="<?= url('image.show')->getRelativeUrl() . $product['image']; ?>"
                 alt="selected image">
            <div class="img-placeholder-icon-container">
                <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                <div class="img-placeholder-num bg-warning text-white">
                    <i class="icon-plus2"></i>
                </div>
            </div>
        </div>
        <div class="row m-0">
            <div class="col-lg-12 border py-2 px-3">
                <div class="mb-2">
                    عنوان
                </div>
                <strong>
                    <?= $product['title']; ?>
                </strong>
            </div>
            <div class="col-lg-6 border py-2 px-3">
                <div class="mb-2">
                    واحد کالا
                </div>
                <strong>
                    <?= $product['unit_title'] . (!empty($product['unit_sign']) ? ' - ' . $product['unit_sign'] : ''); ?>
                </strong>
            </div>
            <div class="col-lg-6 border py-2 px-3">
                <div class="mb-2">
                    برند
                </div>
                <strong>
                    <?= $product['brand_name']; ?>
                </strong>
            </div>
            <div class="col-lg-6 border py-2 px-3">
                <div class="mb-2">
                    دسته‌بندی
                </div>
                <strong>
                    <?= $product['category_name']; ?>
                </strong>
            </div>
            <div class="col-lg-6 border py-2 px-3">
                <div class="mb-2">
                    تعداد کالا برای هشدار
                </div>
                <strong>
                    <?php if (!is_null($product['min_product_alert'])): ?>
                        <?= StringUtil::toPersian($product['min_product_alert']); ?>
                    <?php else: ?>
                        <?php load_partial('admin/parser/dash-icon'); ?>
                    <?php endif; ?>
                </strong>
            </div>
            <div class="col-lg-6 border py-2 px-3">
                <div class="mb-2">
                    ویژگی‌های سریع
                </div>
                <strong>
                    <?php if (!is_null($product['baby_property'])): ?>
                        <?= StringUtil::toPersian($product['baby_property']); ?>
                    <?php else: ?>
                        <?php load_partial('admin/parser/dash-icon'); ?>
                    <?php endif; ?>
                </strong>
            </div>
            <div class="col-lg-6 border py-2 px-3">
                <div class="mb-2">
                    کلمات کلیدی
                </div>
                <strong>
                    <?php if (!is_null($product['keywords'])): ?>
                        <?= StringUtil::toPersian($product['keywords']); ?>
                    <?php else: ?>
                        <?php load_partial('admin/parser/dash-icon'); ?>
                    <?php endif; ?>
                </strong>
            </div>

            <?php
            $isChecked = is_value_checked($product['publish']);
            ?>
            <div class="col-lg-6 border py-2 px-3 <?= $isChecked ? 'alert-success' : 'alert-danger' ?>">
                <div class="mb-2">
                    وضعیت نمایش
                </div>
                <strong>
                    <?php if ($isChecked): ?>
                        نمایش
                    <?php else: ?>
                        عدم نمایش
                    <?php endif; ?>
                </strong>
            </div>

            <?php
            $isChecked = is_value_checked($product['is_available']);
            ?>
            <div class="col-lg-6 border py-2 px-3 <?= $isChecked ? 'alert-success' : 'alert-danger' ?>">
                <div class="mb-2">
                    وضعیت موجودی
                </div>
                <strong>
                    <?php if ($isChecked): ?>
                        موجود
                    <?php else: ?>
                        ناموجود
                    <?php endif; ?>
                </strong>
            </div>

            <?php
            $isChecked = is_value_checked($product['is_special']);
            ?>
            <div class="col-lg-6 border py-2 px-3 <?= $isChecked ? 'alert-success' : 'alert-danger' ?>">
                <div class="mb-2">
                    محصول ویژه
                </div>
                <strong>
                    <?php if ($isChecked): ?>
                        بله
                    <?php else: ?>
                        خیر
                    <?php endif; ?>
                </strong>
            </div>

            <?php
            $isChecked = is_value_checked($product['is_returnable']);
            ?>
            <div class="col-lg-6 border py-2 px-3 <?= $isChecked ? 'alert-success' : 'alert-danger' ?>">
                <div class="mb-2">
                    امکان مرجوع کردن
                </div>
                <strong>
                    <?php if ($isChecked): ?>
                        بله
                    <?php else: ?>
                        خیر
                    <?php endif; ?>
                </strong>
            </div>

            <?php
            $isChecked = is_value_checked($product['allow_commenting']);
            ?>
            <div class="col-lg-6 border py-2 px-3 <?= $isChecked ? 'alert-success' : 'alert-danger' ?>">
                <div class="mb-2">
                    اجازه ارسال نظر
                </div>
                <strong>
                    <?php if ($isChecked): ?>
                        بله
                    <?php else: ?>
                        خیر
                    <?php endif; ?>
                </strong>
            </div>

            <?php
            $isChecked = is_value_checked($product['show_coming_soon']);
            ?>
            <div class="col-lg-6 border py-2 px-3 <?= $isChecked ? 'alert-success' : 'alert-danger' ?>">
                <div class="mb-2">
                    نمایش بزودی
                </div>
                <strong>
                    <?php if ($isChecked): ?>
                        بله
                    <?php else: ?>
                        خیر
                    <?php endif; ?>
                </strong>
            </div>

            <?php
            $isChecked = is_value_checked($product['call_for_more']);
            ?>
            <div class="col-lg-6 border py-2 px-3 <?= $isChecked ? 'alert-success' : 'alert-danger' ?>">
                <div class="mb-2">
                    تماس برای اطلاعات بیشتر
                </div>
                <strong>
                    <?php if ($isChecked): ?>
                        بله
                    <?php else: ?>
                        خیر
                    <?php endif; ?>
                </strong>
            </div>
        </div>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشخصات محصولات']); ?>

        <table class="table table-bordered datatable-highlight">
            <thead>
            <tr>
                <th>تعداد موجود</th>
                <th>بیشترین تعداد در سبد خرید</th>
                <th>رنگ</th>
                <th>سایز</th>
                <th>گارانتی</th>
                <th>قیمت</th>
                <th>قیمت با تخفیف</th>
                <th>تخفیف از تاریخ</th>
                <th>تخفیف تا تاریخ</th>
                <th>وضعیت موجودی</th>
                <th>مرسوله مجزا</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($product_property as $item): ?>
                <tr>
                    <td>
                        <?= StringUtil::toPersian($item['stock_count']); ?>
                    </td>
                    <td>
                        <?= StringUtil::toPersian($item['max_cart_count']); ?>
                    </td>
                    <td>
                        <?php if (!empty($item['color']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES)): ?>
                            <?php if ($item['is_patterned_color'] == DB_NO): ?>
                                <?php load_partial('admin/parser/color-shape', [
                                    'hex' => $item['color_hex'],
                                ]); ?>
                            <?php endif; ?>
                            <span class="d-inline-block ml-2"><?= $item['color_name']; ?></span>
                        <?php else: ?>
                            <?php load_partial('admin/parser/dash-icon'); ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $item['size']; ?></td>
                    <td><?= $item['guarantee']; ?></td>
                    <td data-order="<?= (int)StringUtil::toEnglish($item['price']); ?>">
                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['price']))); ?>
                        تومان
                    </td>
                    <td data-order="<?= (int)StringUtil::toEnglish($item['discounted_price']); ?>">
                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['discounted_price']))); ?>
                        تومان
                    </td>

                    <?php if (!is_null($item['discount_from'])): ?>
                        <td data-order="<?= (int)$item['discount_from']; ?>">
                            <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $item['discount_from']); ?>
                        </td>
                    <?php else: ?>
                        <td>
                            <?php load_partial('admin/parser/dash-icon'); ?>
                        </td>
                    <?php endif; ?>

                    <?php if (!is_null($item['discount_until'])): ?>
                        <td data-order="<?= (int)$item['discount_until']; ?>">
                            <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $item['discount_until']); ?>
                        </td>
                    <?php else: ?>
                        <td>
                            <?php load_partial('admin/parser/dash-icon'); ?>
                        </td>
                    <?php endif; ?>

                    <?php
                    $isChecked = is_value_checked($item['is_available']);
                    ?>
                    <td class="<?= $isChecked ? 'table-success' : 'table-danger'; ?>">
                        <?php if ($isChecked): ?>
                            موجود
                        <?php else: ?>
                            ناموجود
                        <?php endif; ?>
                    </td>

                    <?php
                    $isChecked = is_value_checked($item['separate_consignment']);
                    ?>
                    <td class="<?= $isChecked ? 'table-info' : 'table-warning'; ?>">
                        <?php if ($isChecked): ?>
                            بله
                        <?php else: ?>
                            خیر
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'محصولات مرتبط']); ?>

        <table class="table table-bordered datatable-highlight">
            <thead>
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>تصویر</th>
                <th>برند</th>
                <th>دسته‌بندی</th>
            </tr>
            </thead>
            <tbody>
            <?php $k = 1; ?>
            <?php foreach ($related as $item): ?>
                <tr>
                    <td data-order="<?= $k; ?>"><?= StringUtil::toPersian($k++); ?></td>
                    <td><?= $item['title']; ?></td>
                    <td>
                        <?php load_partial('admin/parser/image-placeholder', [
                            'img' => $item['image'],
                            'alt' => 'عنوان کالا',
                            'lightbox' => true,
                        ]) ?>
                    </td>
                    <td><?= $item['brand_name']; ?></td>
                    <td><?= $item['category_name']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Highlighting rows and columns -->
    <div class="card card-collapsed">
        <?php load_partial('admin/card-header', ['header_title' => 'گالری تصاویر']); ?>

        <div id="productImageGallery" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ul class="carousel-indicators">
                <?php for ($i = 0; $i < count($gallery); ++$i): ?>
                    <li class="<?= 0 == $i ? 'active' : ''; ?>"
                        data-target="#productImageGallery"
                        data-slide-to="<?= $i ?>"></li>
                <?php endfor; ?>
            </ul>

            <!-- The slideshow -->
            <div class="carousel-inner">
                <?php $k = 0; ?>
                <?php foreach ($gallery as $item): ?>
                    <div class="carousel-item <?= 0 == $k ? 'active' : ''; ?>">
                        <div class="d-flex justify-content-center">
                            <img class="image-gallery-max-height mx-auto"
                                 src="<?= url('image.show')->getRelativeUrl() . $item['image']; ?>"
                                 alt="Gallery Image <?= ++$k; ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Left and right controls -->
            <a class="carousel-control-prev bg-dark-alpha" href="#productImageGallery" data-slide="prev">
                <span class="carousel-control-next-icon"></span>
            </a>
            <a class="carousel-control-next bg-dark-alpha" href="#productImageGallery" data-slide="next">
                <span class="carousel-control-prev-icon"></span>
            </a>
        </div>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Highlighting rows and columns -->
    <div class="card card-collapsed">
        <?php load_partial('admin/card-header', ['header_title' => 'توضیحات']); ?>

        <div class="card-body">
            <textarea cols="30"
                      rows="10"
                      placeholder="توضیحات محصول..."
                      class="form-control cntEditor"
                      data-editor-readonly="true"
                      data-editor-menubar="false"
                      data-editor-statusbar="false"
                      data-editor-toolbar="false"
            ><?= $product['body']; ?></textarea>
        </div>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Highlighting rows and columns -->
    <div class="card card-collapsed">
        <?php load_partial('admin/card-header', ['header_title' => 'ویژگی‌های محصول']); ?>

        <div class="card-body">
            <?php
            $properties = json_decode($product['properties'], true);
            $properties = is_array($properties) ? $properties : [];
            ?>

            <?php if (count($properties)): ?>
                <?php foreach ($properties as $property): ?>
                    <h5 class="mt-5 mb-3 text-info"><?= $property['title']; ?></h5>

                    <?php if (isset($property['children']) && is_array($property['children']) && count($property['children']) > 0): ?>
                        <table class="table table-bordered">
                            <?php foreach ($property['children'] as $child): ?>
                                <tr>
                                    <td width="28%">
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
                                                <div class="p-2">
                                                    <i class="linearicons-minus"
                                                       aria-hidden="true"></i>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="p-2">
                                                <i class="linearicons-minus"
                                                   aria-hidden="true"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-info">هیچ ویژگی‌ای یافت نشد.</span>
            <?php endif; ?>
        </div>
    </div>
    <!-- /highlighting rows and columns -->

    <?php load_partial('editor/browser-tiny-func'); ?>
</div>
<!-- /content area -->
