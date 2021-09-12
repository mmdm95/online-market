<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست برندها']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span class="mb-2 mb-lg-0">با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده برندها کنید.</span>

                <div class="ml-0 ml-lg-3 d-block d-lg-flex">
                    <a href="<?= url('admin.brand.add'); ?>"
                       class="btn bg-primary mb-2 mb-sm-0 d-block d-sm-inline-block">
                        افزودن برند جدید
                        <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"image"},{"data":"name"},{"data":"show_in_sliders"},{"data":"status"},{"data":"created_at"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.brand.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>نام</th>
                <th>نمایش در اسلایدر برندها</th>
                <th>وضعیت انتشار</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>نام</th>
                <th>نمایش در اسلایدر برندها</th>
                <th>وضعیت انتشار</th>
                <th>اضافه شده در تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
