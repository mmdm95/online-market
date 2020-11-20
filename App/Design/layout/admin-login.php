<!DOCTYPE html>
<html lang="<?= \translate()->getLanguage() ?>" dir="<?= \translate()->isRTL() ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? ''; ?></title>

    <?= $css['top'] ?? ''; ?>
    <?= $js['top'] ?? ''; ?>
</head>
<body>
<?= $content; ?>

<?= $js['bottom'] ?? ''; ?>
</body>
</html>
