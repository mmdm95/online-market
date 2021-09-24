<!DOCTYPE html>
<html lang="<?= \translate()->getLanguage() ?>" dir="<?= \translate()->isRTL() ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="utf-8">

    <title><?= $title ?? ''; ?></title>

    <?= $css['top'] ?? ''; ?>
</head>
<body style='font-family: IRS, Arial, sans-serif;'>
<?= $content; ?>
</body>
</html>
