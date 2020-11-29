<!-- Content area -->
<div class="content">
    <div class="row flex-row-reverse">
        <div class="col-lg-8 order-1">
            <!-- Highlighting rows and columns -->
            <div class="card">
                <?php load_partial('admin/card-header', ['header_title' => 'کیف پول کاربر']); ?>
                <div class="card-body">
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
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th>توضیحات</th>
                        <th>تاریخ تراکنش</th>
                        <th>کاربر</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>۱</td>
                        <td>
                            <span class="text-danger">100,000</span>
                            <span class="text-green">100,000</span>
                        </td>
                        <td>برداشت / واریز</td>
                        <td>شارژ کیف پول به خاطر دادن</td>
                        <td>۸ آذر ۱۳۹۹</td>
                        <td>
محمدمهدی دهقان منشادی
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /highlighting rows and columns -->
        </div>
        <div class="col-lg-4 order-0">
            <!-- 2 columns form -->
            <div class="card">
                <?php load_partial('admin/card-header', ['header_title' => 'شارژ کیف پول']); ?>

                <div class="card-body">
                    <form action="#">
                        <div class="row">
                            <div class="col-lg-12">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>مبلغ:</label>
                                            <input type="text" class="form-control" placeholder="به تومان">
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>نقش کاربر:</label>
                                                <select data-placeholder="توضیح شارژ"
                                                        class="form-control form-control-select2" data-fouc>
                                                    <option></option>
                                                    <option value="Cambodia">عادی</option>
                                                    <option value="Cameroon">مدیر</option>
                                                    <option value="Canada">مدیر اصلی</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text">
                                            <button type="submit" class="btn btn-primary">
                                                ذخیره اطلاعات
                                                <i class="icon-floppy-disks ml-2"></i>
                                            </button>
                                        </div>
                                    </div>

                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /2 columns form -->
        </div>
    </div>
</div>
<!-- /content area -->