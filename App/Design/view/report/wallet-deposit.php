<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'تراکنش‌های کیف پول']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span></span>
                <button type="button"
                        class="btn bg-success-700 ml-3" id="excelExport">
                    خروجی اکسل
                    <i class="icon-file-excel ml-2"></i>
                </button>
            </div>
        </div>

        <?php if (count($query_builder ?? [])): ?>
            <div class="card-body border-bottom">
                <div id="builder-basic-wallet-deposit" class="mb-3"></div>
                <div class="text-right">
                    <button id="btn-filter-wallet-deposit" class="btn btn-primary mr-2">
                        <i class="icon-filter3 mr-2" aria-hidden="true"></i>
                        فیلتر اطلاعات
                    </button>
                    <button id="btn-reset-wallet-deposit" class="btn btn-warning">
                        <i class="icon-trash mr-2" aria-hidden="true"></i>
                        پاک کردن
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"user"},{"data":"deposit_price"},{"data":"deposit_title"},{"data":"deposit_by"},{"data":"deposit_date"}]'
               data-ajax-url="<?= url('admin.report.wallet.deposit.dt')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>کاربر</th>
                <th>مبلغ تراکنش</th>
                <th>علت تراکنش</th>
                <th>تراکنش توسط</th>
                <th>تاریخ تراکنش</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>کاربر</th>
                <th>مبلغ تراکنش</th>
                <th>علت تراکنش</th>
                <th>تراکنش توسط</th>
                <th>تاریخ تراکنش</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <script>
        (function ($) {
            'use strict';

            window.report_variable_filters = JSON.parse('<?= json_encode($query_builder ?? []); ?>');
        })(jQuery);
    </script>
</div>
<!-- /content area -->
