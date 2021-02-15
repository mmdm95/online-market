<!-- Content area -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <?php load_partial('admin/card-header', ['header_title' => 'کیف پول کاربر']); ?>

                <table class="table table-bordered table-hover datatable-highlight"
                       data-columns='[{"data":"id"},{"data":"price"},{"data":"description"},{"data":"deposit_date"},{"data":"deposit_by"}]'
                       data-ajax-url="<?= url('admin.wallet.detail.dt', ['username' => $username])->getRelativeUrlTrimmed(); ?>">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>مبلغ</th>
                        <th>توضیحات</th>
                        <th>تاریخ تراکنش</th>
                        <th>تراکنش توسط</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>مبلغ</th>
                        <th>توضیحات</th>
                        <th>تاریخ تراکنش</th>
                        <th>تراکنش توسط</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /content area -->