<?php if (count($instagram_images ?? [])): ?>
    <div class="section small_pt small_pb">
        <div class="custom-container container">
            <div class="row">
                <div class="col-12">
                    <div class="follow_box">
                        <?php
                        $instagramAccount = \config()->get('settings.social_instagram.value');
                        $instagramAccount = explode(',', trim($instagramAccount))[0];
                        ?>

                        <i class="ti-instagram"></i>
                        <h3>اینستاگرام</h3>
                        <a href="<?= $instagramAccount; ?>" target="_blank"
                           class="ltr d-inline-block">
                            به ما بپیوندید
                        </a>
                    </div>

                    <div class="client_logo carousel_slider owl-carousel owl-theme" data-dots="false" data-margin="0"
                         data-loop="true" data-autoplay="true"
                         data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "6"}}'>
                        <?php foreach ($instagram_images as $image): ?>
                            <div class="item">
                                <div class="instafeed_box">
                                    <a href="<?= $image['link']; ?>" target="_blank">
                                        <img src="" data-src="<?= url('image.show') . $image['image']; ?>"
                                             alt="<?= $image['link']; ?>" class="lazy">
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>