<div class="address-elements-container">
    <?php load_partial('main/user/addresses', ['addresses' => $addresses ?? []]); ?>
</div>

<button type="button" class="btn btn-info btn-block"
        data-toggle="modal" data-target="#__user_addr_add_modal">
    افزودن آدرس جدید
</button>

<!-- START ADD ADDRESS MODAL -->
<div class="modal fade subscribe_popup" id="__user_addr_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
                </button>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="popup_content text-left">
                            <div class="popup-text">
                                <div class="heading_s3 text-left">
                                    <h4>افزودن آدرس</h4>
                                </div>
                            </div>
                            <form action="#" id="__form_add_address">
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        نام و نام خانوادگی:
                                    </label>
                                    <input name="inp-add-address-full-name" required type="text"
                                           class="form-control" placeholder="حروف فارسی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شماره تماس:
                                    </label>
                                    <input name="inp-add-address-mobile" required type="text"
                                           class="form-control" placeholder="برای مثال: 0912xxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-add-address-province"
                                            class="selectric_dropdown city-loader-select"
                                            data-city-select-target="#addAddressCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected"
                                                disabled="disabled">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شهر:
                                    </label>
                                    <select name="inp-add-address-city"
                                            class="selectric_dropdown"
                                            id="addAddressCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected"
                                                disabled="disabled">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        کد پستی:
                                    </label>
                                    <input name="inp-add-address-postal-code" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            class="form-control form-control-min-height"
                                            name="inp-add-address-address"
                                            id=""
                                            cols="30"
                                            rows="10"
                                            placeholder="وارد کنید"
                                    ></textarea>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary btn-block text-uppercase"
                                            title="اشتراک" type="submit">
                                        افزودن آدرس
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END ADD ADDRESS MODAL -->

<!-- START EDIT ADDRESS MODAL -->
<div class="modal fade subscribe_popup" id="__user_addr_edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
                </button>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="popup_content text-left">
                            <div class="popup-text">
                                <div class="heading_s3 text-left">
                                    <h4>ویرایش آدرس</h4>
                                </div>
                            </div>
                            <form action="#" id="__form_edit_address">
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        نام و نام خانوادگی:
                                    </label>
                                    <input name="inp-edit-address-full-name" required type="text"
                                           class="form-control" placeholder="حروف فارسی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شماره تماس:
                                    </label>
                                    <input name="inp-edit-address-mobile" required type="text"
                                           class="form-control" placeholder="برای مثال: 0912xxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-edit-address-province"
                                            class="selectric_dropdown city-loader-select"
                                            data-city-select-target="#editAddressCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected"
                                                disabled="disabled">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شهر:
                                    </label>
                                    <select name="inp-edit-address-city"
                                            class="selectric_dropdown"
                                            id="editAddressCitySelect">
                                        <option value="<?= DEFAULT_OPTION_VALUE ?>"
                                                selected="selected"
                                                disabled="disabled">
                                            انتخاب کنید
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        کد پستی:
                                    </label>
                                    <input name="inp-edit-address-postal-code" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            class="form-control form-control-min-height"
                                            name="inp-edit-address-address"
                                            id=""
                                            cols="30"
                                            rows="10"
                                            placeholder="وارد کنید"
                                    ></textarea>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-success btn-block text-uppercase"
                                            title="اشتراک" type="submit">
                                        ویرایش آدرس
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END EDIT ADDRESS MODAL -->