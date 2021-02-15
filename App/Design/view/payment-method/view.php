<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'روش‌های پرداخت']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده روش‌های پرداخت کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"image"},{"data":"title"},{"data":"type"},{"data":"status"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.pay_method.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>عنوان</th>
                <th>نوع روش پرداخت</th>
                <th>وضعیت نمایش</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>عنوان</th>
                <th>نوع روش پرداخت</th>
                <th>وضعیت نمایش</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
