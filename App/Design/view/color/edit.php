<!-- Content area -->
<div class="content">
    <!-- 2 columns form -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش رنگ']); ?>

        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <div class="row">
                                <div class="form-group col-md-4">

                                    <label>نام رنگ:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>انتخاب رنگ:</label>
                                    <br>
                                    <div class="d-inline-block">
                                        <input type="text" class="form-control colorpicker-show-input">
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">ذخیره اطلاعات <i
                                class="icon-floppy-disks ml-2"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- /2 columns form -->
</div>
<!-- /content area -->