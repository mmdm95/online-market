<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'شکایات']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف و مشاهده شکایات کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"name"},{"data":"title"},{"data":"sent_date"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.complaints.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام فرستنده</th>
                <th>موضوع</th>
                <th>تاریخ ارسال</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام فرستنده</th>
                <th>موضوع</th>
                <th>تاریخ ارسال</th>
                <th>وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
