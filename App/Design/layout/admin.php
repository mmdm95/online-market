<!DOCTYPE html>
<html lang="<?= \translate()->getLanguage() ?>" dir="<?= \translate()->isRTL() ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $title ?? ''; ?></title>

    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon"
          href="<?= url('image.show') . config()->get('settings.favicon.value'); ?>">

    <?= $css['top'] ?? ''; ?>
    <?= $js['top'] ?? ''; ?>
</head>
<body class="navbar-top">

<!-- Main navbar -->
<?php load_partial('admin/main-nav', [
        'unread_product_comments' => $unread_product_comments,
        'main_user_info' => $main_user_info,
]); ?>
<!-- /main navbar -->

<!-- Page content -->
<div class="page-content">
    <!-- Main sidebar -->
    <?php load_partial('admin/main-sidebar', ['main_user_info' => $main_user_info]); ?>
    <!-- /main sidebar -->

    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Page header -->
        <?php load_partial('admin/page-header', [
            'sub_title' => $sub_title ?? '',
            'breadcrumb' => $breadcrumb ?? [],
        ]); ?>
        <!-- /page header -->

        <?= $content; ?>

        <!-- Footer -->
        <?php load_partial('admin/footer'); ?>
        <!-- /footer -->

    </div>
    <!-- /main content -->
</div>
<!-- /page content -->

<?= $js['bottom'] ?? ''; ?>
</body>
</html>
