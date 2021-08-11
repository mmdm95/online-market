<?php

use App\Logic\Utils\Jdf;

?>

<footer class="bg_gray">
    <div class="middle_footer">
        <div class="custom-container container">
            <div class="row">
                <div class="col-12">
                    <div class="shopping_info">
                        <div class="row justify-content-center">
                            <?php
                            $features = \config()->get('settings.features.value') ?: [];
                            $feature1 = $features[0] ?? [];
                            $feature2 = $features[1] ?? [];
                            $feature3 = $features[2] ?? [];
                            ?>
                            <?php if (count($feature1) && isset($feature1) && '' != $feature1['title']): ?>
                                <div class="col-md-4">
                                    <div class="icon_box icon_box_style2">
                                        <div class="icon">
                                            <i class="flaticon-shipped"></i>
                                        </div>
                                        <div class="icon_box_content">
                                            <h5><?= $feature1['title']; ?></h5>
                                            <?php if (!empty($feature1['sub_title'])): ?>
                                                <p><?= $feature1['sub_title']; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (count($feature2) && isset($feature2) && '' != $feature2['title']): ?>
                                <div class="col-md-4">
                                    <div class="icon_box icon_box_style2">
                                        <div class="icon">
                                            <i class="flaticon-money-back"></i>
                                        </div>
                                        <div class="icon_box_content">
                                            <h5><?= $feature2['title']; ?></h5>
                                            <?php if (!empty($feature1['sub_title'])): ?>
                                                <p><?= $feature2['sub_title']; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (count($feature3) && isset($feature3) && '' != $feature3['title']): ?>
                                <div class="col-md-4">
                                    <div class="icon_box icon_box_style2">
                                        <div class="icon">
                                            <i class="flaticon-support"></i>
                                        </div>
                                        <div class="icon_box_content">
                                            <h5><?= $feature3['title']; ?></h5>
                                            <?php if (!empty($feature1['sub_title'])): ?>
                                                <p><?= $feature3['sub_title']; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer_top small_pt pb_20">
        <div class="custom-container container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="widget">
                        <?php
                        $tinyDesc = \config()->get('settings.footer_tiny_desc.value');
                        ?>
                        <?php if (!empty($tinyDesc)): ?>
                            <p class="mb-3"><?= $tinyDesc; ?></p>
                        <?php endif; ?>
                        <ul class="contact_info">
                            <?php
                            $address = \config()->get('settings.address.value');
                            $phones = \config()->get('settings.phones.value');
                            $phones = explode(',', $phones);
                            $phones = array_filter($phones, function ($val) {
                                if (is_string($val) && !empty(trim($val))) return true;
                                return false;
                            });
                            $email = \config()->get('settings.email.value');
                            ?>
                            <?php if (!empty($address)): ?>
                                <li>
                                    <i class="ti-location-pin"></i>
                                    <p><?= nl2br($address); ?></p>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($email)): ?>
                                <li>
                                    <i class="ti-email"></i>
                                    <a href="mailto:<?= hexentities($email); ?>"><?= hexentities($email); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if (count($phones)): ?>
                                <li>
                                    <i class="ti-mobile"></i>
                                    <p>
                                        <?php foreach ($phones as $phone): ?>
                                            <span class="d-block">
                                                <?= local_number($phone); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <?php
                $section1 = \config()->get('settings.footer_section_1.value');
                $section2 = \config()->get('settings.footer_section_2.value');
                ?>
                <?php if (count($section1['links'] ?? [])): ?>
                    <?php
                    ksort($section1['links']);
                    ?>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="widget">
                            <h6 class="widget_title"><?= $section1['title']; ?></h6>
                            <ul class="widget_links">
                                <?php foreach ($section1['links'] as $link): ?>
                                    <li><a href="<?= $link['link']; ?>"><?= $link['name']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (count($section2['links'] ?? [])): ?>
                    <?php
                    ksort($section2['links']);
                    ?>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="widget">
                            <h6 class="widget_title"><?= $section2['title']; ?></h6>
                            <ul class="widget_links">
                                <?php foreach ($section2['links'] as $link): ?>
                                    <li><a href="<?= $link['link']; ?>"><?= $link['name']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                $namads = \config()->get('settings.footer_namads.value') ?: [];
                ?>
                <?php if (!empty($namads)): ?>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="widget">
                            <ul class="widget_instafeed namad_img">
                                <?php foreach ($namads as $namad): ?>
                                    <li>
                                        <?= html_entity_decode($namad); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bottom_footer border-top-tran mt-0">
        <div class="custom-container container">
            <div class="row align-items-center">
                <?php
                $copyright = \config()->get('settings.footer_copyright.value');
                ?>
                <?php if (!empty($copyright)): ?>
                    <div class="col-lg-6">
                        <p class="mb-lg-0 text-center">
                            ©
                            <?= Jdf::jdate('Y'); ?>
                            <?= $copyright; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php
                $telegram = \config()->get('settings.social_telegram.value');
                $whatsapp = \config()->get('settings.social_whatsapp.value');
                $instagram = \config()->get('settings.social_instagram.value');
                ?>
                <?php if (!empty($telegram) || !empty($instagram) || !empty($whatsapp)): ?>
                    <div class="col-lg-6 order-lg-first">
                        <div class="widget mb-0">
                            <ul class="social_icons text-center text-lg-left">
                                <?php if (!empty($telegram)): ?>
                                    <?php foreach (explode(',', $telegram) as $item): ?>
                                        <li>
                                            <a href="<?= $item; ?>" class="sc_telegram">
                                                <i class="linearicons-paper-plane"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (!empty($whatsapp)): ?>
                                    <?php foreach (explode(',', $whatsapp) as $item): ?>
                                        <li>
                                            <a href="<?= $item; ?>" class="sc_whatsapp">
                                                <i class="ion-social-whatsapp-outline"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (!empty($instagram)): ?>
                                    <?php foreach (explode(',', $instagram) as $item): ?>
                                        <li>
                                            <a href="<?= $item; ?>" class="sc_instagram">
                                                <i class="ion-social-instagram-outline"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>