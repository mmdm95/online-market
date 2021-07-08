<?php if (count($main_slider)): ?>
    <div class="mt-4 staggered-animation-wrap">
        <div class="custom-container container">
            <?php
            $col_1 = 'col-lg-7';
            $col_2 = 'col-lg-2';
            $hasSideImages = true;
            $sideImages = config()->get('settings.index_main_slider_side_images.value');

            if (!is_array($sideImages) || !count($sideImages)) {
                $col_1 = 'col-lg-9';
                $col_2 = '';
                $hasSideImages = false;
            }
            ?>

            <div class="row">
                <div class="<?= $col_1; ?> offset-lg-3">
                    <div class="banner_section shop_el_slider">
                        <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow"
                             data-ride="carousel">
                            <div class="carousel-inner">
                                <?php $k = 0; ?>
                                <?php foreach ($main_slider as $item): ?>
                                    <div class="carousel-item background_bg <?= 0 == $k ? 'active' : ''; ?>"
                                         data-img-src="<?= url('image.show') . $item['image']; ?>">
                                        <div class="banner_slide_content banner_content_inner">
                                            <div class="col-lg-7 col-10">
                                                <div class="banner_content3 overflow-hidden">
                                                    <?php if (isset($item['note']) && !empty($item['note'])): ?>
                                                        <h5 class="mb-3 staggered-animation font-weight-light"
                                                            data-animation="slideInRight" data-animation-delay="0.5s">
                                                            <?= $item['note']; ?>
                                                        </h5>
                                                    <?php endif; ?>
                                                    <?php if (isset($item['title']) && !empty($item['title'])): ?>
                                                        <h2 class="staggered-animation" data-animation="slideInRight"
                                                            data-animation-delay="1s">
                                                            <?= $item['title']; ?>
                                                        </h2>
                                                    <?php endif; ?>
                                                    <?php if (isset($item['link']) && !empty($item['link'])): ?>
                                                        <a class="btn btn-fill-out btn-radius staggered-animation text-uppercase"
                                                           href="<?= $item['link']; ?>" data-animation="slideInRight"
                                                           data-animation-delay="1.5s">جزئیات بیشتر</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $k++; ?>
                                <?php endforeach; ?>
                            </div>
                            <ol class="carousel-indicators indicators_style3">
                                <?php $k = 0; ?>
                                <?php foreach ($main_slider as $item): ?>
                                    <li data-target="#carouselExampleControls"
                                        data-slide-to="<?= $k; ?>" <?= 0 === $k ? 'class="active"' : ''; ?>></li>
                                    <?php $k++; ?>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    </div>
                </div>

                <?php if ($hasSideImages): ?>
                    <div class="<?= $col_2; ?> d-none d-lg-block">
                        <?php if (
                            isset($sideImages[0]['image']) &&
                            !empty($sideImages[0]['image']) &&
                            is_image_exists($sideImages[0]['image'])
                        ): ?>
                            <div class="shop_banner2 el_banner1"
                                 style="background-color: <?= $sideImages[0]['color']; ?>;">
                                <a href="<?= $sideImages[0]['link'] ?? '#'; ?>"
                                   class="hover_effect1">
                                    <?php if (isset($sideImages[0]['title']) || isset($sideImages[0]['sub_title'])): ?>
                                        <div class="el_title text_white">
                                            <?php if (isset($sideImages[0]['title'])): ?>
                                                <h6><?= $sideImages[0]['title']; ?></h6>
                                            <?php endif; ?>
                                            <?php if (isset($sideImages[0]['sub_title'])): ?>
                                                <span><?= $sideImages[0]['sub_title']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="el_img">
                                        <img src="<?= url('image.show', ['filename' => $sideImages[0]['image']]); ?>"
                                             alt="<?= $sideImages[0]['title'] ?: 'side image 1'; ?>">
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (
                            isset($sideImages[1]['image']) &&
                            !empty($sideImages[1]['image']) &&
                            is_image_exists($sideImages[1]['image'])
                        ): ?>
                            <div class="shop_banner2 el_banner2">
                                <a href="<?= $sideImages[1]['link'] ?? '#'; ?>"
                                   class="hover_effect1">
                                    <?php if (isset($sideImages[1]['title']) || isset($sideImages[1]['sub_title'])): ?>
                                        <div class="el_title text_white">
                                            <?php if (isset($sideImages[1]['title'])): ?>
                                                <h6><?= $sideImages[1]['title']; ?></h6>
                                            <?php endif; ?>
                                            <?php if (isset($sideImages[1]['sub_title'])): ?>
                                                <span><?= $sideImages[1]['sub_title']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="el_img">
                                        <img src="<?= url('image.show', ['filename' => $sideImages[1]['image']]); ?>"
                                             alt="<?= $sideImages[1]['title'] ?: 'side image 1'; ?>">
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>