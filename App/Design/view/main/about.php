<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION ABOUT -->
    <div class="section">
        <div class="custom-container container">
            <div class="row align-items-center">
                <?php
                $aboutDesc = \config()->get('settings.about_section.value');
                ?>
                <div class="col-lg-6">
                    <div class="about_img scene mb-4 mb-lg-0">
                        <img src="" data-src="<?= url('image.show', ['filename' => $aboutDesc['image']]); ?>"
                             alt="about_img" class="lazy">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="heading_s1">
                        <h2><?= $aboutDesc['title']; ?></h2>
                    </div>
                    <p>
                        <?= $aboutDesc['desc']; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION ABOUT -->

    <!-- START SECTION TEAM -->
    <?php if (count($our_team ?? [])): ?>
        <div class="section pb_70">
            <div class="custom-container container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="heading_s1 text-center">
                            <h2>اعضای تیم ما</h2>
                        </div>
                        <?php
                        $subTitleTeam = \config()->get('settings.our_team.value.sub_title');
                        ?>
                        <?php if (!empty($subTitleTeam)): ?>
                            <p class="text-center leads">
                                <?= $subTitleTeam; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <?php foreach ($our_team as $member): ?>
                        <div class="col-lg-3 col-sm-6">
                            <div class="team_box team_style1">
                                <?php
                                $socialsTeam = $member['socials'];
                                $hasSocial = !empty($socialsTeam['whatsapp']) ||
                                    !empty($socialsTeam['telegram']) || !empty($socialsTeam['instagram']);
                                ?>
                                <?php if ($hasSocial): ?>
                                    <div class="team_img">
                                        <img src="" data-src="assets/images/team_img1.jpg" alt="team_img1" class="lazy">
                                        <ul class="social_icons social_style1">
                                            <?php if ($socialsTeam['telegram']): ?>
                                                <li><a href="#"><i class="ion-ios-paperplane-outline"></i></a></li>
                                            <?php endif; ?>
                                            <?php if ($socialsTeam['whatsapp']): ?>
                                                <li><a href="#"><i class="ion-social-whatsapp-outline"></i></a></li>
                                            <?php endif; ?>
                                            <?php if ($socialsTeam['instagram']): ?>
                                                <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <div class="team_content">
                                    <div class="team_title">
                                        <h5><?= $member['name']; ?></h5>
                                        <?php if (!empty($member['position'])): ?>
                                            <span><?= $member['position'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- END SECTION TEAM -->


    <!-- START SECTION SHOP INFO -->
    <div class="section pb_70">
        <div class="custom-container container">
            <div class="row no-gutters">
                <?php
                $features = \config()->get('settings.features.value') ?: [];
                $feature1 = $features[0] ?? ['title' => '', 'sub_title' => ''];
                $feature2 = $features[1] ?? ['title' => '', 'sub_title' => ''];
                $feature3 = $features[2] ?? ['title' => '', 'sub_title' => ''];
                ?>
                <div class="col-lg-4">
                    <div class="icon_box icon_box_style1">
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
                <div class="col-lg-4">
                    <div class="icon_box icon_box_style1">
                        <div class="icon">
                            <i class="flaticon-money-back"></i>
                        </div>
                        <div class="icon_box_content">
                            <h5><?= $feature2['title']; ?></h5>
                            <?php if (!empty($feature2['sub_title'])): ?>
                                <p><?= $feature2['sub_title']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="icon_box icon_box_style1">
                        <div class="icon">
                            <i class="flaticon-support"></i>
                        </div>
                        <div class="icon_box_content">
                            <h5><?= $feature3['title']; ?></h5>
                            <?php if (!empty($feature3['sub_title'])): ?>
                                <p><?= $feature3['sub_title']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP INFO -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->