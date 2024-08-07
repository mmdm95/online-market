<?php

use App\Logic\Models\ProductModel;
use Pecee\Http\Input\InputItem;

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <form action="<?= url('admin.product.add')->getRelativeUrlTrimmed(); ?>" method="post"
          id="__form_add_product">
        <?php load_partial('admin/message/message-form', [
            'errors' => $product_add_errors ?? [],
            'success' => $product_add_success ?? '',
            'warning' => $product_add_warning ?? '',
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
                                        $img = $validator->setInput('inp-add-product-img');
                                        ?>
                                        <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto <?= !empty($img) ? 'has-image' : ''; ?>"
                                             data-toggle="modal"
                                             data-target="#modal_efm">
                                            <input type="hidden" name="inp-add-product-img"
                                                   value="<?= $img; ?>">
                                            <?php if (!empty($img)): ?>
                                                <img class="img-placeholder-image"
                                                     src="<?= url('image.show') . $img; ?>"
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
                                       name="inp-add-product-title"
                                       value="<?= $validator->setInput('inp-add-product-title'); ?>">
                            </div>
                            <div class="form-group col-lg-5">
                                <label>
                                    <span class="text-danger">*</span>
                                    واحد:
                                </label>
                                <select data-placeholder="واحد کالا را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-add-product-unit" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            disabled="disabled"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($units as $unit): ?>
                                        <option value="<?= $unit['id']; ?>"
                                            <?= $validator->setSelect('inp-add-product-unit', $unit['id']); ?>>
                                            <?= $unit['title'] . (!empty($unit['sign']) ? ' - ' . $unit['sign'] : ''); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>
                                    <span class="text-danger">*</span>
                                    برند:
                                </label>
                                <select data-placeholder="برند را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-add-product-brand" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            disabled="disabled"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand['id']; ?>"
                                            <?= $validator->setSelect('inp-add-product-brand', $brand['id']); ?>>
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
                                        name="inp-add-product-category" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            disabled="disabled"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id']; ?>"
                                            <?= $validator->setSelect('inp-add-product-category', $category['id']); ?>>
                                            <?= $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label>تعداد کالا برای هشدار:</label>
                                <input type="text" class="form-control" placeholder="از نوع عددی"
                                       name="inp-add-product-alert-product"
                                       value="<?= $validator->setInput('inp-add-product-alert-product'); ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>ویژگی‌های سریع:</label>
                                <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                                       name="inp-add-product-simple-properties"
                                       value="<?= $validator->setInput('inp-add-product-simple-properties'); ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>کلمات کلیدی:</label>
                                <input type="text" class="form-control tags-input" placeholder="وارد کنید"
                                       name="inp-add-product-keywords"
                                       value="<?= $validator->setInput('inp-add-product-keywords'); ?>">
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
                                    data-clearable-elements='["inp-add-product-stock-count[]","inp-add-product-max-count[]","inp-add-product-color[]","inp-add-product-size[]","inp-add-product-weight[]","inp-add-product-guarantee[]","inp-add-product-price[]","inp-add-product-discount-price[]"]'
                                    data-alt-field='["inp-add-product-discount-date-tmp[]","inp-add-product-discount-date-from-tmp[]"]'
                                    data-default-checked-elements='["inp-add-product-product-availability[]", "inp-add-product-consider-discount-date-from[]", "inp-add-product-consider-discount-date[]"]'
                                    data-default-unchecked-elements='["inp-add-product-separate-consignment[]"]'
                                    data-add-remove="true">
                                افزودن محصول جدید
                                <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body __all_products_container">
                        <?php
                        $stockCounts = input()->post('inp-add-product-stock-count');
                        ?>
                        <?php if (is_array($stockCounts) && count($stockCounts)): ?>
                            <?php $counter = 0; ?>
                            <?php foreach ($stockCounts as $count): ?>
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
                                                   name="inp-add-product-stock-count[]"
                                                   value="<?= $validator->setInput('inp-add-product-stock-count.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                بیشترین تعداد در سبد خرید:
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-add-product-max-count[]"
                                                   value="<?= $validator->setInput('inp-add-product-max-count.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-4">
                                            <label>
                                                <span class="text-danger">*</span>
                                                رنگ:
                                            </label>
                                            <select data-placeholder="رنگ را انتخاب کنید..."
                                                    class="form-control form-control-select2-colors"
                                                    name="inp-add-product-color[]" data-fouc>
                                                <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                        disabled="disabled"
                                                        selected="selected">
                                                    انتخاب کنید
                                                </option>
                                                <?php foreach ($colors as $color): ?>
                                                    <option value="<?= $color['id']; ?>"
                                                            data-color="<?= $color['hex']; ?>"
                                                        <?= $validator->setSelect('inp-add-product-color', $color['id']); ?>>
                                                        <?= $color['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>سایز:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید"
                                                   name="inp-add-product-size[]"
                                                   value="<?= $validator->setInput('inp-add-product-size.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>گارانتی:</label>
                                            <input type="text" class="form-control" placeholder="وارد کنید"
                                                   name="inp-add-product-guarantee[]"
                                                   value="<?= $validator->setInput('inp-add-product-guarantee.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                وزن با بسته‌بندی(گرم):
                                            </label>
                                            <input type="text" class="form-control" placeholder="از نوع عددی"
                                                   name="inp-add-product-weight[]"
                                                   value="<?= $validator->setInput('inp-add-product-weight.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                قیمت:
                                            </label>
                                            <input type="text" class="form-control" placeholder="به تومان"
                                                   name="inp-add-product-price[]"
                                                   value="<?= $validator->setInput('inp-add-product-price.' . $counter); ?>">
                                        </div>
                                        <div class="mt-3 col-md-6 col-xl-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                قیمت با تخفیف:
                                            </label>
                                            <input type="text" class="form-control" placeholder="به تومان"
                                                   name="inp-add-product-discount-price[]"
                                                   value="<?= $validator->setInput('inp-add-product-discount-price.' . $counter); ?>">
                                        </div>

                                        <div class="row col-12 flex-row-reverse">
                                            <div class="mt-3 ml-5">
                                                <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                                    <label class="form-check-label">
                                                        موجود
                                                        <input type="checkbox" class="form-check-input-switchery"
                                                               name="inp-add-product-product-availability[]"
                                                            <?= $validator->setCheckbox('inp-add-product-product-availability.' . $counter, 'on', true); ?>>
                                                        ناموجود
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="mt-3 mr-3">
                                                <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input-switchery"
                                                               name="inp-add-product-separate-consignment[]"
                                                            <?= $validator->setCheckbox('inp-add-product-separate-consignment.' . $counter, 'on', false); ?>>
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
                                                        $sdf = date('Y/m/d H:i', $validator->setInput('inp-add-product-discount-date-from.' . $counter, time()));
                                                        ?>
                                                        <input type="hidden" name="inp-add-product-discount-date-from[]"
                                                               id="altDateFrom<?= $counter; ?>">
                                                        <input type="text" class="form-control range-from"
                                                               placeholder="انتخاب تاریخ" readonly data-ignored
                                                               name="inp-add-product-discount-date-from-tmp[]"
                                                               data-format="YYYY/MM/DD HH:mm"
                                                               data-alt-field="#altDateFrom<?= $counter; ?>"
                                                               data-time="true"
                                                               value="<?= $sdf ?>">
                                                    </div>

                                                    <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="checkbox"
                                                                       name="inp-add-product-consider-discount-date-from[]"
                                                                    <?= $validator->setCheckbox('inp-add-product-consider-discount-date-from.' . $counter, 'on', true) ?>
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
                                                        $sd = date('Y/m/d H:i', $validator->setInput('inp-add-product-discount-date.' . $counter, time()));
                                                        ?>
                                                        <input type="hidden" name="inp-add-product-discount-date[]"
                                                               id="altDate<?= $counter; ?>">
                                                        <input type="text" class="form-control range-to"
                                                               placeholder="انتخاب تاریخ" readonly data-ignored
                                                               name="inp-add-product-discount-date-tmp[]"
                                                               data-format="YYYY/MM/DD HH:mm"
                                                               data-alt-field="#altDate<?= $counter; ?>"
                                                               data-time="true"
                                                               value="<?= $sd ?>">
                                                    </div>
                                                    <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="checkbox"
                                                                       name="inp-add-product-consider-discount-date[]"
                                                                    <?= $validator->setCheckbox('inp-add-product-consider-discount-date.' . $counter, 'on', true) ?>
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
                                <div class="row px-3 pb-3 m-0 border-dashed border-2 border-info rounded">
                                    <div class="mt-3 col-md-6 col-xl-2">
                                        <label>
                                            <span class="text-danger">*</span>
                                            تعداد موجود:
                                        </label>
                                        <input type="text" class="form-control" placeholder="از نوع عددی"
                                               name="inp-add-product-stock-count[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            بیشترین تعداد در سبد خرید:
                                        </label>
                                        <input type="text" class="form-control" placeholder="از نوع عددی"
                                               name="inp-add-product-max-count[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-4">
                                        <label>
                                            <span class="text-danger">*</span>
                                            رنگ:
                                        </label>
                                        <select data-placeholder="رنگ را انتخاب کنید..."
                                                class="form-control form-control-select2-colors"
                                                name="inp-add-product-color[]" data-fouc>
                                            <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                    disabled="disabled"
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
                                               name="inp-add-product-size[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>گارانتی:</label>
                                        <input type="text" class="form-control" placeholder="وارد کنید"
                                               name="inp-add-product-guarantee[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            وزن با بسته‌بندی(گرم):
                                        </label>
                                        <input type="text" class="form-control" placeholder="از نوع عددی"
                                               name="inp-add-product-weight[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            قیمت:
                                        </label>
                                        <input type="text" class="form-control" placeholder="به تومان"
                                               name="inp-add-product-price[]">
                                    </div>
                                    <div class="mt-3 col-md-6 col-xl-3">
                                        <label>
                                            <span class="text-danger">*</span>
                                            قیمت با تخفیف:
                                        </label>
                                        <input type="text" class="form-control" placeholder="به تومان"
                                               name="inp-add-product-discount-price[]">
                                    </div>

                                    <div class="row col-12 flex-row-reverse">
                                        <div class="mt-3 ml-5">
                                            <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                                <label class="form-check-label">
                                                    موجود
                                                    <input type="checkbox" class="form-check-input-switchery"
                                                           name="inp-add-product-product-availability[]"
                                                           checked="checked">
                                                    ناموجود
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mt-3 mr-3">
                                            <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-switchery"
                                                           name="inp-add-product-separate-consignment[]">
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
                                                    <input type="hidden" name="inp-add-product-discount-date-from[]"
                                                           id="altDateFromField">
                                                    <input type="text" class="form-control range-from"
                                                           placeholder="انتخاب تاریخ" readonly data-ignored
                                                           name="inp-add-product-discount-date-from-tmp[]"
                                                           data-format="YYYY/MM/DD HH:mm"
                                                           data-alt-field="#altDateFromField"
                                                           data-time="true"
                                                           value="<?= date('Y/m/d H:i', time()); ?>">
                                                </div>
                                                <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox"
                                                                   name="inp-add-product-consider-discount-date-from[]"
                                                                   checked="checked"
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
                                                    <input type="hidden" name="inp-add-product-discount-date[]"
                                                           id="altDateField">
                                                    <input type="text" class="form-control range-to"
                                                           placeholder="انتخاب تاریخ" readonly data-ignored
                                                           name="inp-add-product-discount-date-tmp[]"
                                                           data-format="YYYY/MM/DD HH:mm"
                                                           data-alt-field="#altDateField"
                                                           data-time="true"
                                                           value="<?= date('Y/m/d H:i', time()); ?>">
                                                </div>
                                                <div class="col-sm-6 py-3 alert-warning d-flex align-items-center rounded">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox"
                                                                   name="inp-add-product-consider-discount-date[]"
                                                                   checked="checked"
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
                                    $items = input()->post('inp-add-product-related');
                                    $items = is_array($items) ? $items : $items->getValue();
                                    $items = array_map(function ($v) {
                                        if ($v instanceof \Pecee\Http\Input\IInputItem) {
                                            return $v->getValue();
                                        }
                                        return $v;
                                    }, $items ?? []);
                                    ?>
                                    <select class="form-control select-remote-data"
                                            name="inp-add-product-related[]"
                                            data-remote-placeholder="انتخاب کالا"
                                            data-remote-url="<?= url('admin.product.s2.view')->getRelativeUrlTrimmed(); ?>"
                                            data-remote-limit="15"
                                            multiple
                                            data-fouc>
                                        <?php if (is_array($items) && count($items)): ?>
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
                                        <?php endif; ?>
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
                                               name="inp-add-product-status"
                                            <?= $validator->setCheckbox('inp-add-product-status', 'on', true); ?>>
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
                                               name="inp-add-product-availability"
                                            <?= $validator->setCheckbox('inp-add-product-availability', 'on', true); ?>>
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
                                               name="inp-add-product-special"
                                            <?= $validator->setCheckbox('inp-add-product-special', 'on', true); ?>>
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
            <!--                                               name="inp-add-product-returnable"-->
            <?= '';//$validator->setCheckbox('inp-add-product-returnable', 'on', true);                 ?>
            <!--            >-->
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
                                               name="inp-add-product-commenting"
                                            <?= $validator->setCheckbox('inp-add-product-commenting', 'on', true); ?>>
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
                                               name="inp-add-product-coming-soon"
                                            <?= $validator->setCheckbox('inp-add-product-coming-soon', 'on', false); ?>>
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
                                               name="inp-add-product-call-for-more"
                                            <?= $validator->setCheckbox('inp-add-product-call-for-more', 'on', false); ?>>
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
                                    $images = input()->post('inp-add-product-gallery-img');
                                    ?>
                                    <?php if (!$validator->getStatus() && is_array($images) && count($images)): ?>
                                        <?php $counter = 0; ?>
                                        <?php
                                        /**
                                         * @var InputItem $img
                                         */
                                        foreach ($images as $img):
                                            ?>
                                            <div class="img-placeholder-custom __file_picker_handler __file_image <?= !empty($img->getValue()) ? 'has-image' : ''; ?>"
                                                 data-toggle="modal"
                                                 data-target="#modal_efm"
                                                <?= 0 === $counter ? 'id="__sample_gallery_image"' : ''; ?>>
                                                <input type="hidden" name="inp-add-product-gallery-img[]"
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
                                    <?php else: ?>
                                        <div class="img-placeholder-custom __file_picker_handler __file_image"
                                             data-toggle="modal"
                                             data-target="#modal_efm"
                                             id="__sample_gallery_image">
                                            <input type="hidden" name="inp-add-product-gallery-img[]">
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
                                 data-clearable-elements='["inp-add-product-gallery-img[]"]'
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
                        <textarea name="inp-add-product-desc"
                                  cols="30"
                                  rows="10"
                                  placeholder="توضیحات را وارد کنید..."
                                  class="form-control cntEditor"
                        ><?= $validator->setInput('inp-add-product-desc'); ?></textarea>
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