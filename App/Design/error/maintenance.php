<!doctype html>
<html lang="<?= \translate()->getLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= \translate()->translate('maintenance.title'); ?></title>

    <link href="<?= asset_path('css/font.css'); ?>" rel="stylesheet">

    <style>
        body {
            display: flex;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -moz-flex;
            display: -webkit-flex;
            display: flex;
            -webkit-align-items: center;
            align-items: center;
            -webkit-justify-content: center;
            justify-content: center;
            text-align: center;
            margin: 0;
            height: 100vh;
            background-color: #817fd6;
        }

        h1 {
            font-size: 50px;
        }

        body {
            font: 20px Helvetica, sans-serif;
            color: #fff;
        }

        body.rtl {
            font-family: 'IRANSansWeb', Arial, sans-serif;
            direction: rtl;
        }

        article {
            display: block;
            text-align: left;
            width: 650px;
            margin: 0 auto;
        }

        body.rtl article {
            text-align: right;
        }

        a {
            color: #dc8100;
            text-decoration: none;
        }

        a:hover {
            color: #eee;
            text-decoration: none;
        }
    </style>
</head>
<body class="<?= \translate()->isRTL() ? 'rtl' : ''; ?>">
<article>
    <?= \translate()->translate('maintenance.body'); ?>
</article>
</body>
</html>