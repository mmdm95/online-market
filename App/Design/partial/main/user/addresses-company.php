<?php if (count($addresses_company ?? [])): ?>
    <?php foreach ($addresses_company as $address): ?>
        <div class="dashboard_content remove-element-item">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border border-light-alpha">
                        <div class="card-body">
                            <div class="text-right">
                                <button class="btn btn-outline-danger btn-sm px-3 mb-2 __item_custom_remover_btn"
                                        data-remove-url="<?= url('ajax.user.address.company.remove')->getRelativeUrlTrimmed(); ?>"
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
                                <span class="mr-2 text-muted font-size-sm">استان و شهر:</span>
                                <label class="m-0">
                                    <?= $address['province_name']; ?>
                                    ،
                                    <?= $address['city_name']; ?>
                                </label>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="linearicons-envelope address-icon-size mr-2"
                                   aria-hidden="true"></i>
                                <span class="mr-2 text-muted font-size-sm">کد پستی:</span>
                                <label class="m-0">
                                    <?= $address['postal_code']; ?>
                                </label>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="linearicons-phone-bubble address-icon-size mr-2"
                                   aria-hidden="true"></i>
                                <span class="mr-2 text-muted font-size-sm">تلفن ثابت:</span>
                                <label class="m-0">
                                    <?= $address['landline_tel']; ?>
                                </label>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="linearicons-user address-icon-size mr-2"
                                   aria-hidden="true"></i>
                                <span class="mr-2 text-muted font-size-sm">نام شرکت گیرنده:</span>
                                <label class="m-0">
                                    <?= $address['company_name']; ?>
                                </label>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="linearicons-book2 address-icon-size mr-2"
                                   aria-hidden="true"></i>
                                <span class="mr-2 text-muted font-size-sm">کد اقتصادی:</span>
                                <label class="m-0">
                                    <?= $address['economic_code']; ?>
                                </label>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="linearicons-book2 address-icon-size mr-2"
                                   aria-hidden="true"></i>
                                <span class="mr-2 text-muted font-size-sm">شناسه ملی:</span>
                                <label class="m-0">
                                    <?= $address['economic_national_id']; ?>
                                </label>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="linearicons-book2 address-icon-size mr-2"
                                   aria-hidden="true"></i>
                                <span class="mr-2 text-muted font-size-sm">شماره ثبت:</span>
                                <label class="m-0">
                                    <?= $address['registration_number']; ?>
                                </label>
                            </div>
                        </div>
                        <button data-toggle="modal"
                                data-target="#__user_addr_company_edit_modal"
                                data-edit-id="<?= $address['id']; ?>"
                                class="btn btn-light btn-block rounded-0 edit-company-element-item">
                            ویرایش
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?php load_partial('main/not-found-rows'); ?>
<?php endif; ?>