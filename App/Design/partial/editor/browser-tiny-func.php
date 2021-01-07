<script type="application/javascript">
    (function ($) {
        'use strict';

        $(function () {
            function myFileBrowser(callback, value, meta) {
                var cmsURL = "<?= url('ajax.editor.browser')->getRelativeUrlTrimmed(); ?>";    // script URL - use an absolute path!
                cmsURL = cmsURL + "?type=" + meta.filetype;

                tinymce.activeEditor.windowManager.open({
                    title: 'File Manager',
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
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor",
                image_advtab: true,
                file_picker_callback: function (callback, value, meta) {
                    myFileBrowser(callback, value, meta);
                }
            });
        });
    })(jQuery);
</script>