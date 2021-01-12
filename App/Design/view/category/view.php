<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'لیست دسته‌بندی‌ها']); ?>

        <div class="card-body">
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <span class="mb-2 mb-lg-0">با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده دسته‌بندی‌ها کنید.</span>

                <div class="ml-0 ml-lg-3 d-block d-lg-flex">
                    <a href="<?= url('admin.category.add'); ?>"
                       class="btn bg-primary mb-2 mb-sm-0 d-block d-sm-inline-block">
                        افزودن دسته جدید
                        <i class="icon-plus2 ml-2" aria-hidden="true"></i>
                    </a>
                    <a href="<?= url('admin.category.image.view'); ?>"
                       class="btn bg-success ml-0 ml-lg-2 d-block d-sm-inline-block">
                        افزودن تصویر به دسته
                        <i class="icon-file-picture2 ml-2" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight"
               data-columns='[{"data":"id"},{"data":"name"},{"data":"parent_name"},{"data":"priority"},{"data":"status"},{"data":"show_in_menu"},{"data":"operations"}]'
               data-ajax-url="<?= url('admin.category.dt.view')->getRelativeUrlTrimmed(); ?>">
            <thead>
            <tr>
                <th>#</th>
                <th>نام دسته‌بندی</th>
                <th>نام دسته والد</th>
                <th>اولویت</th>
                <th>وضعیت نمایش</th>
                <th>نمایش در منو</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>نام دسته‌بندی</th>
                <th>نام دسته والد</th>
                <th>اولویت</th>
                <th>وضعیت نمایش</th>
                <th>نمایش در منو</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
