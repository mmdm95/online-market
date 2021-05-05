<?php

use App\Logic\Utils\Jdf;

?>

<footer class="footer_dark">
    <div class="footer_top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="widget">
                        <div class="footer_logo">
                            <a href="<?= url('home.index'); ?>">
                                <img src="<?= url('image.show') . \config()->get('settings.logo_light_footer.value'); ?>"
                                     alt="logo"/>
                            </a>
                        </div>
                        <?php
                        $tinyDesc = \config()->get('settings.footer_tiny_desc.value');
                        ?>
                        <?php if (!empty($tinyDesc)): ?>
                            <p><?= $tinyDesc; ?></p>
                        <?php endif; ?>
                    </div>
                    <?php
                    $telegram = \config()->get('settings.social_telegram.value');
                    $whatsapp = \config()->get('settings.social_whatsapp.value');
                    $instagram = \config()->get('settings.social_instagram.value');
                    ?>
                    <?php if (!empty($telegram) || !empty($instagram) || !empty($whatsapp)): ?>
                        <div class="widget">
                            <ul class="social_icons social_white">
                                <?php if (!empty($telegram)): ?>
                                    <?php foreach (explode(',', $telegram) as $item): ?>
                                        <li>
                                            <a href="<?= $item; ?>">
                                                <i class="ion-ios-paperplane-outline"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (!empty($whatsapp)): ?>
                                    <?php foreach (explode(',', $whatsapp) as $item): ?>
                                        <li>
                                            <a href="<?= $item; ?>">
                                                <i class="ion-social-whatsapp-outline"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (!empty($instagram)): ?>
                                    <?php foreach (explode(',', $instagram) as $item): ?>
                                        <li>
                                            <a href="<?= $item; ?>">
                                                <i class="ion-social-instagram-outline"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <?php
                $section1 = \config()->get('settings.footer_section_1.value');
                $section2 = \config()->get('settings.footer_section_2.value');
                ?>
                <?php if (count($section1['links'])): ?>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="widget">
                            <div class="widget">
                                <h6 class="widget_title"><?= $section1['title']; ?></h6>
                                <ul class="widget_links">
                                    <?php foreach ($section1['links'] as $link): ?>
                                        <li><a href="<?= $link['link']; ?>"><?= $link['name']; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (count($section2['links'])): ?>
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
                $address = \config()->get('settings.address.value');
                $phones = \config()->get('settings.phones.value');
                $phones = explode(',', $phones);
                $phones = array_filter($phones, function ($val) {
                    if (is_string($val) && !empty(trim($val))) return true;
                    return false;
                });
                $email = \config()->get('settings.email.value');
                ?>
                <?php if (!empty($address) || !empty($phones) || !empty($email)): ?>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="widget">
                            <h6 class="widget_title">اطلاعات تماس</h6>
                            <ul class="contact_info contact_info_light">
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
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    $copyright = \config()->get('settings.footer_copyright.value');
    ?>
    <?php if (!empty($copyright)): ?>
        <div class="bottom_footer border-top-tran">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-md-0 text-center text-md-left">
                            ©
                            <?= Jdf::jdate('Y'); ?>
                            <?= $copyright; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</footer>