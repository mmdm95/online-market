<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن روش پرداخت جدید']); ?>

        <div class="card-body">
            <form action="<?= url('admin.pay_method.add')->getRelativeUrlTrimmed(); ?>" method="post"
                  id="__form_add_pay_method">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $pay_method_add_errors ?? [],
                    'success' => $pay_method_add_success ?? '',
                    'warning' => $pay_method_add_warning ?? '',
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="col-12">
                        <div class="d-block d-lg-flex justify-content-between align-items-end">
                            <div class="form-group text-center text-lg-left">
                                <label>
                                    <span class="text-danger">*</span>
                                    انتخاب تصویر:
                                </label>
                                <?php
                                $img = $validator->setInput('inp-add-pay-method-img');
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto ml-lg-0 mr-lg-3 mb-0 <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-add-pay-method-img"
                                           value="<?= $img; ?>">
                                    <?php if (!empty($img)): ?>
                                        <img class="img-placeholder-image" src="<?= url('image.show') . $img; ?>" alt="selected image">
                                    <?php endif; ?>
                                    <div class="img-placeholder-icon-container">
                                        <i class="icon-image2 img-placeholder-icon text-grey-300"></i>
                                        <div class="img-placeholder-num bg-warning text-white">
                                            <i class="icon-plus2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center text-lg-right">
                                <div class="form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        نمایش روش پرداخت
                                        <input type="checkbox" class="form-check-input-switchery"
                                               name="inp-add-pay-method-status"
                                            <?= $validator->setCheckbox('inp-add-pay-method-status', 'on', true); ?>>
                                        عدم نمایش روش پرداخت
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>
                            <span class="text-danger">*</span>
                            عنوان روش پرداخت:
                        </label>
                        <input type="text" class="form-control" placeholder="وارد کنید" name="inp-add-pay-method-title"
                               value="<?= $validator->setInput('inp-add-pay-method-title'); ?>">
                    </div>

                    <div class="form-group col-lg-12 border border-info p-3 rounded accordion" id="radioAccordion">
                        <label>
                            <span class="text-danger">*</span>
                            انتخاب نوع روش پرداخت:
                        </label>

                        <div class="accordion" id="radioAccordion">
                            <div class="card">
                                <div class="card-header" id="sadadHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                   checked="checked"
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseSadad"
                                                   aria-controls="collapseSadad"
                                                   value="<?= METHOD_TYPE_GATEWAY_SADAD; ?>"
                                                   name="inp-add-pay-method-method"
                                                   data-fouc>
                                            <img src="" data-src="<?= asset_path('image/gateways/sadad.jpg', false); ?>"
                                                 alt="سداد" class="rounded mr-2 lazy"
                                                 width="auto" height="40">
                                            <?= METHOD_TYPES[METHOD_TYPE_GATEWAY_SADAD]; ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group collapse" id="collapseSadad"
                                     aria-labelledby="sadadHeading" data-parent="#radioAccordion">
                                    <div class="row m-0">
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                کلید:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-sadad-key">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                شماره ترمینال:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-sadad-terminal">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                شماره مرچنت:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-sadad-merchant">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="behPardakhtHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseBehPardakht"
                                                   aria-controls="collapseBehPardakht"
                                                   value="<?= METHOD_TYPE_GATEWAY_BEH_PARDAKHT; ?>"
                                                   name="inp-add-pay-method-method"
                                                   data-fouc>
                                            <img src="" data-src="<?= asset_path('image/gateways/beh-pardakht.png', false); ?>"
                                                 alt="به پرداخت" class="rounded mr-2 lazy"
                                                 width="auto" height="40">
                                            <?= METHOD_TYPES[METHOD_TYPE_GATEWAY_BEH_PARDAKHT]; ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group collapse" id="collapseBehPardakht"
                                     aria-labelledby="behPardakhtHeading" data-parent="#radioAccordion">
                                    <div class="row m-0">
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                شماره ترمینال:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-beh-pardakht-terminal">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                نام کاربری:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-beh-pardakht-username">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                کلمه عبور:
                                            </label>
                                            <input type="password"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-beh-pardakht-password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="idpayHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseIdpay"
                                                   aria-controls="collapseIdpay"
                                                   value="<?= METHOD_TYPE_GATEWAY_IDPAY; ?>"
                                                   name="inp-add-pay-method-method"
                                                   data-fouc>
                                            <img src="" data-src="<?= asset_path('image/gateways/Idpay.png', false); ?>"
                                                 alt="آی‌دی پی" class="rounded mr-2 lazy"
                                                 width="auto" height="40">
                                            <?= METHOD_TYPES[METHOD_TYPE_GATEWAY_IDPAY]; ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group collapse" id="collapseIdpay"
                                     aria-labelledby="idpayHeading" data-parent="#radioAccordion">
                                    <div class="row m-0">
                                        <div class="col-lg-12 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                کلید API:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-idpay-api-key">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="mabnaHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseMabna"
                                                   aria-controls="collapseMabna"
                                                   value="<?= METHOD_TYPE_GATEWAY_MABNA; ?>"
                                                   name="inp-add-pay-method-method"
                                                   data-fouc>
                                            <img src="" data-src="<?= asset_path('image/gateways/mabna.png', false); ?>"
                                                 alt="مبنا" class="rounded mr-2 lazy"
                                                 width="auto" height="40">
                                            <?= METHOD_TYPES[METHOD_TYPE_GATEWAY_MABNA]; ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group collapse" id="collapseMabna"
                                     aria-labelledby="mabnaHeading" data-parent="#radioAccordion">
                                    <div class="row m-0">
                                        <div class="col-lg-12 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                شماره ترمینال:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-mabna-terminal">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="zarinpalHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseZarinpal"
                                                   aria-controls="collapseZarinpal"
                                                   value="<?= METHOD_TYPE_GATEWAY_ZARINPAL; ?>"
                                                   name="inp-add-pay-method-method"
                                                   data-fouc>
                                            <img src="" data-src="<?= asset_path('image/gateways/zarinpal.png', false); ?>"
                                                 alt="زرین پال" class="rounded mr-2 lazy"
                                                 width="auto" height="40">
                                            <?= METHOD_TYPES[METHOD_TYPE_GATEWAY_ZARINPAL]; ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group collapse" id="collapseZarinpal"
                                     aria-labelledby="zarinpalHeading" data-parent="#radioAccordion">
                                    <div class="row m-0">
                                        <div class="col-lg-12 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                شماره مرچنت:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-add-pay-method-zarinpal-merchant">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        ذخیره اطلاعات
                        <i class="icon-floppy-disks ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mini file manager modal -->
    <?php load_partial('file-manager/modal-efm', [
        'the_options' => $the_options ?? [],
    ]); ?>
    <!-- /mini file manager modal -->
</div>
<!-- /content area -->