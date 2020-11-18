<!doctype html>
<html lang="<?= \translate()->getLanguage() ?>" dir="<?= \translate()->isRTL() ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? ''; ?></title>

    <?= $css['top'] ?? ''; ?>
    <?= $js['top'] ?? ''; ?>
</head>
<body>

<!-- Main navbar -->
<?php load_partial('admin/main-nav'); ?>
<!-- /main navbar -->

<!-- Page content -->
<div class="page-content">
    <!-- Main sidebar -->
    <?php load_partial('admin/main-sidebar'); ?>
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
</body>
<?= $js['bottom'] ?? ''; ?>
</html>
