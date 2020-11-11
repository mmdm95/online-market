<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <?php load_partial('admin/main-sidebar'); ?>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4><i class="icon-arrow-right6 mr-2"></i> <span class="font-weight-semibold">
                            مدیریت رنگ‌ها</span>

                    </h4>
                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="<?= url('admin.index'); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>
                            خانه</a>
                        <a href="<?= url('admin.user.view'); ?>" class="breadcrumb-item">کاربران</a>
                        <span class="breadcrumb-item active">لیست کاربران</span>
                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>
            </div>
        </div>
        <!-- /page header -->


        <!-- Content area -->
        <div class="content">

            <!-- Highlighting rows and columns -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">
                        لیست رنگ‌ها
                    </h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    با استفاده از ستون عملیات می‌توانید اقدام به حذف رنگ کنید.
                </div>

                <table class="table table-bordered table-hover datatable-highlight">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام رنگ</th>
                        <th>کد</th>
                        <th>نمایش</th>
                        <th class="text-center">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>۱</td>
                        <td>طلایی</td>
                        <td>#fff000</td>
                        <td>
                            <span class="icon icon-stop2 font-size-lg" style="color: #fff000"></span>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="#" class="dropdown-item"><i class="icon-trash"></i>حذف</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /highlighting rows and columns -->

        </div>
        <!-- /content area -->


        <!-- Footer -->
        <?php load_partial('admin/footer'); ?>
        <!-- /footer -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->

