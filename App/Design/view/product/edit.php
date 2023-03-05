<?php

use App\Logic\Models\ProductModel;

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <form action="<?= url('admin.product.edit')->getRelativeUrlTrimmed(); ?>" method="post"
          id="__form_edit_product">
        <?php load_partial('admin/message/message-form', [
            'errors' => $product_edit_errors ?? [],
            'success' => $product_edit_success ?? '',
            'warning' => $product_edit_warning ?? '',
        ]); ?>
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>

        <div class="row">

            <!-- Product information -->
            <div class="col-lg-12">
                <div class="card">
                    <?php load_partial('admin/card-header', ['header_title' => 'مشخصات اولیه محصول']); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-block">
                                    <div class="form-group text-center">
                                        <label>
                                            <span class="text-danger">*</span>
                                            انتخاب تصویر شاخص:
                                        </label>
                                        <?php
                                        $img = $validator->setInput('inp-edit-product-img') ?: $product['image'];
                                        ?>
                                        <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto <?= !empty($img) ? 'has-image' : ''; ?>"
                                             data-toggle="modal"
                                             data-target="#modal_efm">
                                            <input type="hidden" name="inp-edit-product-img"
                                                   value="<?= $img; ?>">
                                            <?php if (!empty($img)): ?>
                                                <img class="img-placeholder-image"
                                                     src="<?= url('image.show', ['filename' => $img]); ?>"
                                                     alt="selected image">
                                            <?php endif; ?>
                                            <div class="img-placeholder-icon-container">
                                                <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                                <div class="img-placeholder-num bg-warning text-white">
                                                    <i class="icon-plus2"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-7">
                                <label>
                                    <span class="text-danger">*</span>
                                    عنوان محصول:
                                </label>
                                <input type="text" class="form-control" placeholder="وارد کنید"
                                       name="inp-edit-product-title"
                                       value="<?= $validator->setInput('inp-edit-product-title') ?: $product['title']; ?>">
                            </div>
                            <div class="form-group col-lg-5">
                                <label>
                                    واحد:
                                </label>
                                <select data-placeholder="واحد کالا را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-edit-product-unit" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            disabled="disabled"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($units as $unit): ?>
                                        <option value="<?= $unit['id']; ?>"
                                            <?= $validator->setSelect('inp-edit-product-unit', $unit['id']); ?>>
                                            <?= $unit['title'] . (!empty($unit['sign']) ? ' - ' . $unit['sign'] : ''); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text text-muted">
                                    <?= $product['unit_title'] . (!empty($product['unit_sign']) ? ' - ' . $product['unit_sign'] : ''); ?>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>
                                    <span class="text-danger">*</span>
                                    برند:
                                </label>
                                <select data-placeholder="برند را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        multiple="multiple"
                                        name="inp-edit-product-brand" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            disabled="disabled"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand['id']; ?>"
                                            <?= $product['brand_id'] == $brand['id'] ? 'selected="selected"' : ''; ?>>
                                            <?= $brand['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-5">
                                <label>
                                    <span class="text-danger">*</span>
                                    دسته‌بندی:
                                </label>
                                <select data-placeholder="دسته‌بندی را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-edit-product-category" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            disabled="disabled"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id']; ?>"
                                            <?= $product['category_id'] == $category['id'] ? 'selected="selected"' : ''; ?>>
                                            <?= $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>تعداد کالا برای هشدار:</label>
                                <input type="text" class="form-control" placeholder="از نوع عددی"
                                       name="inp-edit-product-alert-product"
                                       value="<?= $validator->setInput('inp-edit-product-alert-product') ?: $product['min_product_alert']; ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>ویژگی‌های سریع:</label>
                                <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                                       name="inp-edit-product-simple-properties"
                                       value="<?= $validator->setInput('inp-edit-product-simple-properties') ?: $product['baby_property']; ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>کلمات کلیدی:</label>
                                <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                                       name="inp-edit-product-keywords"
                                       value="<?= $validator->setInput('inp-edit-product-keywords') ?: $product['keywords']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product information -->

            <!-- Products property -->
            <div class="col-lg-12">
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            محصولات
                            <span class="text-danger small ml-1">(وارد کردن یک محصول الزامیست)</span>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary">
                            مواردی که رنگ آنها انتخاب نشده، در نظر گرفته نمی‌شود.
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-primary flat-icon __duplicator_btn"
                                    data-container-element=".__all_products_container"
                                    data-sample-element="#__sample_all_product"
                                    data-clearable-elements='["inp-edit-product-stock-count[]","inp-edit-product-max-count[]","inp-edit-product-color[]","inp-edit-product-size[]","inp-edit-product-weight[]","inp-edit-product-guarantee[]","inp-edit-product-price[]","inp-edit-product-discount-price[]","inp-edit-product-discount-date[]","inp-edit-product-product-availability[]","inp-edit-product-consider-discount-date[]"]'
                                    data-alt-field='["inp-edit-product-discount-date-tmp[]"]'
                                    data-add-remove="true"
                                    data-removable-elements='[".product-color-badge"]'>
                                افزودن محصول جدید
                                <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body __all_products_container">
                        <?php
                        $stockCounts = input()->post('inp-edit-product-stock-count');
                        ?>
                        <?php if ((count($product_edit_errors ?? [])) && is_array($stockCounts) && count($stockCounts)): ?>
                            <?php $counter = 0; ?>
                            <?php foreach ($stockCounts as $count): ?>
                                <fieldset
                                        class="position-relative form-group" <?= 0 === $counter ? 'id="__sample_all_product"' : ''; ?>>
                                    <div class="row px-3 pb-3 m-0 border-dashed border-2 border-info rounded">
                                        <div class="mt-3 col-md-6 col-xl-2">
                                            <label>
                                                <span class="text-danger">*</span>
                                                تعداد موجود:
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-edit-product-stock-count[]"
                                                   value="<?= $validator->setInput('inp-edit-product-stock-count.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                بیشترین تعداد در سبد خرید:
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-edit-product-max-count[]"
                                                   value="<?= $validator->setInput('inp-edit-product-max-count.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-4">
                                            <label>
                                                <span class="text-danger">*</span>
                                                رنگ:
                                            </label>
                                            <select data-placeholder="رنگ را انتخاب کنید..."
                                                    class="form-control form-control-select2-colors"
                                                    name="inp-edit-product-color[]" data-fouc>
                                                <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                        selected="selected">
                                                    انتخاب کنید
                                                </option>
                                                <?php foreach ($colors as $color): ?>
                                                    <option value="<?= $color['hex']; ?>"
                                                            data-color="<?= $color['hex']; ?>"
                                                        <?= $validator->setSelect('inp-edit-product-color.' . $counter, $color['hex']) ?: ($color['hex'] == ($product_properties[$counter]['color_hex'] ?? '') ? 'selected="selected"' : ''); ?>>
                                                        <?= $color['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text text-muted d-flex align-items-center product-color-badge">
                                                <?php load_partial('admin/parser/color-shape', ['hex' => $product_properties[$counter]['color_hex'] ?? 'transparent']); ?>
                                                <span class="mx-1"><?= $product_properties[$counter]['color_name'] ?? 'نامشخص'; ?></span>
                                            </div>
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>سایز:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید"
                                                   name="inp-edit-product-size[]"
                                                   value="<?= $validator->setInput('inp-edit-product-size.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>گارانتی:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید"
                                                   name="inp-edit-product-guarantee[]"
                                                   value="<?= $validator->setInput('inp-edit-product-guarantee.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                وزن با بسته‌بندی(گرم):
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-edit-product-weight[]"
                                                   value="<?= $validator->setInput('inp-edit-product-weight.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                قیمت:
                                            </label>
                                            <input type="text" class="form-control" placeholder="به تومان"
                                                   name="inp-edit-product-price[]"
                                                   value="<?= $validator->setInput('inp-edit-product-price.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                قیمت با تخفیف:
                                            </label>
                                            <input type="text" class="form-control" placeholder="به تومان"
                                                   name="inp-edit-product-discount-price[]"
                                                   value="<?= $validator->setInput('inp-edit-product-discount-price.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-lg-4">
                                            <label>تخفیف تا تاریخ:</label>
                                            <?php
                                            $sd = date('Y/m/d H:i', $validator->setInput('inp-add-product-discount-date.' . $counter, time()));
                                            ?>
                                            <input type="hidden" name="inp-edit-product-discount-date[]"
                                                   id="altDate<?= $counter; ?>">
                                            <input type="text" class="form-control myDatepickerWithEn"
                                                   placeholder="انتخاب تاریخ" readonly data-ignored
                                                   name="inp-edit-product-discount-date-tmp[]"
                                                   data-alt-field="#altDate<?= $counter; ?>"
                                                   data-format="YYYY/MM/DD HH:mm"
                                                   data-time="true"
                                                   value="<?= $sd; ?>">
                                        </div>
                                        <div class="mt-3 col alert-warning d-flex align-items-center rounded">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox"
                                                           name="inp-edit-product-consider-discount-date[]"
                                                        <?= $validator->setCheckbox('inp-edit-product-consider-discount-date.' . $counter, '') ?>
                                                           value="<?= $counter; ?>"
                                                           class="styled form-input-styled">
                                                    عدم درنظرگیری تاریخ تخفیف
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mt-3 col">
                                            <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                                <label class="form-check-label">
                                                    موجود
                                                    <input type="checkbox" class="form-check-input-switchery"
                                                           name="inp-edit-product-product-availability[]"
                                                        <?= $validator->setCheckbox('inp-edit-product-product-availability.' . $counter, 'on', true); ?>>
                                                    ناموجود
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (0 !== $counter++): ?>
                                        <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                    <?php endif; ?>
                                </fieldset>
                            <?php endforeach; ?>
                        <?php elseif (count($product_properties)): ?>
                            <?php $counter = 0; ?>
                            <?php foreach ($product_properties as $property): ?>
                                <fieldset
                                        class="position-relative form-group" <?= 0 === $counter ? 'id="__sample_all_product"' : ''; ?>>
                                    <div class="row px-3 pb-3 m-0 border-dashed border-2 border-info rounded">
                                        <div class="mt-3 col-md-6 col-xl-2">
                                            <label>
                                                <span class="text-danger">*</span>
                                                تعداد موجود:
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-edit-product-stock-count[]"
                                                   value="<?= $property['stock_count']; ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                بیشترین تعداد در سبد خرید:
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-edit-product-max-count[]"
                                                   value="<?= $property['max_cart_count']; ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-4">
                                            <label>
                                                <span class="text-danger">*</span>
                                                رنگ:
                                            </label>
                                            <select data-placeholder="رنگ را انتخاب کنید..."
                                                    class="form-control form-control-select2-colors"
                                                    name="inp-edit-product-color[]" data-fouc>
                                                <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                        selected="selected">
                                                    انتخاب کنید
                                                </option>
                                                <?php foreach ($colors as $color): ?>
                                                    <option value="<?= $color['hex']; ?>"
                                                            data-color="<?= $color['hex']; ?>"
                                                        <?= $property['color_hex'] == strtolower($color['hex']) ? 'checked="checked"' : ''; ?>>
                                                        <?= $color['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text text-muted d-flex align-items-center product-color-badge">
                                                <?php load_partial('admin/parser/color-shape', ['hex' => $property['color_hex']]); ?>
                                                <span class="mx-1"><?= $property['color_name']; ?></span>
                                            </div>
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>سایز:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید"
                                                   name="inp-edit-product-size[]"
                                                   value="<?= $property['size']; ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>گارانتی:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید"
                                                   name="inp-edit-product-guarantee[]"
                                                   value="<?= $property['guarantee']; ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                وزن با بسته‌بندی(گرم):
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-edit-product-weight[]"
                                                   value="<?= $property['weight']; ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                قیمت:
                                            </label>
                                            <input type="text" class="form-control" placeholder="به تومان"
                                                   name="inp-edit-product-price[]"
                                                   value="<?= $property['price']; ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                قیمت با تخفیف:
                                            </label>
                                            <input type="text" class="form-control" placeholder="به تومان"
                                                   name="inp-edit-product-discount-price[]"
                                                   value="<?= $property['discounted_price']; ?>">
                                        </div>
                                        <div class="mt-3 col-lg-4">
                                            <label>تخفیف تا تاریخ:</label>
                                            <?php
                                            $sd = date('Y/m/d H:i', $property['discount_until'] ?: time());
                                            ?>
                                            <input type="hidden" name="inp-edit-product-discount-date[]"
                                                   id="altDate<?= $counter; ?>">
                                            <input type="text" class="form-control myDatepickerWithEn"
                                                   placeholder="انتخاب تاریخ" readonly data-ignored
                                                   name="inp-edit-product-discount-date-tmp[]"
                                                   data-alt-field="#altDate<?= $counter; ?>"
                                                   data-format="YYYY/MM/DD HH:mm"
                                                   data-time="true"
                                                   value="<?= $sd; ?>">
                                        </div>
                                        <div class="mt-3 col alert-warning d-flex align-items-center rounded">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox"
                                                           name="inp-edit-product-consider-discount-date[]"
                                                        <?= empty($property['discount_until']) ? 'checked="checked"' : ''; ?>
                                                           value="<?= $counter; ?>"
                                                           class="styled form-input-styled">
                                                    عدم درنظرگیری تاریخ تخفیف
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mt-3 col">
                                            <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                                <label class="form-check-label">
                                                    موجود
                                                    <input type="checkbox" class="form-check-input-switchery"
                                                           name="inp-edit-product-product-availability[]"
                                                        <?= is_value_checked($property['is_available']) ? 'checked="checked"' : ''; ?>>
                                                    ناموجود
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (0 !== $counter++): ?>
                                        <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                    <?php endif; ?>
                                </fieldset>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <fieldset class="position-relative form-group" id="__sample_all_product">
                                <div class="row px-3 pb-3 m-0 border-dashed border-2 border-info rounded">
                                    <div class="mt-3 col-md-6 col-xl-2">
                                        <label>
                                            <span class="text-danger">*</span>
                                            تعداد موجود:
                                        </label>
                                        <input type="text" class="form-control" placeholder="از نوع عددی"
                                               name="inp-edit-product-stock-count[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            بیشترین تعداد در سبد خرید:
                                        </label>
                                        <input type="text" class="form-control" placeholder="از نوع عددی"
                                               name="inp-edit-product-max-count[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-4">
                                        <label>
                                            <span class="text-danger">*</span>
                                            رنگ:
                                        </label>
                                        <select data-placeholder="رنگ را انتخاب کنید..."
                                                class="form-control form-control-select2-colors"
                                                name="inp-edit-product-color[]" data-fouc>
                                            <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                    selected="selected">
                                                انتخاب کنید
                                            </option>
                                            <?php foreach ($colors as $color): ?>
                                                <option value="<?= $color['hex']; ?>"
                                                        data-color="<?= $color['hex']; ?>">
                                                    <?= $color['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>سایز:</label>
                                        <input type="text" class="form-control" placeholder="وارد کنید"
                                               name="inp-edit-product-size[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>گارانتی:</label>
                                        <input type="text" class="form-control" placeholder="وارد کنید"
                                               name="inp-edit-product-guarantee[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            وزن با بسته‌بندی(گرم):
                                        </label>
                                        <input type="text" class="form-control" placeholder="از نوع عددی"
                                               name="inp-edit-product-weight[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            قیمت:
                                        </label>
                                        <input type="text" class="form-control" placeholder="به تومان"
                                               name="inp-edit-product-price[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            قیمت با تخفیف:
                                        </label>
                                        <input type="text" class="form-control" placeholder="به تومان"
                                               name="inp-edit-product-discount-price[]">
                                    </div>
                                    <div class="mt-3 col-lg-4">
                                        <label>تخفیف تا تاریخ:</label>
                                        <input type="hidden" name="inp-edit-product-discount-date[]"
                                               id="altDateField">
                                        <input type="text" class="form-control myDatepickerWithEn"
                                               placeholder="انتخاب تاریخ" readonly data-ignored
                                               name="inp-edit-product-discount-date-tmp[]"
                                               data-alt-field="#altDateField"
                                               data-format="YYYY/MM/DD HH:mm"
                                               data-time="true"
                                               value="<?= date('Y/m/d H:i', time()); ?>">
                                    </div>
                                    <div class="mt-3 col alert-warning d-flex align-items-center rounded">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox"
                                                       name="inp-edit-product-consider-discount-date[]"
                                                       value="0"
                                                       class="styled form-input-styled">
                                                عدم درنظرگیری تاریخ تخفیف
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-3 col">
                                        <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                            <label class="form-check-label">
                                                موجود
                                                <input type="checkbox" class="form-check-input-switchery"
                                                       name="inp-edit-product-product-availability[]" checked="checked">
                                                ناموجود
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- /products property -->

            <!-- Related products -->
            <div class="col-lg-12">
                <div class="card card-collapsed">
                    <?php load_partial('admin/card-header', ['header_title' => 'محصولات مرتبط']); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 p-2">
                                <div class="border p-1" style="border-radius: 50rem;">
                                    <?php
                                    /**
                                     * @var ProductModel $productsModel
                                     */
                                    $productsModel = container()->get(ProductModel::class);
                                    $items = input()->post('inp-edit-product-related');
                                    $items = is_array($items) && count($items) ? $items : $related;
                                    $items = array_map(function ($v) {
                                        if ($v instanceof \Pecee\Http\Input\IInputItem) {
                                            return $v->getValue();
                                        }
                                        return $v;
                                    }, $items);
                                    ?>
                                    <select class="form-control select-remote-data"
                                            name="inp-edit-product-related[]"
                                            data-remote-placeholder="انتخاب کالا"
                                            data-remote-url="<?= url('admin.product.s2.view')->getRelativeUrlTrimmed(); ?>"
                                            data-remote-limit="15"
                                            multiple
                                            data-fouc>
                                        <?php foreach ($items as $item): ?>
                                            <?php
                                            $p = $productsModel->getFirst(['title'], 'id=:id', ['id' => $item]);
                                            ?>
                                            <?php if (count($p)): ?>
                                                <option value="<?= $item; ?>" selected>
                                                    <?= $p['title']; ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /related products -->

            <!-- Product publish -->
            <div class="col-lg-6">
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__pubStatus">
                                وضعیت نمایش
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__pubStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-status"
                                            <?= $validator->setCheckbox('inp-edit-product-status', 'on') ?: (is_value_checked($product['publish']) ? 'checked="checked"' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product publish -->

            <!-- Product availability -->
            <div class="col-lg-6">
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__avStatus">
                                وضعیت موجودی
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__avStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-availability"
                                            <?= $validator->setCheckbox('inp-edit-product-availability', 'on') ?: (is_value_checked($product['is_available']) ? 'checked="checked"' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product availability -->

            <!-- Product special -->
            <div class="col-lg-6">
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__spStatus">
                                محصول ویژه
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__spStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-special"
                                            <?= $validator->setCheckbox('inp-edit-product-special', 'on') ?: (is_value_checked($product['is_special']) ? 'checked="checked"' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product special -->

            <!-- Product returnable -->
            <!--            <div class="col-lg-6">-->
            <!--                <div class="card card-collapsed">-->
            <!--                    <div class="card-header header-elements-inline">-->
            <!--                        <h5 class="card-title">-->
            <!--                            <label class="m-0 cursor-pointer" for="__rtStatus">-->
            <!--                                امکان مرجوع کردن-->
            <!--                            </label>-->
            <!--                        </h5>-->
            <!--                        <div class="header-elements">-->
            <!--                            <div class="list-icons">-->
            <!--                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">-->
            <!--                                    <label class="form-check-label">-->
            <!--                                        <input id="__rtStatus" type="checkbox" class="form-check-input-switchery"-->
            <!--                                               name="inp-edit-product-returnable"-->
            <?= '';//$validator->setCheckbox('inp-edit-product-returnable', 'on') ?: (is_value_checked($product['is_returnable']) ? 'checked="checked"' : '');  ?>
            >
            <!--                                    </label>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!-- /product returnable -->

            <!-- Product commenting -->
            <div class="col-lg-6">
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__cStatus">
                                اجازه ارسال نظر
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__cStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-commenting"
                                            <?= $validator->setCheckbox('inp-edit-product-commenting', 'on') ?: (is_value_checked($product['allow_commenting']) ? 'checked="checked"' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product commenting -->

            <!-- Product coming soon -->
            <div class="col-lg-6">
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__csStatus">
                                نمایش بزودی
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__csStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-coming-soon"
                                            <?= $validator->setCheckbox('inp-edit-product-coming-soon', 'on') ?: (is_value_checked($product['show_coming_soon']) ? 'checked="checked"' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product coming soon -->

            <!-- Product call for more -->
            <div class="col-lg-6">
                <div class="card card-collapsed">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__cfmStatus">
                                تماس برای اطلاعات بیشتر
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__cfmStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-call-for-more"
                                            <?= $validator->setCheckbox('inp-edit-product-call-for-more', 'on') ?: (is_value_checked($product['call_for_more']) ? 'checked="checked"' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product call for more -->

            <!-- Product images -->
            <div class="col-lg-12">
                <div class="card card-collapsed">
                    <?php load_partial('admin/card-header', ['header_title' => 'گالری تصاویر' . '<span class="text-danger small ml-1">' . '(وارد کردن یک تصویر الزامیست) ' . '</span>']); ?>

                    <div class="card-body">
                        <div class="d-flex align-items-end flex-wrap">
                            <div class="col">
                                <div class="__image_gallery_container d-flex align-items-center flex-wrap">
                                    <?php
                                    $stockCounts = input()->post('inp-edit-product-gallery-img');
                                    $galleryImages = $gallery;
                                    ?>
                                    <?php if (is_array($stockCounts) && count($stockCounts)): ?>
                                        <?php $counter = 0; ?>
                                        <?php foreach ($stockCounts as $img): ?>
                                            <div class="img-placeholder-custom __file_picker_handler __file_image <?= !empty($img->getValue()) ? 'has-image' : ''; ?>"
                                                 data-toggle="modal"
                                                 data-target="#modal_efm"
                                                <?= 0 === $counter ? 'id="__sample_gallery_image"' : ''; ?>>
                                                <input type="hidden" name="inp-edit-product-gallery-img[]"
                                                       value="<?= $img->getValue(); ?>">
                                                <?php if (!empty($img->getValue())): ?>
                                                    <img class="img-placeholder-image"
                                                         src="<?= url('image.show') . $img->getValue(); ?>"
                                                         alt="selected image">
                                                <?php endif; ?>
                                                <div class="img-placeholder-icon-container">
                                                    <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                                    <div class="img-placeholder-num bg-warning text-white">
                                                        <i class="icon-plus2"></i>
                                                    </div>
                                                </div>
                                                <?php if (0 !== $counter++): ?>
                                                    <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php elseif (is_array($galleryImages) && count($galleryImages)): ?>
                                        <?php $counter = 0; ?>
                                        <?php foreach ($galleryImages as $img): ?>
                                            <div class="img-placeholder-custom __file_picker_handler __file_image <?= !empty($img['image']) ? 'has-image' : ''; ?>"
                                                 data-toggle="modal"
                                                 data-target="#modal_efm"
                                                <?= 0 === $counter ? 'id="__sample_gallery_image"' : ''; ?>>
                                                <input type="hidden" name="inp-edit-product-gallery-img[]"
                                                       value="<?= $img['image']; ?>">
                                                <?php if (!empty($img['image'])): ?>
                                                    <img class="img-placeholder-image"
                                                         src="<?= url('image.show') . $img['image']; ?>"
                                                         alt="selected image">
                                                <?php endif; ?>
                                                <div class="img-placeholder-icon-container">
                                                    <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                                    <div class="img-placeholder-num bg-warning text-white">
                                                        <i class="icon-plus2"></i>
                                                    </div>
                                                </div>
                                                <?php if (0 !== $counter++): ?>
                                                    <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="img-placeholder-custom __file_picker_handler __file_image"
                                             data-toggle="modal"
                                             data-target="#modal_efm"
                                             id="__sample_gallery_image">
                                            <input type="hidden" name="inp-edit-product-gallery-img[]">
                                            <div class="img-placeholder-icon-container">
                                                <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                                <div class="img-placeholder-num bg-warning text-white">
                                                    <i class="icon-plus2"></i>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Extra image slot -->
                            <div class="img-placeholder-custom img-placeholder-custom-sm rounded-circle __duplicator_btn ml-3"
                                 data-container-element=".__image_gallery_container"
                                 data-sample-element="#__sample_gallery_image"
                                 data-clearable-elements='["inp-edit-product-gallery-img[]"]'
                                 data-removable-elements='[".img-placeholder-image"]'
                                 data-removable-classes='["has-image"]'
                                 data-add-remove="true"
                                 data-deep-copy="true">
                                <div class="img-placeholder-icon-container">
                                    <i class="icon-plus2 img-placeholder-icon text-info"></i>
                                </div>
                            </div>
                            <!-- /extra image slot -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product image -->

            <!-- Product description -->
            <div class="col-lg-12">
                <div class="card card-collapsed">
                    <?php load_partial('admin/card-header', ['header_title' => 'توضیحات محصول']); ?>

                    <div class="card-body">
                        <textarea name="inp-edit-product-desc"
                                  cols="30"
                                  rows="10"
                                  placeholder="توضیحات را وارد کنید..."
                                  class="form-control cntEditor"
                        ><?= $validator->setInput('inp-edit-product-desc') ?: $product['body']; ?></textarea>
                    </div>
                </div>
            </div>
            <!-- /product description -->

            <!-- Product properties -->
            <div class="col-lg-12">
                <div class="card card-collapsed">
                    <?php load_partial('admin/card-header', ['header_title' => 'ویژگی‌های محصول']); ?>

                    <div class="card-body">
                        مواردی که
                        <span class="badge badge-info">عنوان اصلی ویژگی</span>
                        یا
                        <span class="badge badge-warning">عنوان زیر ویژگی</span>
                        آن‌ها خالی است، در نظر گرفته نمیشود.
                    </div>

                    <div class="card-body">
                        <?php
                        $properties = input()->post('inp-item-product-properties');
                        $subProperties = input()->post('inp-item-product-sub-properties');
                        $dbProperties = json_decode($product['properties'], true);
                        $dbProperties = is_array($dbProperties) ? $dbProperties : [];
                        ?>

                        <div class="__all_properties_container">
                            <?php if (!$validator->getStatus()): ?>
                                <?php $counter = 0; ?>
                                <?php foreach ($properties as $k => $main): ?>
                                    <div class="border-success border-2 border-dashed rounded p-2 mb-3 position-relative __product_properties __sample_product_property">
                                        <div class="row m-0">
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    عنوان اصلی ویژگی
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-item-product-properties[<?= $k; ?>][title]"
                                                       value="<?= $main['title']->getValue(); ?>">
                                            </div>
                                        </div>

                                        <div class="__all_sub_property_container">
                                            <?php if (($subProperties[$k] ?? [])): ?>
                                                <?php $counter2 = 0; ?>
                                                <?php foreach (($subProperties[$k]) as $k2 => $sub): ?>
                                                    <div class="row m-0 position-relative border-warning border-2 border-dashed rounded p-2 my-3 __sub_product_properties __sample_sub_product_property">
                                                        <div class="col-lg-4 form-group">
                                                            <label>
                                                                عنوان زیر ویژگی
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="وارد کنید"
                                                                   name="inp-item-product-sub-properties[<?= $k; ?>][<?= $k2; ?>][sub-title]"
                                                                   value="<?= $sub['sub-title']->getValue(); ?>">
                                                        </div>
                                                        <div class="col-lg-8 form-group">
                                                            <label>
                                                                ویژگی‌ها
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control tags-input"
                                                                   placeholder="وارد کنید"
                                                                   name="inp-item-product-sub-properties[<?= $k; ?>][<?= $k2; ?>][sub-properties]"
                                                                   value="<?= $sub['sub-properties']->getValue(); ?>">
                                                        </div>
                                                        <?php if (0 != $counter2++): ?>
                                                            <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row m-0 position-relative border-warning border-2 border-dashed rounded p-2 my-3 __sub_product_properties __sample_sub_product_property">
                                                    <div class="col-lg-4 form-group">
                                                        <label>
                                                            عنوان زیر ویژگی
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="وارد کنید"
                                                               name="inp-item-product-sub-properties[<?= $k; ?>][0][sub-title]">
                                                    </div>
                                                    <div class="col-lg-8 form-group">
                                                        <label>
                                                            ویژگی‌ها
                                                        </label>
                                                        <input type="text"
                                                               class="form-control tags-input"
                                                               placeholder="وارد کنید"
                                                               name="inp-item-product-sub-properties[<?= $k; ?>][0][sub-properties]">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-7 col-lg-5 ml-auto">
                                                <button type="button"
                                                        class="btn bg-white btn-block border-warning border-3 __sub_property_cloner">
                                                    زیر ویژگی جدید
                                                    <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <?php if (0 != $counter++): ?>
                                            <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif (count($dbProperties)): ?>
                                <?php $counter = 0; ?>
                                <?php foreach ($dbProperties as $main): ?>
                                    <div class="border-success border-2 border-dashed rounded p-2 mb-3 position-relative __product_properties __sample_product_property">
                                        <div class="row m-0">
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    عنوان اصلی ویژگی
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-item-product-properties[<?= $counter; ?>][title]"
                                                       value="<?= $main['title']; ?>">
                                            </div>
                                        </div>

                                        <div class="__all_sub_property_container">
                                            <?php if (($main['children'] ?? [])): ?>
                                                <?php $counter2 = 0; ?>
                                                <?php foreach (($main['children']) as $sub): ?>
                                                    <div class="row m-0 position-relative border-warning border-2 border-dashed rounded p-2 my-3 __sub_product_properties __sample_sub_product_property">
                                                        <div class="col-lg-4 form-group">
                                                            <label>
                                                                عنوان زیر ویژگی
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="وارد کنید"
                                                                   name="inp-item-product-sub-properties[<?= $counter; ?>][<?= $counter2; ?>][sub-title]"
                                                                   value="<?= $sub['title']; ?>">
                                                        </div>
                                                        <div class="col-lg-8 form-group">
                                                            <label>
                                                                ویژگی‌ها
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control tags-input"
                                                                   placeholder="وارد کنید"
                                                                   name="inp-item-product-sub-properties[<?= $counter; ?>][<?= $counter2; ?>][sub-properties]"
                                                                   value="<?= $sub['properties']; ?>">
                                                        </div>
                                                        <?php if (0 != $counter2++): ?>
                                                            <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row m-0 position-relative border-warning border-2 border-dashed rounded p-2 my-3 __sub_product_properties __sample_sub_product_property">
                                                    <div class="col-lg-4 form-group">
                                                        <label>
                                                            عنوان زیر ویژگی
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="وارد کنید"
                                                               name="inp-item-product-sub-properties[<?= $counter; ?>][0][sub-title]">
                                                    </div>
                                                    <div class="col-lg-8 form-group">
                                                        <label>
                                                            ویژگی‌ها
                                                        </label>
                                                        <input type="text"
                                                               class="form-control tags-input"
                                                               placeholder="وارد کنید"
                                                               name="inp-item-product-sub-properties[<?= $counter; ?>][0][sub-properties]">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-7 col-lg-5 ml-auto">
                                                <button type="button"
                                                        class="btn bg-white btn-block border-warning border-3 __sub_property_cloner">
                                                    زیر ویژگی جدید
                                                    <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <?php if (0 != $counter++): ?>
                                            <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="border-success border-2 border-dashed rounded p-2 mb-3 position-relative __product_properties __sample_product_property">
                                    <div class="row m-0">
                                        <div class="col-lg-6 form-group">
                                            <label>
                                                عنوان اصلی ویژگی
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="وارد کنید"
                                                   name="inp-item-product-properties[0][title]">
                                        </div>
                                    </div>

                                    <div class="__all_sub_property_container">
                                        <div class="row m-0 position-relative border-warning border-2 border-dashed rounded p-2 my-3 __sub_product_properties __sample_sub_product_property">
                                            <div class="col-lg-4 form-group">
                                                <label>
                                                    عنوان زیر ویژگی
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-item-product-sub-properties[0][0][sub-title]">
                                            </div>
                                            <div class="col-lg-8 form-group">
                                                <label>
                                                    ویژگی‌ها
                                                </label>
                                                <input type="text"
                                                       class="form-control tags-input"
                                                       placeholder="وارد کنید"
                                                       name="inp-item-product-sub-properties[0][0][sub-properties]">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-7 col-lg-5 ml-auto">
                                            <button type="button"
                                                    class="btn bg-white btn-block border-warning border-3 __sub_property_cloner">
                                                زیر ویژگی جدید
                                                <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8 col-lg-6 mx-auto">
                                <button type="button" id="__property_cloner"
                                        class="btn bg-white btn-block border-success border-3">
                                    ویژگی جدید
                                    <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product properties -->

            <div class="col-lg-12">
                <div class="row flex-row-reverse">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            ذخیره اطلاعات
                            <i class="icon-floppy-disks ml-2"></i>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= url('admin.product.view', ''); ?>" class="btn bg-white text-dark btn-lg btn-block">
                            بازگشت
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->

    <?php load_partial('editor/browser-tiny-func'); ?>
</div>
<!-- /content area -->