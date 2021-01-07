<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست رنگ‌ها']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش رنگ‌ها کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"name"},{"data":"hex"},{"data":"show"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.color.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام رنگ</th>
                <th>کد</th>
                <th>نمایش</th>
                <th>وضعیت نمایش</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام رنگ</th>
                <th>کد</th>
                <th>نمایش</th>
                <th>وضعیت نمایش</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
