<div class="row m-0">
    <?php if (isset($the_options) && isset($the_options['allow_upload']) && $the_options['allow_upload']): ?>
        <div class="form-group col-12">
            <div id="file_drop_target">
                <div class="my-2">
                    <input name="recommendations" type="file" class="d-none" multiple
                           id="file-uploader">
                    <label for="file-uploader" class="ml-5"
                           style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                        فایلی انتخاب نشده است
                    </label>
                    <label for="file-uploader" class="action btn bg-pink text-white ml-2"
                           style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                        انتخاب فایل
                    </label>
                </div>
                <strong class="d-block my-3">یا</strong>
                <span class="d-block my-2">فایل را کشیده و اینجا رها کنید</span>
            </div>

            <div id="upload_progress"></div>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <div class="row flex-row-reverse">
            <div class="form-group col-lg-6 order-2">
                <label for="dirsearch" class="d-block">
                    جستجو در پوشه فعلی:
                </label>
                <div class="form-group">
                    <div class="form-group-feedback form-group-feedback-left">
                        <input id="dirsearch" class="form-control" type="text"
                               value="" placeholder="جستجو">
                        <div class="form-control-feedback form-control-feedback-lg">
                            <i class="icon-search4 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($the_options['allow_create_folder'] ?? false): ?>
                <div class="form-group col-lg-6 order-1">
                    <form action="?" method="post" id="mkdir">
                        <label for="dirname" class="d-block">
                            ساخت پوشه جدید:
                        </label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="form-group-feedback form-group-feedback-left">
                                    <input id="dirname" class="form-control" type="text" name="name"
                                           value="" placeholder="نام لاتین پوشه را وارد کنید">
                                    <div class="form-control-feedback form-control-feedback-lg">
                                        <i class="icon-folder text-muted"></i>
                                    </div>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-icon">
                                        ساخت پوشه
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12 order-3 mb-1">
        <div id="breadcrumb"></div>
    </div>
</div>

<div class="table-responsive">
    <?php
    $extraAttr = $extra_attribute ?? '';
    ?>
    <table id="table" class="table text-left" <?= $extraAttr; ?>>
        <thead class="bg-green-600 border-radius">
        <tr>
            <th class="sort_desc">نام</th>
            <th>اندازه</th>
            <th>تاریخ ایجاد</th>
            <th>دسترسی ها</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody id="list">
        </tbody>
    </table>
</div>

<?php if ($the_options['allow_rename'] ?? false): ?>
    <!-- Standard width modal -->
    <div id="modal_rename" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تغییر نام</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div id="rename-body" class="modal-body">
                    <div class="form-group-feedback form-group-feedback-left">
                        <input id="renameInput" class="form-control text-right"
                               style="direction: ltr;" type="text" value="" placeholder="">
                        <div class="form-control-feedback form-control-feedback-lg">
                            <i class="icon-pencil7 text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal" id="__efmModalDismiss">لغو</button>
                    <button id="renameFile" type="button" class="btn btn-primary" data-dismiss="modal">
                        تغییر نام
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /standard width modal -->
<?php endif; ?>

<script type="text/javascript">
    (function ($) {
        'use strict';

        $(function () {
            var
                variables,
                //-----
                modal,
                table,
                okBtn,
                //-----
                clickedItem = null,
                selectedItem;

            var
                clsImage = '__file_image',
                clsVideo = '__file_video',
                isImage = false,
                isVideo = false;

            var
                imageExts = ['png', 'jpg', 'jpeg', 'gif', 'svg'],
                videoExts = ['mp4', 'ogg', 'webm'];

            variables = window.MyGlobalVariables;

            modal = $('#modal_efm');
            table = $('#table');
            okBtn = $('#__pick_file_btn');

            $.efmInitPickerEvent = function () {
                $('.__file_picker_handler')
                    .off('click' + variables.namespace)
                    .on('click' + variables.namespace, function () {
                        if ($(this).hasClass(clsImage) || $(this).hasClass(clsVideo)) {
                            isImage = $(this).hasClass(clsImage);
                            isVideo = $(this).hasClass(clsVideo);
                            clickedItem = $(this);
                        }
                    });
            }

            /**
             * @param filename
             */
            function getExtension(filename) {
                return filename.split('.').pop();
            }

            /**
             * @param filename
             * @returns {boolean}
             */
            function validateImage(filename) {
                return $.inArray(getExtension(filename), imageExts) !== -1;
            }

            /**
             * @param filename
             * @returns {boolean}
             */
            function validateVideo(filename) {
                return $.inArray(getExtension(filename), videoExts) !== -1;
            }

            /**
             * @param e
             */
            function selectImageFromFileManager(e) {
                if (clickedItem && clickedItem.length && selectedItem) {
                    if ((isImage && validateImage(selectedItem)) || (isVideo && validateVideo(selectedItem))) {
                        clickedItem
                            .addClass('has-image')
                            .find('.img-placeholder-image')
                            .remove();
                        clickedItem.append($('<img class="img-placeholder-image" src="/images/' + selectedItem + '" alt="selected image">'));
                        clickedItem.find('input[type="hidden"]').val(selectedItem);
                        return true;
                    } else {
                        e.stopPropagation();
                    }
                } else {
                    e.stopPropagation();
                }
            }

            table.on('efm:list', function () {
                var first = table.find('tr td.first');
                first
                    .off('click' + variables.namespace)
                    .on('click' + variables.namespace, function () {
                        if (!$(this).closest('tr').hasClass('is_dir')) {
                            table.find('.selectable').removeClass('selectable');
                            $(this).closest('tr').addClass('selectable');
                            selectedItem = $(this).find('.name').attr('data-url');
                        }
                    });
                first
                    .off('dblclick' + variables.namespace)
                    .on('dblclick' + variables.namespace, function (e) {
                        if (selectImageFromFileManager(e)) {
                            if (modal.length) {
                                modal.modal('hide');
                            }
                        }
                    });
            });

            $.efmInitPickerEvent();

            okBtn
                .off('click')
                .on('click', function (e) {
                    selectImageFromFileManager(e);
                });

            $('#__efmModalDismiss').on('click', function () {
                if (modal.length) {
                    modal.modal('hide');
                }
            });
        });
    })(jQuery);
</script>