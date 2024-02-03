<!-- Content area -->
<div class="content">
    <div class="row">
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
                                data-clearable-elements='["inp-edit-product-stock-count[]","inp-edit-product-max-count[]","inp-edit-product-color[]","inp-edit-product-size[]","inp-edit-product-weight[]","inp-edit-product-guarantee[]","inp-edit-product-price[]","inp-edit-product-discount-price[]","inp-edit-product-discount-date[]"]'
                                data-alt-field='["inp-edit-product-discount-date-tmp[]","inp-edit-product-discount-date-from-tmp[]"]'
                                data-default-checked-elements='["inp-edit-product-product-availability[]", "inp-edit-product-consider-discount-date-from[]", "inp-edit-product-consider-discount-date[]"]'
                                data-default-unchecked-elements='["inp-edit-product-separate-consignment[]"]'
                                data-add-remove="true"
                                data-removable-elements='[".product-color-badge"]'>
                            افزودن محصول جدید
                            <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body __all_products_container">
                    <?php if (count($product_properties)): ?>
                        <?php $counter = 0; ?>
                        <?php foreach ($product_properties as $property): ?>
                            <input type="hidden" name="inp-edit-product-current-id[]" value="<?= $property['id']; ?>">
                            <fieldset
                                    class="position-relative form-group"
                                <?= 0 === $counter ? 'id="__sample_all_product"' : ''; ?>
                                    data-child-container
                            >
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
                                                <option value="<?= $color['id']; ?>"
                                                        data-color="<?= $color['hex']; ?>">
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

                                    <div class="row col-12 flex-row-reverse">
                                        <div class="mt-3 ml-5">
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

                                        <div class="mt-3 mr-3">
                                            <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-switchery"
                                                           name="inp-edit-product-separate-consignment[]"
                                                        <?= is_value_checked($property['separate_consignment']) ? 'checked="checked"' : ''; ?>>
                                                    مرسوله مجزا
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row col-12 no-gutters">
                                        <div class="col-12 mt-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>تخفیف از تاریخ:</label>
                                                    <?php
                                                    $sdf = date('Y/m/d H:i', $property['discount_from'] ?: time());
                                                    ?>
                                                    <input type="hidden"
                                                           name="inp-edit-product-discount-date-from[]"
                                                           id="altDateFrom<?= $counter; ?>">
                                                    <input type="text" class="form-control range-from"
                                                           placeholder="انتخاب تاریخ" readonly data-ignored
                                                           name="inp-edit-product-discount-date-from-tmp[]"
                                                           data-format="YYYY/MM/DD HH:mm"
                                                           data-alt-field="#altDateFrom<?= $counter; ?>"
                                                           data-time="true"
                                                           value="<?= $sdf; ?>">
                                                </div>
                                                <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox"
                                                                   name="inp-edit-product-consider-discount-date-from[]"
                                                                <?= empty($property['discount_from']) ? 'checked="checked"' : ''; ?>
                                                                   class="styled form-input-styled">
                                                            عدم درنظرگیری تاریخ شروع تخفیف
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>تخفیف تا تاریخ:</label>
                                                    <?php
                                                    $sd = date('Y/m/d H:i', $property['discount_until'] ?: time());
                                                    ?>
                                                    <input type="hidden" name="inp-edit-product-discount-date[]"
                                                           id="altDate<?= $counter; ?>">
                                                    <input type="text" class="form-control range-to"
                                                           placeholder="انتخاب تاریخ" readonly data-ignored
                                                           name="inp-edit-product-discount-date-tmp[]"
                                                           data-format="YYYY/MM/DD HH:mm"
                                                           data-alt-field="#altDate<?= $counter; ?>"
                                                           data-time="true"
                                                           value="<?= $sd; ?>">
                                                </div>
                                                <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox"
                                                                   name="inp-edit-product-consider-discount-date[]"
                                                                <?= empty($property['discount_until']) ? 'checked="checked"' : ''; ?>
                                                                   class="styled form-input-styled">
                                                            عدم درنظرگیری تاریخ پایان تخفیف
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (0 !== $counter++): ?>
                                    <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                <?php endif; ?>
                            </fieldset>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <fieldset
                                class="position-relative form-group"
                                id="__sample_all_product"
                                data-child-container
                        >
                            <input type="hidden" name="inp-edit-product-current-id[]" value="">
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
                                            <option value="<?= $color['id']; ?>"
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

                                <div class="row col-12 flex-row-reverse">
                                    <div class="mt-3 ml-5">
                                        <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                            <label class="form-check-label">
                                                موجود
                                                <input type="checkbox" class="form-check-input-switchery"
                                                       name="inp-edit-product-product-availability[]"
                                                       checked="checked">
                                                ناموجود
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-3 mr-3">
                                        <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input-switchery"
                                                       name="inp-edit-product-separate-consignment[]">
                                                مرسوله مجزا
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row col-12 no-gutters">
                                    <div class="col-12 mt-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>تخفیف از تاریخ:</label>
                                                <input type="hidden" name="inp-edit-product-discount-date-from[]"
                                                       id="altDateFromField">
                                                <input type="text" class="form-control range-from"
                                                       placeholder="انتخاب تاریخ" readonly data-ignored
                                                       name="inp-edit-product-discount-date-from-tmp[]"
                                                       data-format="YYYY/MM/DD HH:mm"
                                                       data-alt-field="#altDateFromField"
                                                       data-time="true"
                                                       value="<?= date('Y/m/d H:i', time()); ?>">
                                            </div>
                                            <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox"
                                                               name="inp-edit-product-consider-discount-date-from[]"
                                                               class="styled form-input-styled">
                                                        عدم درنظرگیری تاریخ شروع تخفیف
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>تخفیف تا تاریخ:</label>
                                                <input type="hidden" name="inp-edit-product-discount-date[]"
                                                       id="altDateField">
                                                <input type="text" class="form-control range-to"
                                                       placeholder="انتخاب تاریخ" readonly data-ignored
                                                       name="inp-edit-product-discount-date-tmp[]"
                                                       data-format="YYYY/MM/DD HH:mm"
                                                       data-alt-field="#altDateField"
                                                       data-time="true"
                                                       value="<?= date('Y/m/d H:i', time()); ?>">
                                            </div>
                                            <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox"
                                                               name="inp-edit-product-consider-discount-date[]"
                                                               class="styled form-input-styled">
                                                        عدم درنظرگیری تاریخ پایان تخفیف
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- /products property -->
    </div>
</div>
<!-- /content area -->