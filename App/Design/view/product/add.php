<?php

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
                                                <img class="img-placeholder-image" src="<?= $img; ?>"
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
                            <div class="form-group col-lg-8 mx-auto">
                                <label>
                                    <span class="text-danger">*</span>
                                    عنوان محصول:
                                </label>
                                <input type="text" class="form-control" placeholder="وارد کنید"
                                       name="inp-add-product-title"
                                       value="<?= $validator->setInput('inp-add-product-title'); ?>">
                            </div>

                            <div class="col-12"></div>

                            <div class="form-group col-lg-6">
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
                                            <?= $validator->setSelect('inp-add-product-brand', $brand['id']); ?>><?= $brand['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
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
                                            <?= $validator->setSelect('inp-add-product-category', $category['id']); ?>><?= $category['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                <div class="card">
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
                    <div class="card-body text-right">
                        <button type="button" class="btn btn-primary flat-icon __duplicator_btn"
                                data-container-element=".__all_products_container"
                                data-sample-element="#__sample_all_product"
                                data-clearable-elements='["inp-add-product-stock-count[]","inp-add-product-max-count[]","inp-add-product-color[]","inp-add-product-size[]","inp-add-product-guarantee[]","inp-add-product-price[]","inp-add-product-discount-price[]","inp-add-product-discount-date[]","inp-add-product-product-availability[]"]'
                                data-add-remove="true">
                            افزودن محصول جدید
                            <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="card-body __all_products_container">
                        <fieldset class="position-relative form-group" id="__sample_all_product">
                            <div class="row px-3 pb-3 m-0 border-dashed border-2 border-info rounded">
                                <div class="mt-3 col-md-6 col-xl-2">
                                    <label>تعداد موجود:</label>
                                    <input type="text" class="form-control" placeholder="از نوع عددی"
                                           name="inp-add-product-stock-count[]"
                                           value="<?= $validator->setInput('inp-add-product-stock-count'); ?>">
                                </div>
                                <div class="mt-3 col-md-6 col-xl-3">
                                    <label>بیشترین تعداد در سبد خرید:</label>
                                    <input type="text" class="form-control" placeholder="از نوع عددی"
                                           name="inp-add-product-max-count[]"
                                           value="<?= $validator->setInput('inp-add-product-max-count'); ?>">
                                </div>
                                <div class="mt-3 col-md-6 col-xl-4">
                                    <label>رنگ:</label>
                                    <select data-placeholder="رنگ را انتخاب کنید..."
                                            class="form-control form-control-select2-searchable"
                                            name="inp-add-product-color[]" data-fouc>
                                        <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                disabled="disabled"
                                                selected="selected">
                                            انتخاب کنید
                                        </option>
                                        <?php foreach ($colors as $color): ?>
                                            <option value="<?= $color['hex']; ?>"
                                                <?= $validator->setSelect('inp-add-product-color', $color['hex']); ?>><?= $color['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mt-3 col-md-6 col-xl-3">
                                    <label>سایز:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید"
                                           name="inp-add-product-size[]"
                                           value="<?= $validator->setInput('inp-add-product-size'); ?>">
                                </div>
                                <div class="mt-3 col-lg-4">
                                    <label>گارانتی:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید"
                                           name="inp-add-product-guarantee[]"
                                           value="<?= $validator->setInput('inp-add-product-guarantee'); ?>">
                                </div>
                                <div class="mt-3 col-md-6 col-lg-4">
                                    <label>قیمت:</label>
                                    <input type="text" class="form-control" placeholder="به تومان"
                                           name="inp-add-product-price[]"
                                           value="<?= $validator->setInput('inp-add-product-price'); ?>">
                                </div>
                                <div class="mt-3 col-md-6 col-lg-4">
                                    <label>قیمت با تخفیف:</label>
                                    <input type="text" class="form-control" placeholder="به تومان"
                                           name="inp-add-product-discount-price[]"
                                           value="<?= $validator->setInput('inp-add-product-discount-price'); ?>">
                                </div>
                                <div class="mt-3 col-lg-4">
                                    <label>تخفیف تا تاریخ:</label>
                                    <input type="text" class="form-control myDatepickerWithEn"
                                           placeholder="انتخاب تاریخ" readonly data-ignored
                                           name="inp-add-product-discount-date[]"
                                           data-format="YYYY/MM/DD HH:mm"
                                           data-time="true"
                                           value="<?= $validator->setInput('inp-add-product-discount-date', time()) ?>">
                                </div>
                                <div class="mt-3 col">
                                    <div class="form-check form-check-switchery form-check-switchery-double mt-4 text-right">
                                        <label class="form-check-label">
                                            موجود
                                            <input type="checkbox" class="form-check-input-switchery"
                                                   name="inp-add-product-product-availability[]"
                                                <?= $validator->setCheckbox('inp-add-product-product-availability', 'on', true); ?>>
                                            ناموجود
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <!-- /products property -->

            <!-- Product publish -->
            <div class="col-lg-6">
                <div class="card">
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
                <div class="card">
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
                <div class="card">
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

            <!-- Product commenting -->
            <div class="col-lg-6">
                <div class="card">
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

            <!-- Product images -->
            <div class="col-lg-12">
                <div class="card">
                    <?php load_partial('admin/card-header', ['header_title' => 'گالری تصاویر' . '<span class="text-danger small ml-1">' . '(وارد کردن یک تصویر الزامیست) ' . '</span>']); ?>

                    <div class="card-body">
                        <div class="d-flex align-items-end flex-wrap">
                            <div class="col">
                                <div class="__image_gallery_container d-flex align-items-center flex-wrap">
                                    <?php
                                    $img = $validator->setInput('inp-add-product-gallery-img');
                                    ?>
                                    <div class="img-placeholder-custom __file_picker_handler __file_image <?= !empty($img) ? 'has-image' : ''; ?>"
                                         data-toggle="modal"
                                         data-target="#modal_efm"
                                         id="__sample_gallery_image">
                                        <input type="hidden" name="inp-add-product-gallery-img[]"
                                               value="<?= $img; ?>">
                                        <?php if (!empty($img)): ?>
                                            <img class="img-placeholder-image" src="<?= $img; ?>"
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
                <div class="card">
                    <?php load_partial('admin/card-header', ['header_title' => "<span class='text-danger mr-1'>*</span>" . 'ویژگی‌های محصول']); ?>

                    <div class="card-body">
                        <textarea name="inp-add-product-desc"
                                  cols="30"
                                  rows="10"
                                  placeholder="ویژگی‌ها را وارد کنید..."
                                  class="form-control cntEditor"
                        ><?= $validator->setInput('inp-add-product-desc'); ?></textarea>
                    </div>
                </div>
            </div>
            <!-- /product description -->

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