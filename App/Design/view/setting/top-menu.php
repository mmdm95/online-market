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
            <form action="<?= url('admin.setting.top-menu')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_top_menu">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_top_menu_errors ?? [],
                    'success' => $setting_top_menu_success ?? '',
                    'warning' => $setting_top_menu_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات منوی بالای صفحه']); ?>

                    <div class="px-3 pt-3">
                        <?php load_partial('admin/message/message-info', [
                            'info' => 'مواردی که عنوان آن‌ها خالی است، در نظر گرفته نمی‌شوند.',
                            'dismissible' => false,
                        ]); ?>
                    </div>

                    <div class="card-body">
                        <?php
                        $menu = input()->post('inp-setting-menu');
                        $subMenu = input()->post('inp-setting-sub-menu');
                        $topMenu = config()->get('settings.top_menu.value') ?: [];
                        ?>

                        <div class="__all_menu_container">
                            <?php if (!$validator->getStatus()): ?>
                                <?php foreach ($menu as $k => $main): ?>
                                    <div class="border-info border-2 border-dashed rounded p-2 mb-3 position-relative __menu_items __sample_menu_item">
                                        <div class="row m-0">
                                            <div class="col-lg-4 form-group">
                                                <label>
                                                    عنوان منو
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-setting-menu[<?= $k; ?>][title]"
                                                       value="<?= $main['title']->getValue(); ?>">
                                            </div>
                                            <div class="col-lg-5 form-group">
                                                <label>
                                                    لینک
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="مانند: http://www.example.com/about"
                                                       name="inp-setting-menu[<?= $k; ?>][link]"
                                                       value="<?= $main['link']->getValue(); ?>">
                                            </div>
                                            <div class="col-lg-3 form-group">
                                                <label>
                                                    اولویت
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-menu[<?= $k; ?>][priority]"
                                                       value="<?= $main['priority']->getValue(); ?>">
                                            </div>
                                        </div>

                                        <div class="__all_sub_menu_container">
                                            <?php if (($subMenu[$k] ?? [])): ?>
                                                <?php foreach (($subMenu[$k]) as $k2 => $sub): ?>
                                                    <div class="row m-0 position-relative border-violet border-2 border-dashed rounded p-2 my-3 __sub_menu_items __sample_sub_menu_item">
                                                        <div class="col-lg-4 form-group">
                                                            <label>
                                                                عنوان زیر منو
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="وارد کنید"
                                                                   name="inp-setting-sub-menu[<?= $k; ?>][<?= $k2; ?>][sub-title]"
                                                                   value="<?= $sub['sub-title']->getValue(); ?>">
                                                        </div>
                                                        <div class="col-lg-5 form-group">
                                                            <label>
                                                                لینک
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="مانند: http://www.example.com/about"
                                                                   name="inp-setting-sub-menu[<?= $k; ?>][<?= $k2; ?>][sub-link]"
                                                                   value="<?= $sub['sub-link']->getValue(); ?>">
                                                        </div>
                                                        <div class="col-lg-3 form-group">
                                                            <label>
                                                                اولویت
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="از نوع عددی"
                                                                   name="inp-setting-sub-menu[<?= $k; ?>][<?= $k2; ?>][sub-priority]"
                                                                   value="<?= $sub['sub-priority']->getValue(); ?>">
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row m-0 position-relative border-violet border-2 border-dashed rounded p-2 my-3 __sub_menu_items __sample_sub_menu_item">
                                                    <div class="col-lg-4 form-group">
                                                        <label>
                                                            عنوان زیر منو
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="وارد کنید"
                                                               name="inp-setting-sub-menu[<?= $k; ?>][0][sub-title]">
                                                    </div>
                                                    <div class="col-lg-5 form-group">
                                                        <label>
                                                            لینک
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="مانند: http://www.example.com/about"
                                                               name="inp-setting-sub-menu[<?= $k; ?>][0][sub-link]">
                                                    </div>
                                                    <div class="col-lg-3 form-group">
                                                        <label>
                                                            اولویت
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="از نوع عددی"
                                                               name="inp-setting-sub-menu[<?= $k; ?>][0][sub-priority]">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-7 col-lg-5 ml-auto">
                                                <button type="button"
                                                        class="btn bg-white btn-block border-violet border-3 __sub_menu_cloner">
                                                    زیر دسته جدید
                                                    <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif (count($topMenu)): ?>
                                <?php
                                $counter = 0;
                                ?>
                                <?php foreach ($topMenu as $k => $main): ?>
                                    <div class="border-info border-2 border-dashed rounded p-2 mb-3 position-relative __menu_items __sample_menu_item">
                                        <div class="row m-0">
                                            <div class="col-lg-4 form-group">
                                                <label>
                                                    عنوان منو
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-setting-menu[<?= $counter; ?>][title]"
                                                       value="<?= $main['name'] ?>">
                                            </div>
                                            <div class="col-lg-5 form-group">
                                                <label>
                                                    لینک
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="مانند: http://www.example.com/about"
                                                       name="inp-setting-menu[<?= $counter; ?>][link]"
                                                       value="<?= $main['link'] ?>">
                                            </div>
                                            <div class="col-lg-3 form-group">
                                                <label>
                                                    اولویت
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-menu[<?= $counter; ?>][priority]"
                                                       value="<?= $k; ?>">
                                            </div>
                                        </div>

                                        <div class="__all_sub_menu_container">
                                            <?php if (count($main['children'] ?: [])): ?>
                                                <?php
                                                $counter2 = 0;
                                                ?>
                                                <?php foreach ($main['children'] as $k2 => $sub): ?>
                                                    <div class="row m-0 position-relative border-violet border-2 border-dashed rounded p-2 my-3 __sub_menu_items __sample_sub_menu_item">
                                                        <div class="col-lg-4 form-group">
                                                            <label>
                                                                عنوان زیر منو
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="وارد کنید"
                                                                   name="inp-setting-sub-menu[<?= $counter; ?>][<?= $counter2; ?>][sub-title]"
                                                                   value="<?= $sub['name']; ?>">
                                                        </div>
                                                        <div class="col-lg-5 form-group">
                                                            <label>
                                                                لینک
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="مانند: http://www.example.com/about"
                                                                   name="inp-setting-sub-menu[<?= $counter; ?>][<?= $counter2; ?>][sub-link]"
                                                                   value="<?= $sub['link']; ?>">
                                                        </div>
                                                        <div class="col-lg-3 form-group">
                                                            <label>
                                                                اولویت
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   placeholder="از نوع عددی"
                                                                   name="inp-setting-sub-menu[<?= $counter; ?>][<?= $counter2; ?>][sub-priority]"
                                                                   value="<?= $k2; ?>">
                                                        </div>
                                                    </div>
                                                    <?php $counter2++; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row m-0 position-relative border-violet border-2 border-dashed rounded p-2 my-3 __sub_menu_items __sample_sub_menu_item">
                                                    <div class="col-lg-4 form-group">
                                                        <label>
                                                            عنوان زیر منو
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="وارد کنید"
                                                               name="inp-setting-sub-menu[<?= $counter; ?>][0][sub-title]">
                                                    </div>
                                                    <div class="col-lg-5 form-group">
                                                        <label>
                                                            لینک
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="مانند: http://www.example.com/about"
                                                               name="inp-setting-sub-menu[<?= $counter; ?>][0][sub-link]">
                                                    </div>
                                                    <div class="col-lg-3 form-group">
                                                        <label>
                                                            اولویت
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               placeholder="از نوع عددی"
                                                               name="inp-setting-sub-menu[<?= $counter; ?>][0][sub-priority]">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-7 col-lg-5 ml-auto">
                                                <button type="button"
                                                        class="btn bg-white btn-block border-violet border-3 __sub_menu_cloner">
                                                    زیر دسته جدید
                                                    <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $counter++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="border-info border-2 border-dashed rounded p-2 mb-3 position-relative __menu_items __sample_menu_item">
                                    <div class="row m-0">
                                        <div class="col-lg-4 form-group">
                                            <label>
                                                عنوان منو
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="وارد کنید"
                                                   name="inp-setting-menu[0][title]">
                                        </div>
                                        <div class="col-lg-5 form-group">
                                            <label>
                                                لینک
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="مانند: http://www.example.com/about"
                                                   name="inp-setting-menu[0][link]">
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label>
                                                اولویت
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="از نوع عددی"
                                                   name="inp-setting-menu[0][priority]">
                                        </div>
                                    </div>

                                    <div class="__all_sub_menu_container">
                                        <div class="row m-0 position-relative border-violet border-2 border-dashed rounded p-2 my-3 __sub_menu_items __sample_sub_menu_item">
                                            <div class="col-lg-4 form-group">
                                                <label>
                                                    عنوان زیر منو
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="وارد کنید"
                                                       name="inp-setting-sub-menu[0][0][sub-title]">
                                            </div>
                                            <div class="col-lg-5 form-group">
                                                <label>
                                                    لینک
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="مانند: http://www.example.com/about"
                                                       name="inp-setting-sub-menu[0][0][sub-link]">
                                            </div>
                                            <div class="col-lg-3 form-group">
                                                <label>
                                                    اولویت
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       placeholder="از نوع عددی"
                                                       name="inp-setting-sub-menu[0][0][sub-priority]">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-7 col-lg-5 ml-auto">
                                            <button type="button"
                                                    class="btn bg-white btn-block border-violet border-3 __sub_menu_cloner">
                                                زیر دسته جدید
                                                <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8 col-lg-6 mx-auto">
                                <button type="button" id="__menu_cloner"
                                        class="btn bg-white btn-block border-info border-3">
                                    افزودن منوی جدید
                                    <i class="icon-plus2 text-dark ml-2" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
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
