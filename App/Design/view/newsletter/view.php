<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'اعضای خبرنامه']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به حذف اعضای خبرنامه کنید.</span>
                <button type="button" class="btn btn-success ml-3" data-toggle="modal"
                        data-target="#modal_form_add_newsletter">
                    افزودن موبایل
                    <i class="icon-mobile ml-2"></i>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"mobile"},{"data":"created_at"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.newsletter.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>شماره موبایل</th>
                <th>اضافه شدن در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>شماره موبایل</th>
                <th>اضافه شدن در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Add newsletter modal -->
    <div id="modal_form_add_newsletter" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">افزودن موبایل جدید</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="#" id="__form_add_newsletter">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    شماره موبایل:
                                </label>
                                <input type="text" placeholder="شماره تلفن ۱۱ رقمی" class="form-control"
                                       name="inp-add-newsletter-mobile">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">افزودن به خبرنامه</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /add newsletter modal -->
</div>
