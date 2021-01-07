<!DOCTYPE html>
<html lang="<?= \translate()->getLanguage() ?>" dir="<?= \translate()->isRTL() ? 'rtl' : 'ltr'; ?>">
<head>
    <!-- Required meta tags -->
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

    <style>
        #page {
            height: 598px;
            overflow: auto;
        }

        [name="my_form"] {
            position: fixed;
            left: 0;
            bottom: 0;
            margin: 2rem 2rem;
        }
    </style>
</head>
<body>
<div id="page" class="lazyContainer">
    <?php load_partial('file-manager/efm-view', [
        'the_options' => $the_options ?? [],
        'extra_attribute' => 'data-table-custom-identifier="the_table"',
    ]); ?>

    <form name="my_form" class="text-right">
        <input type="hidden" name="my_field"/>
        <input id="__pick_file_btn" type="button" class="btn bg-slate" value="انتخاب فایل">
    </form>
</div>

<script language="javascript" type="text/javascript">
    (function ($) {
        'use strict';

        $(function () {
            // off method MUST be present to remove previous attached click event
            // and attach new event to ok btn
            $('#__pick_file_btn').off('click').on('click', function () {
                var selectedRow = $('[data-table-custom-identifier="the_table"]').find('.selectable').first();

                if (selectedRow && selectedRow.length && !selectedRow.hasClass('is_dir')) {
                    var image_url = selectedRow.find('.first a').attr("data-url");

                    top.tinymce.activeEditor.windowManager.getParams().onInsert(image_url);
                    top.tinymce.activeEditor.windowManager.close();
                }
            });
        });
    })(jQuery);
</script>

<?php load_partial('file-manager/efm', [
    'the_options' => $the_options ?? [],
]); ?>

<?= $js['bottom'] ?? ''; ?>
</body>
</html>