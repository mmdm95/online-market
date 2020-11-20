<!DOCTYPE html>
<html lang="<?= \translate()->getLanguage() ?>" dir="<?= \translate()->isRTL() ? 'rtl' : 'ltr'; ?>">
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
    <link rel="shortcut icon" type="image/x-icon" href="">

    <?= $css['top'] ?? ''; ?>
    <?= $js['top'] ?? ''; ?>
</head>
<body dir="rtl">

<!-- LOADER -->
<div class="preloader">
    <div class="lds-ellipsis">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<!-- END LOADER -->

<?= $content; ?>

<?= $js['bottom'] ?? ''; ?>
</body>
</html>
