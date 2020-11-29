<?php if (count($main_slider)): ?>
    <div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 offset-lg-3">
                    <div id="carouselExampleControls" class="carousel slide light_arrow" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($main_slider as $k => $item): ?>
                                <a href="<?= $item['link']; ?>" class="carousel-item active background_bg"
                                   data-img-src="<?= url('image.show') . $item['image']; ?>">
                                    <div class="banner_slide_content banner_content_inner">
                                        <div class="col-lg-8 col-10">
                                            <div class="banner_content overflow-hidden">
                                                <?php if (isset($item['note'])): ?>
                                                    <h5 class="mb-3 staggered-animation font-weight-light"
                                                        data-animation="slideInRight" data-animation-delay="0.5s">
                                                        <?= $item['note']; ?>
                                                    </h5>
                                                <?php endif; ?>
                                                <?php if (isset($item['title'])): ?>
                                                    <h2 class="staggered-animation" data-animation="slideInRight"
                                                        data-animation-delay="1s">
                                                        <?= $item['title']; ?>
                                                    </h2>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <ol class="carousel-indicators indicators_style1">
                            <?php foreach ($main_slider as $k => $item): ?>
                                <li data-target="#carouselExampleControls"
                                    data-slide-to="<?= $k; ?>" <?= 0 === $k ? 'class="active"' : ''; ?>></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>