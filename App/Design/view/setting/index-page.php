<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">

    <!-- Inner container -->
    <div class="d-xl-flex align-items-xl-start">

        <!-- Left sidebar component -->
        <?php load_partial('admin/setting-sidebar'); ?>
        <!-- /left sidebar component -->

        <!-- Right content -->
        <div class="w-100">
            <form action="<?= url('admin.setting.pages.index')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_index_page">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_footer_errors ?? [],
                    'success' => $setting_footer_success ?? '',
                    'warning' => $setting_footer_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات صفحه اصلی']); ?>

                    <!-- Tabbed slider -->
                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'اسلایدر تب‌بندی شده']); ?>
                        <div class="row">
                            <div class="col-lg-7 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    عنوان اسلایدر:
                                </label>
                                <input type="text" class="form-control maxlength"
                                       placeholder="تا ۵۰ کاراکتر"
                                       maxlength="50"
                                       name="inp-setting-tabbed-slider-title"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-tabbed-slider-title') ?: config()->get('settings.index_tabbed_slider.value.title')) : config()->get('settings.index_tabbed_slider.value.title'); ?>">
                            </div>
                            <div class="col-12 __all_tabbed_slider_container">
                                <?php
                                $items = config()->get('settings.index_tabbed_slider.value.items');
                                $errorItemNames = input()->post('inp-setting-tabbed-slider-name');
                                $errorItemTypes = input()->post('inp-setting-tabbed-slider-type');
                                $errorItemLimits = input()->post('inp-setting-tabbed-slider-limit');
                                $errorItemCategories = input()->post('inp-setting-tabbed-slider-category');
                                ?>
                                <?php if (!$validator->getStatus()): ?>
                                    <?php
                                    $counter = 0;
                                    ?>
                                    <?php foreach ($errorItemNames as $k => $name): ?>
                                        <div id="__sample_tabbed_item"
                                             class="row m-0 border-orange border-2 border-dashed rounded p-2 mb-3 position-relative __tabbed_items">
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    نام تب‌بندی:
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-setting-tabbed-slider-name[]"
                                                       value="<?= $validator->setInput('inp-setting-tabbed-slider-name', $name->getValue()); ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    نوع تب‌بندی:
                                                </label>
                                                <select data-placeholder="نوع تب‌بندی را انتخاب کنید..."
                                                        class="form-control form-control-select2"
                                                        name="inp-setting-tabbed-slider-type[]" data-fouc>
                                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                            disabled="disabled"
                                                            selected="selected">
                                                        انتخاب کنید
                                                    </option>
                                                    <?php foreach (SLIDER_TABBED_TYPES as $type => $text): ?>
                                                        <option value="<?= $type; ?>"
                                                            <?= !$validator->getStatus() ? $validator->setSelect('inp-setting-tabbed-slider-type', $type) : ($type == $errorItemTypes[$k]->getValue() ? 'selected="selected"' : ''); ?>>
                                                            <?= $text; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    حداکثر تعداد نمایش:
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-tabbed-slider-limit[]"
                                                       value="<?= $validator->setInput('inp-setting-tabbed-slider-limit', $errorItemLimits[$k]->getValue()); ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    دسته‌بندی:
                                                </label>
                                                <select data-placeholder="نوع دسته‌بندی را انتخاب کنید..."
                                                        class="form-control form-control-select2"
                                                        name="inp-setting-tabbed-slider-category[]" data-fouc>
                                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                            selected="selected">
                                                        انتخاب کنید
                                                    </option>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= $category['id']; ?>"
                                                            <?= !$validator->getStatus() ? $validator->setSelect('inp-setting-tabbed-slider-category', $category['id']) : ($category['id'] == $errorItemCategories[$k]->getValue() ? 'selected="selected"' : ''); ?>>
                                                            <?= $category['name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <?php if (0 != $counter++): ?>
                                                <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif (count($items)): ?>
                                    <?php
                                    $counter = 0;
                                    ?>
                                    <?php foreach ($items as $item): ?>
                                        <div id="__sample_tabbed_item"
                                             class="row m-0 border-orange border-2 border-dashed rounded p-2 mb-3 position-relative __tabbed_items">
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    نام تب‌بندی:
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-setting-tabbed-slider-name[]"
                                                       value="<?= $validator->setInput('inp-setting-tabbed-slider-name', $item['name']); ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    نوع تب‌بندی:
                                                </label>
                                                <select data-placeholder="نوع تب‌بندی را انتخاب کنید..."
                                                        class="form-control form-control-select2"
                                                        name="inp-setting-tabbed-slider-type[]" data-fouc>
                                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                            disabled="disabled"
                                                            selected="selected">
                                                        انتخاب کنید
                                                    </option>
                                                    <?php foreach (SLIDER_TABBED_TYPES as $type => $text): ?>
                                                        <option value="<?= $type; ?>"
                                                            <?= !$validator->getStatus() ? $validator->setSelect('inp-setting-tabbed-slider-type', $type) : ($type == $item['type'] ? 'selected="selected"' : ''); ?>>
                                                            <?= $text; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    حداکثر تعداد نمایش:
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-tabbed-slider-limit[]"
                                                       value="<?= $validator->setInput('inp-setting-tabbed-slider-limit', $item['limit']); ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>
                                                    دسته‌بندی:
                                                </label>
                                                <select data-placeholder="نوع دسته‌بندی را انتخاب کنید..."
                                                        class="form-control form-control-select2"
                                                        name="inp-setting-tabbed-slider-category[]" data-fouc>
                                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                            selected="selected">
                                                        انتخاب کنید
                                                    </option>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= $category['id']; ?>"
                                                            <?= !$validator->getStatus() ? $validator->setSelect('inp-setting-tabbed-slider-category', $category['id']) : ($category['id'] == $item['category'] ? 'selected="selected"' : ''); ?>>
                                                            <?= $category['name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <?php if (0 != $counter++): ?>
                                                <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div id="__sample_tabbed_item"
                                         class="row m-0 border-orange border-2 border-dashed rounded p-2 mb-3 position-relative __tabbed_items">
                                        <div class="col-lg-6 form-group">
                                            <label>
                                                نام تب‌بندی:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="وارد کنید"
                                                   name="inp-setting-tabbed-slider-name[]">
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label>
                                                نوع تب‌بندی:
                                            </label>
                                            <select data-placeholder="نوع تب‌بندی را انتخاب کنید..."
                                                    class="form-control form-control-select2"
                                                    name="inp-setting-tabbed-slider-type[]" data-fouc>
                                                <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                        disabled="disabled"
                                                        selected="selected">
                                                    انتخاب کنید
                                                </option>
                                                <?php foreach (SLIDER_TABBED_TYPES as $type => $text): ?>
                                                    <option value="<?= $type; ?>">
                                                        <?= $text; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label>
                                                حداکثر تعداد نمایش:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="از نوع عددی"
                                                   name="inp-setting-tabbed-slider-limit[]">
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label>
                                                دسته‌بندی:
                                            </label>
                                            <select data-placeholder="نوع دسته‌بندی را انتخاب کنید..."
                                                    class="form-control form-control-select2"
                                                    name="inp-setting-tabbed-slider-category[]" data-fouc>
                                                <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                                        selected="selected">
                                                    انتخاب کنید
                                                </option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= $category['id']; ?>">
                                                        <?= $category['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8 col-lg-6 mx-auto mt-3">
                                <button type="button"
                                        class="btn bg-white btn-block border-orange border-3 __duplicator_btn"
                                        data-container-element=".__all_tabbed_slider_container"
                                        data-sample-element="#__sample_tabbed_item"
                                        data-clearable-elements='["inp-setting-tabbed-slider-name[]","inp-setting-tabbed-slider-type[]","inp-setting-tabbed-slider-limit[]","inp-setting-tabbed-slider-category[]"]'
                                        data-add-remove="true">
                                    افزودن تب‌بندی جدید
                                    <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /tabbed slider -->

                    <!-- Three images -->
                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'تصاویر سه‌تایی']); ?>

                        <?php
                        $images = config()->get('settings.index_3_images.value');
                        ?>
                        <div class="row align-items-end m-0">
                            <div>
                                <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                                    <?php
                                    $img = !$validator->getStatus() ? ($validator->setInput('inp-setting-three-slider-image.1') ?: $images[0]['image']) : $images[0]['image'];
                                    $img = is_image_exists($img) ? $img : '';
                                    ?>
                                    <label class="form-text text-info">
                                        تصویر شماره ۱
                                    </label>
                                    <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto mb-0 <?= !empty($img) ? 'has-image' : ''; ?>"
                                         data-toggle="modal"
                                         data-target="#modal_efm">
                                        <input type="hidden" name="inp-setting-three-slider-image[0]"
                                               value="<?= $img; ?>">
                                        <?php if (!empty($img)): ?>
                                            <img class="img-placeholder-image" src="<?= url('image.show') . $img; ?>"
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
                            <div class="col">
                                <label>
                                    لینک:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="برای مثال: http://www.example.com"
                                       name="inp-setting-three-slider-name[0]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-three-slider-name.0') ?: $images[0]['link']) : $images[0]['link'] ?>">
                            </div>
                        </div>

                        <div class="row align-items-end m-0">
                            <div>
                                <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                                    <?php
                                    $img = !$validator->getStatus() ? ($validator->setInput('inp-setting-three-slider-image.1') ?: $images[1]['image']) : $images[1]['image'];
                                    $img = is_image_exists($img) ? $img : '';
                                    ?>
                                    <label class="form-text text-info">
                                        تصویر شماره ۲
                                    </label>
                                    <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto mb-0 <?= !empty($img) ? 'has-image' : ''; ?>"
                                         data-toggle="modal"
                                         data-target="#modal_efm">
                                        <input type="hidden" name="inp-setting-three-slider-image[1]"
                                               value="<?= $img; ?>">
                                        <?php if (!empty($img)): ?>
                                            <img class="img-placeholder-image" src="<?= url('image.show') . $img; ?>"
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
                            <div class="col">
                                <label>
                                    لینک:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="برای مثال: http://www.example.com"
                                       name="inp-setting-three-slider-name[1]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-three-slider-name.1') ?: $images[1]['link']) : $images[1]['link'] ?>">
                            </div>
                        </div>

                        <div class="row align-items-end m-0">
                            <div>
                                <div class="form-group text-center ml-sm-0 mr-sm-3 mb-0">
                                    <?php
                                    $img = !$validator->getStatus() ? ($validator->setInput('inp-setting-three-slider-image.2') ?: $images[2]['image']) : $images[2]['image'];
                                    $img = is_image_exists($img) ? $img : '';
                                    ?>
                                    <label class="form-text text-info">
                                        تصویر شماره ۳
                                    </label>
                                    <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto mb-0 <?= !empty($img) ? 'has-image' : ''; ?>"
                                         data-toggle="modal"
                                         data-target="#modal_efm">
                                        <input type="hidden" name="inp-setting-three-slider-image[2]"
                                               value="<?= $img; ?>">
                                        <?php if (!empty($img)): ?>
                                            <img class="img-placeholder-image" src="<?= url('image.show') . $img; ?>"
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
                            <div class="col">
                                <label>
                                    لینک:
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="برای مثال: http://www.example.com"
                                       name="inp-setting-three-slider-name[2]"
                                       value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-three-slider-name.2') ?: $images[2]['link']) : $images[2]['link'] ?>">
                            </div>
                        </div>
                    </div>
                    <!-- /three images -->

                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success btn-block">
                                    ذخیره تنظیمات
                                    <i class="icon-check2 ml-2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /sidebars overview -->
            </form>
        </div>
        <!-- /right content -->

    </div>
    <!-- /inner container -->

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->

</div>
<!-- /content area -->
