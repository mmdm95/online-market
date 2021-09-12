<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده کاربران']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>با استفاده از ستون عملیات می‌توانید اقدام به مشاهده کاربران کنید.</span>
                <button type="button"
                        class="btn bg-success-700 ml-3" id="excelExport">
                    خروجی اکسل
                    <i class="icon-file-excel ml-2"></i>
                </button>
            </div>
        </div>

        <?php if (count($query_builder ?? [])): ?>
            <div class="card-body border-bottom">
                <div id="builder-basic-user" class="mb-3"></div>
                <div class="text-right">
                    <button id="btn-filter-user" class="btn btn-primary mr-2">
                        <i class="icon-filter3 mr-2" aria-hidden="true"></i>
                        فیلتر اطلاعات
                    </button>
                    <button id="btn-reset-user" class="btn btn-warning">
                        <i class="icon-trash mr-2" aria-hidden="true"></i>
                        پاک کردن
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"}, {"data":"first_name"}, {"data":"last_name"}, {"data":"roles"}, {"data":"mobile"}, {"data":"created_at"}, {"data":"status"}, {"data":"operations"}]'
               data-ajax-url="<?= url('admin.report.users.dt')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>نقش</th>
                <th>موبایل</th>
                <th>تاریخ عضویت</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <th>نقش</th>
                <th>موبایل</th>
                <th>تاریخ عضویت</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
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
