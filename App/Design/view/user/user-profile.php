<!-- Content area -->
<div class="content">

    <!-- Fieldset legend -->
    <!-- 2 columns form -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'مشاهده کاربر']); ?>


        <div class="card-body">
            <form action="#">
                <div class="row">
                    <div class="col-md-6">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-user mr-2"></i>
                                اطلاعات کاربر
                            </legend>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <i class="icon icon-info3 text-info" data-toggle="popover"
                                       title="موبایل به عنوان نام کاربری خواهد بود."></i>
                                    <label>موبایل:</label>
                                    <input type="text" class="form-control" placeholder="11 رقمی">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>نقش کاربر:</label>
                                        <select data-placeholder="نقش کاربر در سایت"
                                                class="form-control form-control-select2" data-fouc>
                                            <option></option>
                                            <option value="Cambodia">عادی</option>
                                            <option value="Cameroon">مدیر</option>
                                            <option value="Canada">مدیر اصلی</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <i class="icon icon-info3 text-info" data-toggle="popover"
                                       title="حداقل ۸ کاراکتر و شامل یک حرف"></i>
                                    <label>رمز عبور:</label>
                                    <input type="password" class="form-control"
                                           placeholder="حداقل ۸ کاراکتر و شامل یک حرف">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>تکرار رمز عبور:</label>
                                    <input type="password" class="form-control" placeholder="تکرار رمز عبور">
                                </div>

                                <div class="form-check form-check-switch form-check-switch-left col-md-6">
                                    <label class="form-check-label d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input form-check-input-switch"
                                               data-on-text="On" data-off-text="Off" data-on-color="default"
                                               data-off-color="danger" checked>
                                        Default color
                                    </label>
                                </div>

                            </div>

                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset>
                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>
                                اطلاعات شخصی
                            </legend>

                            <div class="row">
                                <div class="card-body col-md-12">
                                    <div class="d-flex align-items-start flex-nowrap">
                                        <div>
                                            <div class="font-weight-semibold mr-2">He it otherwise</div>
                                            <span class="font-size-sm text-muted">Size: 329kb</span>
                                        </div>

                                        <div class="list-icons list-icons-extended ml-auto">
                                            <a href="#" class="list-icons-item"><i
                                                        class="icon-download top-0"></i></a>
                                            <a href="#" class="list-icons-item"><i
                                                        class="icon-bin top-0"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>نام:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>نام خانوادگی:</label>
                                    <input type="text" class="form-control" placeholder="وارد کنید">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>پست الکترونیکی:</label>
                                        <input type="text" placeholder="example@mail.com" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>موبایل:</label>
                                        <input type="text" placeholder="" class="form-control"
                                               disabled="disabled">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /2 columns form -->
    <!-- Highlighting rows and columns -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">
                آدرس‌های کاربر
            </h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
            <div class="col-md-2 float-right">
                <button type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#modal_form_address_add">
                    افزودن آدرس
                    <i class="icon-truck ml-2"></i></button>
            </div>
            <!-- Vertical form modal -->
            <div id="modal_form_address_add" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">افزودن آدرس جدید</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form action="#">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>نام گیرنده</label>
                                            <input type="text" placeholder="وارد کنید" class="form-control">
                                        </div>

                                        <div class="col-sm-6">
                                            <label>نام خانوادگی گیرنده</label>
                                            <input type="text" placeholder="وارد کنید" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>موبایل گیرنده:</label>
                                            <input type="text" placeholder="09139518055"
                                                   class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>تلفن ثابت گیرنده:</label>
                                            <input type="text" placeholder="eugene@kopyov.com"
                                                   class="form-control">
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>استان:</label>
                                                <select data-placeholder="استان را انتخاب کنید."
                                                        class="form-control form-control-select2" data-fouc>
                                                    <option></option>
                                                    <option value="Cambodia">یزد</option>
                                                    <option value="Cameroon">اصفهان</option>
                                                    <option value="Canada">تهران</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>شهر:</label>
                                                <select data-placeholder="شهر را انتخاب کنید."
                                                        class="form-control form-control-select2" data-fouc>
                                                    <option></option>
                                                    <option value="Cambodia">یزد</option>
                                                    <option value="Cameroon">اصفهان</option>
                                                    <option value="Canada">تهران</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>کد پستی:</label>
                                            <input type="text" placeholder="8916754595" class="form-control">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>آدرس پستی</label>
                                            <input type="text"
                                                   placeholder="آدرس کامل پستی را در اینجا وارد کنید."
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                                <button type="submit" class="btn bg-primary">افزودن</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /vertical form modal -->
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
            <tr>
                <th>#</th>
                <th>استان / شهر</th>
                <th>نام و نام خانوادگی گیرنده</th>
                <th>کدپستی</th>
                <th>موبایل</th>
                <th>آدرس کامل</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>محمدمهدی</td>
                <td><a href="#">دهقان منشادی</a></td>
                <td>۸۹۱۶۷۵۴۹۵۹</td>
                <td>۰۹۱۷۹۵۱۶۲۷۱</td>
                <td>
                    خیابان کاشانی ک لاله پلاک ۳۵
                </td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#modal_form_address_edit" data-toggle="modal" class="dropdown-item"><i class="icon-pencil"></i>ویرایش</a>
                                <a href="#" class="dropdown-item"><i class="icon-trash"></i>حذف</a>
                                <a href="#" class="dropdown-item"><i class="icon-cart"></i>خریدها</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Vertical form modal -->
        <div id="modal_form_address_edit" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ویرایش آدرس</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>نام گیرنده</label>
                                        <input type="text" placeholder="وارد کنید" class="form-control">
                                    </div>

                                    <div class="col-sm-6">
                                        <label>نام خانوادگی گیرنده</label>
                                        <input type="text" placeholder="وارد کنید" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>موبایل گیرنده:</label>
                                        <input type="text" placeholder="09139518055"
                                               class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>تلفن ثابت گیرنده:</label>
                                        <input type="text" placeholder="eugene@kopyov.com"
                                               class="form-control">
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>استان:</label>
                                            <select data-placeholder="استان را انتخاب کنید."
                                                    class="form-control form-control-select2" data-fouc>
                                                <option></option>
                                                <option value="Cambodia">یزد</option>
                                                <option value="Cameroon">اصفهان</option>
                                                <option value="Canada">تهران</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>شهر:</label>
                                            <select data-placeholder="شهر را انتخاب کنید."
                                                    class="form-control form-control-select2" data-fouc>
                                                <option></option>
                                                <option value="Cambodia">یزد</option>
                                                <option value="Cameroon">اصفهان</option>
                                                <option value="Canada">تهران</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>کد پستی:</label>
                                        <input type="text" placeholder="8916754595" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>آدرس پستی</label>
                                        <input type="text"
                                               placeholder="آدرس کامل پستی را در اینجا وارد کنید."
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn bg-primary">افزودن</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /vertical form modal -->
    </div>
    <!-- /highlighting rows and columns --
    <!-- Highlighting rows and columns -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">
                سفارشات کاربر
            </h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
            <tr>
                <th>#</th>
                <th>استان / شهر</th>
                <th>نام و نام خانوادگی گیرنده</th>
                <th>کدپستی</th>
                <th>موبایل</th>
                <th>آدرس کامل</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>محمدمهدی</td>
                <td><a href="#">دهقان منشادی</a></td>
                <td>۸۹۱۶۷۵۴۹۵۹</td>
                <td>۰۹۱۷۹۵۱۶۲۷۱</td>
                <td>
                    خیابان کاشانی ک لاله پلاک ۳۵
                </td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item"><i class="icon-pencil"></i>ویرایش</a>
                                <a href="#" class="dropdown-item"><i class="icon-trash"></i>حذف</a>
                                <a href="#" class="dropdown-item"><i class="icon-cart"></i>خریدها</a>
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