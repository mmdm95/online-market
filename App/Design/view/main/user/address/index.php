<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#myAddresses">
            آدرس‌های من
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#legalAddresses">
            آدرس‌های حقوقی
        </a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane container active p-0" id="myAddresses">
        <div class="dashboard_content">
            <div class="text-right">
                <button type="button" class="btn btn-info mb-3"
                        data-toggle="modal" data-target="#__user_addr_add_modal">
                    افزودن آدرس جدید
                    <i class="ti-plus icon-half-x ml-2"></i>
                </button>
            </div>

            <div class="address-elements-container">
                <?php load_partial('main/user/addresses', ['addresses' => $addresses ?? []]); ?>
            </div>
        </div>
    </div>

    <div class="tab-pane container p-0" id="legalAddresses">
        <div class="dashboard_content">
            <div class="text-right">
                <button type="button" class="btn btn-info mb-3"
                        data-toggle="modal" data-target="#__user_addr_company_add_modal">
                    افزودن آدرس حقوقی جدید
                    <i class="ti-plus icon-half-x ml-2"></i>
                </button>
            </div>

            <div class="address-company-elements-container">
                <?php load_partial('main/user/addresses-company', ['addresses_company' => $addresses_company ?? []]); ?>
            </div>
        </div>
    </div>
</div>

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
                                    <button class="btn btn-primary btn-block text-uppercase" type="submit">
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
                                    <button class="btn btn-success btn-block text-uppercase" type="submit">
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

<!-- START ADD ADDRESS COMPANY MODAL -->
<div class="modal fade subscribe_popup" id="__user_addr_company_add_modal" tabindex="-1" role="dialog"
     aria-hidden="true">
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
                                    <h4>افزودن آدرس حقوقی</h4>
                                </div>
                            </div>

                            <form action="#" id="__form_add_address_company">
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        نام شرکت:
                                    </label>
                                    <input name="inp-add-address-company-name" required type="text"
                                           class="form-control" placeholder="حروف فارسی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        کد اقتصادی:
                                    </label>
                                    <input name="inp-add-address-company-economic-code" required type="text"
                                           class="form-control" placeholder="وارد نمایید">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شناسه ملی:
                                    </label>
                                    <input name="inp-add-address-company-economic-national-id" required type="text"
                                           class="form-control" placeholder="وارد نمایید">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شماره ثبت:
                                    </label>
                                    <input name="inp-add-address-company-registration-number" required type="text"
                                           class="form-control" placeholder="وارد نمایید">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        تلفن ثابت:
                                    </label>
                                    <input name="inp-add-address-company-landline-tel" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-add-address-company-province"
                                            class="selectric_dropdown city-loader-select"
                                            data-city-select-target="#addAddressCompanyCitySelect">
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
                                    <select name="inp-add-address-company-city"
                                            class="selectric_dropdown"
                                            id="addAddressCompanyCitySelect">
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
                                    <input name="inp-add-address-company-postal-code" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            class="form-control form-control-min-height"
                                            name="inp-add-address-company-addr"
                                            id=""
                                            cols="30"
                                            rows="10"
                                            placeholder="وارد کنید"
                                    ></textarea>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary btn-block text-uppercase" type="submit">
                                        افزودن آدرس حقوقی
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
<!-- END ADD ADDRESS COMPANY MODAL -->

<!-- START EDIT ADDRESS COMPANY MODAL -->
<div class="modal fade subscribe_popup" id="__user_addr_company_edit_modal" tabindex="-1" role="dialog"
     aria-hidden="true">
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

                            <form action="#" id="__form_edit_address_company">
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        نام شرکت:
                                    </label>
                                    <input name="inp-edit-address-company-name" required type="text"
                                           class="form-control" placeholder="حروف فارسی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        کد اقتصادی:
                                    </label>
                                    <input name="inp-edit-address-company-economic-code" required type="text"
                                           class="form-control" placeholder="وارد نمایید">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شناسه ملی:
                                    </label>
                                    <input name="inp-edit-address-company-economic-national-id" required type="text"
                                           class="form-control" placeholder="وارد نمایید">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شماره ثبت:
                                    </label>
                                    <input name="inp-edit-address-company-registration-number" required type="text"
                                           class="form-control" placeholder="وارد نمایید">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        تلفن ثابت:
                                    </label>
                                    <input name="inp-edit-address-company-landline-tel" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-edit-address-company-province"
                                            class="selectric_dropdown city-loader-select"
                                            data-city-select-target="#editAddressCompanyCitySelect">
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
                                    <select name="inp-edit-address-company-city"
                                            class="selectric_dropdown"
                                            id="editAddressCompanyCitySelect">
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
                                    <input name="inp-edit-address-company-postal-code" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            class="form-control form-control-min-height"
                                            name="inp-edit-address-company-addr"
                                            id=""
                                            cols="30"
                                            rows="10"
                                            placeholder="وارد کنید"
                                    ></textarea>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-success btn-block text-uppercase" type="submit">
                                        ویرایش آدرس حقوقی
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
<!-- END EDIT ADDRESS COMPANY MODAL -->
