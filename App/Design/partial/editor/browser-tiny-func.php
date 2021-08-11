<script type="application/javascript">
    (function ($) {
        'use strict';

        $(function () {
            function myFileBrowser(callback, value, meta) {
                var cmsURL = "<?= url('ajax.editor.browser')->getRelativeUrlTrimmed(); ?>";    // script URL - use an absolute path!
                cmsURL = cmsURL + "?type=" + meta.filetype;

                tinymce.activeEditor.windowManager.open({
                    title: 'مدیریت فایل‌ها',
                    url: cmsURL,
                    width: 600,
                    height: 600
                }, {
                    onInsert: function (url) {
                        callback(url);
                    }
                });
            }

            tinyMCE.init({
                // for custom css of the actual page
                content_css: '<?= asset_path('css/for-editor.css'); ?>',
                selector: '.cntEditor',
                height: 500,
                theme: 'modern',
                plugins: [
                    "textcolor",
                    'emoticons template paste textpattern imagetools',
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality'
                ],
                // toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor",
                image_advtab: true,
                file_picker_callback: function (callback, value, meta) {
                    myFileBrowser(callback, value, meta);
                },
                setup: function (ed) {
                    if ($('#' + ed.id).attr('data-editor-readonly')) {
                        ed.settings.readonly = true;
                    }
                    if ($('#' + ed.id).attr('data-editor-menubar')) {
                        ed.settings.menubar = false;
                    }
                    if ($('#' + ed.id).attr('data-editor-statusbar')) {
                        ed.settings.statusbar = false;
                    }
                    if ($('#' + ed.id).attr('data-editor-toolbar')) {
                        ed.settings.toolbar = false;
                    }
                },
            });
        });
    })(jQuery);
</script>