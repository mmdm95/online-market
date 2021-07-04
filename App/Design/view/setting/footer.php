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
            <form action="<?= url('admin.setting.footer')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_footer">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_footer_errors ?? [],
                    'success' => $setting_footer_success ?? '',
                    'warning' => $setting_footer_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات فوتر/پاورقی']); ?>

                    <div class="card-body">
                        <?php load_partial('admin/section-header', ['header_title' => 'توضیح مختصر']); ?>
                        <!-- Tiny description -->
                        <div class="form-group pb-3">
                            <label>توضیحات مختصر:</label>
                            <textarea class="form-control form-control-min-height maxlength-placeholder"
                                      placeholder="تا ۲۵۰ کاراکتر"
                                      maxlength="250"
                                      name="inp-setting-desc"
                            ><?= !$validator->getStatus() ? ($validator->setInput('inp-setting-desc') ?: config()->get('settings.footer_tiny_desc.value')) : config()->get('settings.footer_tiny_desc.value'); ?></textarea>
                        </div>
                        <!-- /tiny description -->

                        <?php load_partial('admin/section-header', ['header_title' => 'کپی رایت']); ?>
                        <!-- Tiny description -->
                        <div class="form-group pb-3">
                            <label>توضیحات کپی رایت:</label>
                            <input class="form-control maxlength-placeholder"
                                   placeholder="تا ۲۵۰ کاراکتر"
                                   maxlength="250"
                                   name="inp-setting-copyright"
                                   value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-copyright') ?: config()->get('settings.footer_copyright.value')) : config()->get('settings.footer_copyright.value'); ?>">
                        </div>
                        <!-- /tiny description -->

                        <?php load_partial('admin/section-header', ['header_title' => 'پاورقی - بخش یک']); ?>
                        <?php load_partial('admin/message/message-info', [
                            'info' => 'مواردی که نام لینک آن‌ها خالی است، در نظر گرفته نمی‌شوند.',
                            'dismissible' => false,
                        ]); ?>
                        <!-- Footer section 1 -->
                        <?php
                        $footerSection1 = config()->get('settings.footer_section_1.value') ?: [];
                        $errFooterSection1Names = input()->post('inp-setting-sec1-name') ?: [];
                        $errFooterSection1Links = input()->post('inp-setting-sec1-link') ?: [];
                        $errFooterSection1Priorities = input()->post('inp-setting-sec1-priority') ?: [];
                        $counter = 0;
                        ?>
                        <div class="col-lg-12 form-group">
                            <label>عنوان بخش یک:</label>
                            <input type="text" class="form-control maxlength"
                                   placeholder="تا ۵۰ کاراکتر"
                                   maxlength="50"
                                   name="inp-setting-sec1-title"
                                   value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-sec1-title') ?: ($footerSection1['title'] ?? '')) : ($footerSection1['title'] ?? ''); ?>">
                        </div>
                        <div class="d-flex align-items-end">
                            <div class="col __footer_sec1_container">
                                <?php if (!$validator->getStatus()): ?>
                                    <?php foreach ($errFooterSection1Names as $k => $name): ?>
                                        <div class="row form-group border border-warning position-relative rounded mx-0 px-2 py-3"
                                            <?= 0 == $counter ? 'id="__sample_footer_sec1"' : ''; ?>>
                                            <div class="col-lg-4 form-group">
                                                <label>نام لینک:</label>
                                                <input type="text" class="form-control maxlength"
                                                       placeholder="تا ۵۰ کاراکتر"
                                                       maxlength="50"
                                                       name="inp-setting-sec1-name[]"
                                                       value="<?= $name->getValue(); ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>لینک:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="برای مثال:‌ http://www.example.com"
                                                       name="inp-setting-sec1-link[]"
                                                       value="<?= $errFooterSection1Links[$k] ? $errFooterSection1Links[$k]->getValue() : ''; ?>">
                                            </div>
                                            <div class="col-lg-2 form-group">
                                                <label>اولویت:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-sec1-priority[]"
                                                       value="<?= $errFooterSection1Priorities[$k] ? $errFooterSection1Priorities[$k]->getValue() : ''; ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif (isset($footerSection1['links']) && !empty($footerSection1['links'])): ?>
                                    <?php foreach ($footerSection1['links'] as $k => $item): ?>
                                        <div class="row form-group border border-warning position-relative rounded mx-0 px-2 py-3"
                                             id="__sample_footer_sec1">
                                            <div class="col-lg-4 form-group">
                                                <label>نام لینک:</label>
                                                <input type="text" class="form-control maxlength"
                                                       placeholder="تا ۵۰ کاراکتر"
                                                       maxlength="50"
                                                       name="inp-setting-sec1-name[]"
                                                       value="<?= $item['name'] ?? ''; ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>لینک:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="برای مثال:‌ http://www.example.com"
                                                       name="inp-setting-sec1-link[]"
                                                       value="<?= $item['link'] ?? ''; ?>">
                                            </div>
                                            <div class="col-lg-2 form-group">
                                                <label>اولویت:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-sec1-priority[]"
                                                       value="<?= $k; ?>">
                                            </div>
                                            <?php if (0 != $counter++): ?>
                                                <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="row form-group border border-warning position-relative rounded mx-0 px-2 py-3"
                                         id="__sample_footer_sec1">
                                        <div class="col-lg-5 form-group">
                                            <label>نام لینک:</label>
                                            <input type="text" class="form-control maxlength"
                                                   placeholder="تا ۵۰ کاراکتر"
                                                   maxlength="50"
                                                   name="inp-setting-sec1-name[]">
                                        </div>
                                        <div class="col-lg-7 form-group">
                                            <label>لینک:</label>
                                            <input type="text" class="form-control"
                                                   placeholder="برای مثال:‌ http://www.example.com"
                                                   name="inp-setting-sec1-link[]">
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>

                            <div class="pl-3">
                                <button type="button"
                                        class="btn btn-primary btn-icon rounded-full mb-3 __duplicator_btn"
                                        data-popup="tooltip"
                                        data-original-title="اضافه کردن لینک جدید"
                                        data-placement="right"
                                        data-container-element=".__footer_sec1_container"
                                        data-sample-element="#__sample_footer_sec1"
                                        data-clearable-elements='["inp-setting-sec1-name[]","inp-setting-sec1-link[]","inp-setting-sec1-priority[]"]'
                                        data-add-remove="true">
                                    <i class="icon-plus2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /footer section 1 -->

                        <?php load_partial('admin/section-header', ['header_title' => 'پاورقی - بخش دو']); ?>
                        <?php load_partial('admin/message/message-info', [
                            'info' => 'مواردی که نام لینک آن‌ها خالی است، در نظر گرفته نمی‌شوند.',
                            'dismissible' => false,
                        ]); ?>
                        <!-- Footer section 2 -->
                        <?php
                        $footerSection2 = config()->get('settings.footer_section_2.value') ?: [];
                        $errFooterSection2Names = input()->post('inp-setting-sec2-name') ?: [];
                        $errFooterSection2Links = input()->post('inp-setting-sec2-link') ?: [];
                        $errFooterSection2Priorities = input()->post('inp-setting-sec2-priority') ?: [];
                        $counter = 0;
                        ?>
                        <div class="col-lg-12 form-group">
                            <label>عنوان بخش دو:</label>
                            <input type="text" class="form-control maxlength"
                                   placeholder="تا ۵۰ کاراکتر"
                                   maxlength="50"
                                   name="inp-setting-sec2-title"
                                   value="<?= !$validator->getStatus() ? ($validator->setInput('inp-setting-sec2-title') ?: ($footerSection1['title'] ?? '')) : ($footerSection1['title'] ?? ''); ?>">
                        </div>
                        <div class="d-flex align-items-end">
                            <div class="col __footer_sec2_container">
                                <?php if (!$validator->getStatus()): ?>
                                    <?php foreach ($errFooterSection2Names as $k => $name): ?>
                                        <div class="row form-group border border-warning position-relative rounded mx-0 px-2 py-3"
                                            <?= 0 == $counter ? 'id="__sample_footer_sec2"' : ''; ?>>
                                            <div class="col-lg-4 form-group">
                                                <label>نام لینک:</label>
                                                <input type="text" class="form-control maxlength"
                                                       placeholder="تا ۵۰ کاراکتر"
                                                       maxlength="50"
                                                       name="inp-setting-sec2-name[]"
                                                       value="<?= $name->getValue(); ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>لینک:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="برای مثال:‌ http://www.example.com"
                                                       name="inp-setting-sec2-link[]"
                                                       value="<?= $errFooterSection2Links[$k] ? $errFooterSection2Links[$k]->getValue() : ''; ?>">
                                            </div>
                                            <div class="col-lg-2 form-group">
                                                <label>اولویت:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-sec2-priority[]"
                                                       value="<?= $errFooterSection2Priorities[$k] ? $errFooterSection2Priorities[$k]->getValue() : ''; ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif (isset($footerSection2['links']) && !empty($footerSection2['links'])): ?>
                                    <?php foreach ($footerSection2['links'] as $k => $item): ?>
                                        <div class="row form-group border border-warning position-relative rounded mx-0 px-2 py-3"
                                             id="__sample_footer_sec2">
                                            <div class="col-lg-4 form-group">
                                                <label>نام لینک:</label>
                                                <input type="text" class="form-control maxlength"
                                                       placeholder="تا ۵۰ کاراکتر"
                                                       maxlength="50"
                                                       name="inp-setting-sec2-name[]"
                                                       value="<?= $item['name'] ?? ''; ?>">
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>لینک:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="برای مثال:‌ http://www.example.com"
                                                       name="inp-setting-sec2-link[]"
                                                       value="<?= $item['link'] ?? ''; ?>">
                                            </div>
                                            <div class="col-lg-2 form-group">
                                                <label>اولویت:</label>
                                                <input type="text" class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-sec2-priority[]"
                                                       value="<?= $k; ?>">
                                            </div>
                                            <?php if (0 != $counter++): ?>
                                                <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="row form-group border border-warning position-relative rounded mx-0 px-2 py-3"
                                         id="__sample_footer_sec2">
                                        <div class="col-lg-5 form-group">
                                            <label>نام لینک:</label>
                                            <input type="text" class="form-control maxlength"
                                                   placeholder="تا ۵۰ کاراکتر"
                                                   maxlength="50"
                                                   name="inp-setting-sec2-name[]">
                                        </div>
                                        <div class="col-lg-7 form-group">
                                            <label>لینک:</label>
                                            <input type="text" class="form-control"
                                                   placeholder="برای مثال:‌ http://www.example.com"
                                                   name="inp-setting-sec2-link[]">
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>

                            <div class="pl-3">
                                <button type="button"
                                        class="btn btn-primary btn-icon rounded-full mb-3 __duplicator_btn"
                                        data-popup="tooltip"
                                        data-original-title="اضافه کردن لینک جدید"
                                        data-placement="right"
                                        data-container-element=".__footer_sec2_container"
                                        data-sample-element="#__sample_footer_sec2"
                                        data-clearable-elements='["inp-setting-sec2-name[]","inp-setting-sec2-link[]","inp-setting-sec2-priority[]"]'
                                        data-add-remove="true">
                                    <i class="icon-plus2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /footer section 2 -->

                        <?php load_partial('admin/section-header', [
                            'header_title' => 'نمادها',
                            'element' => '<button type="button"' .
                                ' class="btn btn-light btn-icon rounded __duplicator_btn"' .
                                ' data-popup="tooltip"' .
                                ' data-original-title="اضافه کردن نماد جدید"' .
                                ' data-placement="right"' .
                                ' data-container-element=".__all_namads_container"' .
                                ' data-sample-element="#__sample_namad"' .
                                ' data-clearable-elements=\'["inp-setting-namads[]"]\'' .
                                ' data-add-remove="true">' .
                                '<i class="icon-plus2" aria-hidden="true"></i>' .
                                '</button>',
                        ]); ?>
                        <!-- Namads -->
                        <?php
                        $namads = config()->get('settings.footer_namads.value') ?: [];
                        $errNamads = input()->post('inp-setting-namads') ?: [];
                        $counter = 0;
                        ?>
                        <div class="__all_namads_container">
                            <?php if (!$validator->getStatus()): ?>
                                <?php foreach ($errNamads as $namad): ?>
                                    <div class="form-group position-relative" <?= 0 == $counter ? 'id="__sample_namad"' : ''; ?>>
                                        <textarea class="form-control form-control-min-height ltr"
                                                  placeholder="نماد را وارد کنید"
                                                  name="inp-setting-namads[]"
                                        ><?= $namad->getValue(); ?></textarea>
                                        <?php if (0 != $counter++): ?>
                                            <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif (count($namads)): ?>
                                <?php foreach ($namads as $namad): ?>
                                    <div class="form-group position-relative" <?= 0 == $counter ? 'id="__sample_namad"' : ''; ?>>
                                        <textarea class="form-control form-control-min-height ltr"
                                                  placeholder="نماد را وارد کنید"
                                                  name="inp-setting-namads[]"
                                        ><?= $namad; ?></textarea>
                                        <?php if (0 != $counter++): ?>
                                            <?php load_partial('admin/parser/dynamic-remover-btn'); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="form-group position-relative" id="__sample_namad">
                                    <textarea class="form-control form-control-min-height ltr"
                                              placeholder="نماد را وارد کنید"
                                              name="inp-setting-namads[]"
                                    ></textarea>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- /namads -->
                    </div>

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

</div>
<!-- /content area -->
