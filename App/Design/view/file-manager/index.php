<!-- Content area -->
<div class="content">
    <!-- Fieldset legend -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مدیریت فایل ها']) ?>

        <div class="card-body">
            <div class="row">
                <?php if ($the_options['allow_upload']): ?>
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

                        <?php if ($the_options['allow_create_folder']): ?>
                            <div class="form-group col-lg-6 order-1">
                                <form action="?" method="post" id="mkdir">
                                    <label for="dirname" class="d-block">
                                        ساخت پوشه جدید:
                                    </label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="form-group-feedback form-group-feedback-left">
                                                <input id="dirname" class="form-control" type="text" name="name"
                                                       autocomplete="off" value=""
                                                       placeholder="نام لاتین پوشه را وارد کنید">
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
                <table id="table" class="table text-left">
                    <thead class="bg-green-600 border-radius">
                    <tr>
                        <th id="chks">
                            <label class="checkbox-switch mb-0">
                                <input type="checkbox" class="styled form-input-styled">
                            </label>
                        </th>
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

            <!-- Clickable menu -->
            <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right" data-fab-toggle="click">
                <li>
                    <a href="javascript:void(0);" class="fab-menu-btn btn btn-dark btn-float rounded-round btn-icon">
                        <i class="fab-icon-open icon-menu"></i>
                        <i class="fab-icon-close icon-cross2"></i>
                    </a>

                    <ul class="fab-menu-inner">
                        <li>
                            <div class="fab-label-light" data-fab-label="جابجایی موارد انتخاب شده"
                                 id="selItem" data-toggle="modal" data-target="#modal_full">
                                <a href="javascript:void(0);" class="btn btn-info rounded-round btn-icon btn-float">
                                    <i class="icon-folder"></i>
                                </a>
                                <span class="badge bg-success-400 selItemsCount"></span>
                            </div>
                        </li>
                        <li>
                            <div class="fab-label-light" data-fab-label="حذف موارد انتخاب شده" id="delItem">
                                <a href="javascript:void(0);" class="btn btn-danger rounded-round btn-icon btn-float">
                                    <i class="icon-trash"></i>
                                </a>
                                <span class="badge bg-success-400 selItemsCount"></span>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- /clickable menu -->

            <!-- Standard width modal -->
            <div id="modal_full" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">انتخاب فولدر</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div id="folders-body" class="modal-body">
                            <button id="tree-refresh" type="button" style="z-index: 1;"
                                    class="btn btn-sm bg-purple btn-icon rounded-round float-right">
                                <i class="icon-rotate-cw3"></i>
                            </button>
                            <div class="tree tree-default pt-3 pr-3">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);" class="folder"
                                           data-path="<?= get_path('upload-root'); ?>">
                                            <i class="folder-icon icon-folder"></i>
                                            Home
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">لغو</button>
                            <button id="mvdir" type="button" class="btn btn-primary" data-dismiss="modal">
                                جابجایی
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /standard width modal -->

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
                            <button type="button" class="btn btn-link" data-dismiss="modal">لغو</button>
                            <button id="renameFile" type="button" class="btn btn-primary" data-dismiss="modal">تغییر
                                نام
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /standard width modal -->
        </div>
    </div>
</div>
<!-- /content area -->

<?php load_partial('file-manager/efm-main', ['the_options' => $the_options,]); ?>
