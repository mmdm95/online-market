<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'سؤالات متداول']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده خرید‌های کاربر کنید.
            <div class="col-md-2 float-right">
                <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#modal_form_add_faq">
                    افزودن سؤال جدید
                    <i class="icon-question7 ml-2"></i>
                </button>
            </div>
            <!-- Vertical form modal -->
            <div id="modal_form_add_faq" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">افزودن سؤال جدید</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form action="#">
                            <div class="modal-body">
                                <div class="form-group">
                                        <div class="form-group">
                                            <label>سؤال</label>
                                            <input type="text"
                                                   placeholder="متن سؤال را وارد کنید."
                                                   class="form-control">
                                        </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>پاسخ</label>
                                        <input type="text"
                                               placeholder="پاسخ سؤال را وارد کنید."
                                               class="form-control">
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
                <th>صورت سؤال</th>
                <th>پاسخ سؤال</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>۱</td>
                <td>برای خرید چه اقدامی باید انجام داد؟</td>
                <td>شما برای پاسخ به این سؤال باید در انتهای خرید فحش بدهید.</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#modal_form_edit_faq" data-toggle="modal" class="dropdown-item">
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
        <div id="modal_form_edit_faq" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ویرایش واحد جدید</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="#">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>سؤال</label>
                                    <input type="text"
                                           placeholder="متن سؤال را وارد کنید."
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label>پاسخ</label>
                                    <input type="text"
                                           placeholder="پاسخ سؤال را وارد کنید."
                                           class="form-control">
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
