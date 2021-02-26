<?php if (count($addresses)): ?>
    <div class="address-elements-container">
        <?php foreach ($addresses as $address): ?>
            <div class="dashboard_content remove-element-item">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border border-light-alpha">
                            <div class="card-body">
                                <div class="text-right">
                                    <button class="btn btn-outline-danger btn-sm px-3 mb-2 __item_custom_remover_btn"
                                            data-remove-url="<?= url('ajax.user.address.remove')->getRelativeUrlTrimmed(); ?>"
                                            data-remove-id="<?= $address['id']; ?>"
                                            data-toggle="tooltip" data-original-title="حذف آدرس"
                                            data-placement="right">
                                        <i class="ti-trash icon-2x m-0" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <address>
                                    <div class="mb-3">
                                        <?= $address['address']; ?>
                                    </div>
                                </address>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="linearicons-map-marker-user address-icon-size mr-2"
                                       aria-hidden="true"></i>
                                    <label class="m-0">
                                        <?= $address['province_name']; ?>
                                        ،
                                        <?= $address['city_name']; ?>
                                    </label>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="linearicons-envelope address-icon-size mr-2"
                                       aria-hidden="true"></i>
                                    <label class="m-0">
                                        <?= $address['postal_code']; ?>
                                    </label>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="linearicons-phone-bubble address-icon-size mr-2"
                                       aria-hidden="true"></i>
                                    <label class="m-0">
                                        <?= $address['mobile']; ?>
                                    </label>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="linearicons-user address-icon-size mr-2"
                                       aria-hidden="true"></i>
                                    <label class="m-0">
                                        <?= $address['full_name']; ?>
                                    </label>
                                </div>
                            </div>
                            <button data-toggle="modal"
                                    data-target="#__user_addr_edit_modal"
                                    data-edit-id="<?= $address['id']; ?>"
                                    class="btn btn-light btn-block rounded-0 edit-element-item">
                                ویرایش
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?php load_partial('main/not-found-rows'); ?>
<?php endif; ?>

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
                                    <input name="inp-address-add-full-name" required type="text"
                                           class="form-control" placeholder="حروف فارسی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شماره تماس:
                                    </label>
                                    <input name="inp-address-add-mobile" required type="text"
                                           class="form-control" placeholder="برای مثال: 0912xxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-address-add-province"
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
                                    <select name="inp-address-add-city"
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
                                    <input name="inp-address-add-postal-code" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            class="form-control form-control-min-height"
                                            name="inp-address-add-address"
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
                                    <input name="inp-address-edit-full-name" required type="text"
                                           class="form-control" placeholder="حروف فارسی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        شماره تماس:
                                    </label>
                                    <input name="inp-address-edit-mobile" required type="text"
                                           class="form-control" placeholder="برای مثال: 0912xxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        استان:
                                    </label>
                                    <select name="inp-address-edit-province"
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
                                    <select name="inp-address-edit-city"
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
                                    <input name="inp-address-edit-postal-code" required type="text"
                                           class="form-control" placeholder="از نوع عددی">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span class="text-danger" aria-hidden="true">*</span>
                                        آدرس:
                                    </label>
                                    <textarea
                                            class="form-control form-control-min-height"
                                            name="inp-address-edit-address"
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