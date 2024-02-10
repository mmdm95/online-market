<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'روش‌های ارسال']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span class="mb-2 mb-lg-0">با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده روش‌های ارسال کنید.</span>

                <div class="ml-0 ml-lg-3 d-block d-lg-flex">
                    <a href="<?= url('admin.send_method.add'); ?>"
                       class="btn bg-primary mb-2 mb-sm-0 d-block d-sm-inline-block">
                        افزودن روش ارسال جدید
                        <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"image"},{"data":"title"},{"data":"price"},{"data":"determine_location"},{"data":"for_shop_location"},{"data":"status"},{"data":"priority"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.send_method.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>عنوان</th>
                <th>هزینه ارسال</th>
                <th>در نظرگیری مکان در هزینه ارسال</th>
                <th>مورد استفاده فقط برای محل فروشگاه</th>
                <th>وضعیت نمایش</th>
                <th>اولویت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>تصویر</th>
                <th>عنوان</th>
                <th>هزینه ارسال</th>
                <th>در نظرگیری مکان در هزینه ارسال</th>
                <th>مورد استفاده فقط برای محل فروشگاه</th>
                <th>وضعیت نمایش</th>
                <th>اولویت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
