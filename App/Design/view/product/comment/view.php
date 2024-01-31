<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'نظرات محصول']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف و مشاهده نظرات کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"username"},{"data":"status"},{"data":"accept_status"},{"data":"sent_date"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.comment.dt.view')->getRelativeUrl() . $product_id; ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>توسط</th>
                <th>وضعیت</th>
                <th>وضعیت تایید</th>
                <th>ارسال شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>توسط</th>
                <th>وضعیت</th>
                <th>وضعیت تایید</th>
                <th>ارسال شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
