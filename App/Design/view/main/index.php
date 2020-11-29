<!-- Home Popup Section -->
<?php load_partial('main/popup-newsletter'); ?>
<!-- End Screen Load Popup Section -->

<!-- START HEADER -->
<?php load_partial('main/menu-main', [
    'menu' => $menu ?? [],
    'menu_images' => $menu_images ?? [],
    'categories' => $categories ?? [],
    'cart_section' => $cart_section ?? '',
]); ?>
<!-- END HEADER -->

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
    <?php load_partial('main/slider-specials'); ?>
    <!-- END SECTION SHOP -->

    <!-- START SECTION BANNER -->
    <?php load_partial('main/section-banner'); ?>
    <!-- END SECTION BANNER -->

    <!-- START SECTION BLOG -->
    <?php load_partial('main/section-blog'); ?>
    <!-- END SECTION BLOG -->

    <!-- START SECTION CLIENT LOGO -->
    <?php load_partial('main/slider-brands'); ?>
    <!-- END SECTION CLIENT LOGO -->

    <!-- START SECTION SUBSCRIBE NEWSLETTER -->
    <?php load_partial('main/newsletter'); ?>
    <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->

<!-- START FOOTER -->
<?php load_partial('main/footer-main'); ?>
<!-- END FOOTER -->

<a href="#" class="scrollup" style="display: none;"><i class="ion-ios-arrow-up"></i></a>
