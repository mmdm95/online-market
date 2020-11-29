<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'وضعیت سفارشات']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
            <div class="col-md-2 float-right">
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal_form_add_badges">
                    افزودن وضعیت
                </button>
            </div>
            <!-- Vertical form modal -->
            <div id="modal_form_add_badges" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">افزودن وضعیت</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form action="#">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>عنوان وضعیت</label>
                                            <input type="text" placeholder="وارد کنید" class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>انتخاب رنگ:</label>
                                            </br>
                                            <div class="d-inline-block">
                                                <input type="text" class="form-control colorpicker-show-input"
                                                       data-preferred-format="HSL" value="#00fff5" data-fouc>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>انتخاب آیکون نمایشی:</label>
                                            <select data-placeholder="لیست آیکون‌ها"
                                                    class="form-control form-control-select2" data-fouc>
                                                <option></option>
                                                <option value="Cambodia">
                                                    <i class="icon icon-basket"></i>
                                                </option>
                                            </select>
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
                <th>عنوان وضعیت</th>
                <th>رنگ</th>
                <th>آیکون نمایشی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>در حال آماده سازی</td>
                <td>
                    <span class="p-2 d-inline-block rounded shadow-3 border-grey-300 border-1"
                          style="background-color: #dd4a68;"></span>
                </td>
                <td>
                    <i class="icon icon-basket"></i>
                </td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#modal_form_edit_badges" data-toggle="modal" class="dropdown-item">
                                    <i class="icon-pencil"></i>ویرایش</a>
                                <a href="#" class="dropdown-item"><i class="icon-trash"></i>حذف</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Vertical form modal -->
        <div id="modal_form_edit_badges" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">افزودن وضعیت</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>عنوان وضعیت</label>
                                        <input type="text" placeholder="وارد کنید" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>انتخاب رنگ:</label>
                                        </br>
                                        <div class="d-inline-block">
                                            <input type="text" class="form-control colorpicker-show-input"
                                                   data-preferred-format="HSL" value="#00fff5" data-fouc>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>انتخاب آیکون نمایشی:</label>
                                        <select data-placeholder="لیست آیکون‌ها"
                                                class="form-control form-control-select2" data-fouc>
                                            <option></option>
                                            <option value="Cambodia">
                                                <i class="icon icon-basket"></i>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">بستن</button>
                            <button type="submit" class="btn bg-primary">افزودن</button>
                        </div>
                    </form>                </div>
            </div>
        </div>
        <!-- /vertical form modal -->
    </div>
</div>
