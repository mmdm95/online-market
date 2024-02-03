<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="row">
        <div class="col-lg-9">
            <!-- 2 columns form -->
            <div class="card">
                <?php load_partial('admin/card-header', ['header_title' => 'شارژ کیف پول']); ?>

                <div class="card-body">
                    <form action="<?= url('admin.wallet.charge', ['id' => $wallet_id])->getRelativeUrlTrimmed(); ?>"
                          method="post" id="__form_charge_wallet">
                        <?php load_partial('admin/message/message-form', [
                            'errors' => $wallet_charge_errors ?? [],
                            'success' => $wallet_charge_success ?? '',
                            'warning' => $wallet_charge_warning ?? '',
                        ]); ?>

                        <div class="row">
                            <div class="col-lg-12 col-xl-4 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    مبلغ(به تومان):
                                </label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="به تومان"
                                       name="inp-charge-wallet-price"
                                       value="<?= $validator->setInput('inp-charge-wallet-price'); ?>">
                            </div>
                            <div class="col-lg-12 col-xl-4 form-group">
                                <label>
                                    <span class="text-danger">*</span>
                                    توضیح شارژ:
                                </label>
                                <select data-placeholder="توضیح شارژ"
                                        class="form-control form-control-select2"
                                        name="inp-charge-wallet-desc"
                                        data-fouc>
                                    <option selected disabled value="<?= DEFAULT_OPTION_VALUE ?>">انتخاب کنید</option>
                                    <?php foreach ($deposit_types as $type): ?>
                                        <option value="<?= $type['id']; ?>"
                                            <?= $validator->setSelect('inp-charge-wallet-desc', $type['id']); ?>>
                                            <?= $type['title']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-12 col-xl-4 text-right mt-0 mt-xl-3">
                                <button type="submit" class="btn btn-primary">
                                    ذخیره اطلاعات
                                    <i class="icon-floppy-disks ml-2"></i>
                                </button>
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