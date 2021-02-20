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
            <form action="<?= url('admin.setting.social')->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_setting_social">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $setting_social_errors ?? [],
                    'success' => $setting_social_success ?? '',
                    'warning' => $setting_social_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <!-- Sidebars overview -->
                <div class="card">
                    <?php load_partial('admin/setting-header', ['header_title' => 'تنظیمات شبکه‌های اجتماعی']); ?>

                    <div class="card-body">
                        <div class="form-group">
                            <label>
                                آدرس‌های تلگرام:
                            </label>
                            <input type="text" class="form-control tags-input"
                                   placeholder="وارد کنید"
                                   name="inp-setting-telegram"
                                   value="<?= $validator->setInput('inp-setting-telegram') ?: config()->get('settings.social_telegram.value'); ?>">
                        </div>
                        <div class="form-group">
                            <label>
                                آدرس‌های اینستاگرام:
                            </label>
                            <input type="text" class="form-control tags-input"
                                   placeholder="وارد کنید"
                                   name="inp-setting-instagram"
                                   value="<?= $validator->setInput('inp-setting-instagram') ?: config()->get('settings.social_instagram.value'); ?>">
                        </div>
                        <div class="form-group">
                            <label>
                                آدرس‌های واتس اپ:
                            </label>
                            <input type="text" class="form-control tags-input"
                                   placeholder="وارد کنید"
                                   name="inp-setting-whatsapp"
                                   value="<?= $validator->setInput('inp-setting-whatsapp') ?: config()->get('settings.social_whatsapp.value'); ?>">
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
