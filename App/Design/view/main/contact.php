<?php

$validator = form_validator();

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION CONTACT -->
    <div class="section pb_70">
        <div class="custom-container container">
            <div class="row justify-content-center">
                <?php
                $address = \config()->get('settings.address.value');
                $email = \config()->get('settings.email.value');
                $phone = \config()->get('settings.main_phone.value');
                ?>

                <?php if (!empty($address)): ?>
                    <div class="col-xl-4 col-md-12 stretch-card">
                        <div class="contact_wrap contact_style3 w-100">
                            <div class="contact_icon">
                                <i class="linearicons-map2"></i>
                            </div>
                            <div class="contact_text">
                                <span>آدرس</span>
                                <p><?= $address; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($email)): ?>
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="contact_wrap contact_style3 w-100">
                            <div class="contact_icon">
                                <i class="linearicons-envelope-open"></i>
                            </div>
                            <div class="contact_text">
                                <span>آدرس ایمیل</span>
                                <a href="mailto:<?= hexentities($email); ?>"><?= hexentities($email); ?> </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($phone)): ?>
                    <div class="col-xl-4 col-md-6 stretch-card">
                        <div class="contact_wrap contact_style3 w-100">
                            <div class="contact_icon">
                                <i class="linearicons-tablet2"></i>
                            </div>
                            <div class="contact_text">
                                <span>تلفن</span>
                                <p><?= local_number($phone); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- END SECTION CONTACT -->

    <!-- START SECTION CONTACT -->
    <div class="section pt-0">
        <div class="custom-container container">
            <div class="row">
                <div class="col-lg-6" id="__contact_form_container">
                    <div class="heading_s1">
                        <h2>ارسال پیام</h2>
                    </div>
                    <p class="leads">
                        پیام خود را با ما در میان بگذارید.
                    </p>
                    <div class="field_form">
                        <form action="<?= url('home.contact')->getRelativeUrlTrimmed(); ?>#__contact_form_container"
                              method="post" id="__form_contact">
                            <?php load_partial('main/message/message-form', [
                                'errors' => $contact_errors ?? [],
                                'success' => $contact_success ?? '',
                                'warning' => $contact_warning ?? '',
                            ]); ?>
                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input placeholder="نام را وارد کنید *"
                                           class="form-control" name="inp-contact-name" type="text" required
                                           value="<?= $validator->setInput('inp-contact-name'); ?>">
                                </div>
                                <div class="form-group col-md-6 ltr">
                                    <input placeholder="ایمیل را وارد کنید" class="form-control text-left"
                                           name="inp-contact-email" type="text" required
                                           value="<?= $validator->setInput('inp-contact-email'); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <input placeholder="شماره موبایل خود را وارد کنید *"
                                           class="form-control" name="inp-contact-mobile" type="text" required
                                           value="<?= $validator->setInput('inp-contact-mobile'); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <input placeholder="موضوع را وارد کنید" class="form-control"
                                           name="inp-contact-subject" type="text" required
                                           value="<?= $validator->setInput('inp-contact-subject'); ?>">
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea placeholder="پیام *" class="form-control"
                                              name="inp-contact-message" required
                                              rows="4"><?= $validator->setInput('inp-contact-message'); ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <input placeholder="کد تصویر را وارد کنید *" class="form-control"
                                           name="inp-contact-captcha" required>
                                </div>
                                <div class="form-group col-md-6 d-flex justify-content-center align-items-center __captcha_main_container">
                                    <div class="__captcha_container">
                                    </div>
                                    <button class="btn btn-link text_default p-2 ml-3 __captcha_regenerate_btn"
                                            type="button" aria-label="regenerate captcha">
                                        <input type="hidden" name="inp-captcha-name"
                                               value="<?= url() . '__form_contact'; ?>">
                                        <i class="icon-refresh icon-2x" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-fill-out">
                                        ارسال پیام
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6 pt-2 pt-lg-0 mt-4 mt-lg-0">
                    <div id="map" class="contact_map2" data-zoom="14"
                         data-latitude="<?= \config()->get('settings.lat_lng.value.lat'); ?>"
                         data-longitude="<?= \config()->get('settings.lat_lng.value.lng'); ?>"
                         data-icon="<?= asset_path('image/marker.png', false); ?>"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION CONTACT -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->