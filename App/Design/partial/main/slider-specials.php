<?php if (count($special_slider ?? [])): ?>
    <div class="section pt-0 pb-0">
        <div class="custom-container container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading_tab_header">
                        <div class="heading_s2">
                            <h4>تخفیف‌های جشنواره</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="product_slider carousel_slider owl-carousel owl-theme nav_style3" data-loop="true"
                         data-dots="false" data-nav="true" data-margin="30"
                         data-responsive='{"0":{"items": "1"}, "650":{"items": "2"}, "1199":{"items": "2"}}'>
                        <?php foreach ($special_slider as $item): ?>
                            <?php load_partial('main/product-card', ['item' => $item]); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>