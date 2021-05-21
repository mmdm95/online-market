<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'ویرایش روش پرداخت']); ?>

        <div class="card-body">
            <form action="<?= url('admin.pay_method.edit')->getRelativeUrl() . $payment['id']; ?>" method="post"
                  id="__form_edit_pay_method">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $pay_method_edit_errors ?? [],
                    'success' => $pay_method_edit_success ?? '',
                    'warning' => $pay_method_edit_warning ?? '',
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
                                $img = $validator->setInput('inp-edit-pay-method-img') ?: (url('image.show')->getRelativeUrl() . $payment['image']);
                                ?>
                                <div class="img-placeholder-custom __file_picker_handler __file_image mx-auto ml-lg-0 mr-lg-3 mb-0 <?= !empty($img) ? 'has-image' : ''; ?>"
                                     data-toggle="modal"
                                     data-target="#modal_efm">
                                    <input type="hidden" name="inp-edit-pay-method-img"
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
                                               name="inp-edit-pay-method-status"
                                            <?= $validator->setCheckbox('inp-edit-pay-method-status', 'on') ?: (is_value_checked($payment['publish']) ? 'checked="checked"' : ''); ?>>
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
                        <input type="text" class="form-control" placeholder="وارد کنید" name="inp-edit-pay-method-title"
                               value="<?= $validator->setInput('inp-edit-pay-method-title') ?: $payment['title']; ?>">
                    </div>

                    <div class="form-group col-lg-12 border border-info p-3 rounded accordion" id="radioAccordion">
                        <label>
                            <span class="text-danger">*</span>
                            انتخاب نوع روش پرداخت:
                        </label>

                        <?php
                        $method = $validator->setInput('inp-edit-pay-method-method');
                        ?>

                        <div class="accordion" id="radioAccordion">
                            <div class="card">
                                <div class="card-header" id="behPardakhtHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                <?= METHOD_TYPE_GATEWAY_BEH_PARDAKHT == $method || '' == $method ? 'checked="checked"' : ''; ?>
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseBehPardakht"
                                                   aria-controls="collapseBehPardakht"
                                                   value="<?= METHOD_TYPE_GATEWAY_BEH_PARDAKHT; ?>"
                                                   name="inp-edit-pay-method-method"
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
                                                   value="<?= $validator->setInput('inp-edit-pay-method-beh-pardakht-terminal') ?: ($payment['meta_parameters']['terminal'] ?: ''); ?>"
                                                   name="inp-edit-pay-method-beh-pardakht-terminal">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                نام کاربری:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder=""
                                                   value="<?= $validator->setInput('inp-edit-pay-method-beh-pardakht-username') ?: ($payment['meta_parameters']['username'] ?: ''); ?>"
                                                   name="inp-edit-pay-method-beh-pardakht-username">
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label>
                                                <span class="text-danger">*</span>
                                                کلمه عبور:
                                            </label>
                                            <input type="password"
                                                   class="form-control"
                                                   placeholder=""
                                                   name="inp-edit-pay-method-beh-pardakht-password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="idpayHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                <?= METHOD_TYPE_GATEWAY_IDPAY == $method ? 'checked="checked"' : ''; ?>
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseIdpay"
                                                   aria-controls="collapseIdpay"
                                                   value="<?= METHOD_TYPE_GATEWAY_IDPAY; ?>"
                                                   name="inp-edit-pay-method-method"
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
                                                   value="<?= $validator->setInput('inp-edit-pay-method-idpay-api-key') ?: ($payment['meta_parameters']['api_key'] ?: ''); ?>"
                                                   name="inp-edit-pay-method-idpay-api-key">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="mabnaHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                <?= METHOD_TYPE_GATEWAY_MABNA == $method ? 'checked="checked"' : ''; ?>
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseMabna"
                                                   aria-controls="collapseMabna"
                                                   value="<?= METHOD_TYPE_GATEWAY_MABNA; ?>"
                                                   name="inp-edit-pay-method-method"
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
                                                   value="<?= $validator->setInput('inp-edit-pay-method-mabna-terminal') ?: ($payment['meta_parameters']['terminal'] ?: ''); ?>"
                                                   name="inp-edit-pay-method-mabna-terminal">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="zarinpalHeading">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label align-items-center">
                                            <input type="radio"
                                                <?= METHOD_TYPE_GATEWAY_ZARINPAL == $method ? 'checked="checked"' : ''; ?>
                                                   data-toggle="collapse"
                                                   aria-expanded="false"
                                                   class="form-check-input-styled"
                                                   data-target="#collapseZarinpal"
                                                   aria-controls="collapseZarinpal"
                                                   value="<?= METHOD_TYPE_GATEWAY_ZARINPAL; ?>"
                                                   name="inp-edit-pay-method-method"
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
                                                   value="<?= $validator->setInput('inp-edit-pay-method-zarinpal-merchant') ?: ($payment['meta_parameters']['merchant'] ?: ''); ?>"
                                                   name="inp-edit-pay-method-zarinpal-merchant">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">
                        ویرایش اطلاعات
                        <i class="icon-checkmark3 ml-2"></i>
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