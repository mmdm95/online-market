<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">

        <?php load_partial('admin/card-header', ['header_title' => 'پیام‌های ارسال شده']); ?>


        <table class="table badge- table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"name"},{"data":"number"},{"data":"numbers"},{"data":"status"},{"data":"body"},{"data":"type"},{"data":"code"},{"data":"sender"},{"data":"res_msg"},{"data":"sent_at"}]'
               data-ajax-url="<?= url('admin.sms.dt.logs')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام پنل</th>
                <th>شماره پنل</th>
                <th>شماره‌های ارسال شده</th>
                <th>وضعیت ارسال</th>
                <th>پیام ارسال شده</th>
                <th>نوع پیام</th>
                <th>کد نتیجه پنل</th>
                <th>ارسال شده توسط</th>
                <th>نتیجه ارسال</th>
                <th>در تاریخ</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام پنل</th>
                <th>شماره پنل</th>
                <th>شماره‌های ارسال شده</th>
                <th>وضعیت ارسال</th>
                <th>پیام ارسال شده</th>
                <th>نوع پیام</th>
                <th>کد نتیجه پنل</th>
                <th>ارسال شده توسط</th>
                <th>نتیجه ارسال</th>
                <th>در تاریخ</th>
            </tr>
            </tfoot>
        </table>

    </div>
</div>
<!-- /content area -->
