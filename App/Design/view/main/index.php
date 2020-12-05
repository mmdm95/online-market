<!-- Home Popup Section -->
<?php load_partial('main/message/popup-newsletter'); ?>
<!-- End Screen Load Popup Section -->

<!-- START SECTION BANNER -->
<?php load_partial('main/slider-main', [
    'main_slider' => $main_slider ?? [],
]); ?>
<!-- END SECTION BANNER -->

<!-- END MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <?php load_partial('main/slider-tabbed', [
        'tabbed_slider' => $tabbed_slider ?? [],
    ]); ?>
    <!-- END SECTION SHOP -->

    <!-- START SECTION INSTAGRAM IMAGE -->
    <?php load_partial('main/instagram-images', [
        'instagram_images' => $instagram_images ?? [],
    ]); ?>
    <!-- END SECTION INSTAGRAM IMAGE -->

    <!-- START SECTION SHOP -->
    <?php load_partial('main/slider-specials', [
            'special_slider' => $special_slider ?? [],
    ]); ?>
    <!-- END SECTION SHOP -->

    <!-- START SECTION BANNER -->
    <?php load_partial('main/section-banner', [
            'three_images' => $three_images ?? [],
    ]); ?>
    <!-- END SECTION BANNER -->

    <!-- START SECTION BLOG -->
    <?php load_partial('main/section-blog', [
            'blog' => $blog ?? [],
    ]); ?>
    <!-- END SECTION BLOG -->

    <!-- START SECTION CLIENT LOGO -->
    <?php load_partial('main/slider-brands', [
            'brands' => $brands ?? [],
    ]); ?>
    <!-- END SECTION CLIENT LOGO -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter-main'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->
