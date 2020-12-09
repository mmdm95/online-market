<!DOCTYPE html>
<html lang="<?= \translate()->getLanguage() ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title><?= $title ?? ''; ?></title>

    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon"
          href="<?= url('image.show') . \config()->get('settings.favicon.value'); ?>">

    <?= $css['top'] ?? ''; ?>
    <?= $js['top'] ?? ''; ?>
</head>
<body dir="<?= \translate()->isRTL() ? 'rtl' : 'ltr'; ?>">

<!-- LOADER -->
<div class="preloader">
    <div class="lds-ellipsis">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<!-- END LOADER -->

<!-- START HEADER -->
<?php load_partial('main/menu', [
    'cart_section' => $cart_section ?? '',
]); ?>
<!-- END HEADER -->

<!-- START SECTION BREADCRUMB -->
<?php load_partial('main/section-breadcrumb', [
    'sub_title' => $sub_title ?? '',
    'breadcrumb' => $breadcrumb ?? [],
]); ?>
<!-- END SECTION BREADCRUMB -->

<?= $content; ?>

<!-- START FOOTER -->
<?php load_partial('main/footer'); ?>
<!-- END FOOTER -->

<a href="#" class="scrollup" style="display: none;"><i class="ion-ios-arrow-up"></i></a>

<?= $js['bottom'] ?? ''; ?>
</body>
</html>
