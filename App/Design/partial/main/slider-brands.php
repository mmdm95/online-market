<?php if (count($brands ?? [])): ?>
    <div class="section small_pb">
        <div class="custom-container container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading_tab_header">
                        <div class="heading_s2">
                            <h4>برندهای ما</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="client_logo carousel_slider owl-carousel owl-theme nav_style3" data-dots="false"
                         data-nav="true" data-margin="30" data-loop="true" data-autoplay="true"
                         data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "5"}, "1199":{"items": "6"}}'>
                        <?php foreach ($brands as $brand): ?>
                            <div class="item">
                                <div class="cl_logo">
                                    <a href="<?= url('home.search', null, [
                                        'brands[]' => $brand['id'],
                                    ])->getRelativeUrl(); ?>">
                                        <img src="" data-src="<?= url('image.show') . $brand['image']; ?>"
                                             alt="<?= $brand['name']; ?>" class="lazy">
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