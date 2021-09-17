<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'افزودن قیمت پلکانی جدید']); ?>

        <div class="card-body">
            <form action="<?= url('admin.stepped-price.add', ['code' => $product_code])->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_add_stepped">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $stepped_add_errors ?? [],
                    'success' => $stepped_add_success ?? '',
                    'warning' => $stepped_add_warning ?? '',
                ]); ?>

                <?php load_partial('admin/message/message-info', [
                    'info' => 'وارد کردن یکی از دو فیلد ' .
                        '<span class="badge bg-white text-dark mx-1">حداقل تعداد در سبد خرید</span>' .
                        ' یا' .
                        '<span class="badge bg-white text-dark mx-1">حداکثر تعداد در سبد خرید</span>' .
                        ' الزامی است',
                    'dismissible' => false,
                ]); ?>

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>
                            حداقل تعداد در سبد خرید:
                        </label>
                        <input type="text" class="form-control" placeholder="از نوع عددی"
                               name="inp-add-stepped-min-count"
                               value="<?= $validator->setInput('inp-add-stepped-min-count'); ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            حداکثر تعداد در سبد خرید:
                        </label>
                        <input type="text" class="form-control" placeholder="از نوع عددی"
                               name="inp-add-stepped-max-count"
                               value="<?= $validator->setInput('inp-add-stepped-max-count'); ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            قیمت:
                        </label>
                        <input type="text" class="form-control" placeholder="به تومان" name="inp-add-stepped-price"
                               value="<?= $validator->setInput('inp-add-stepped-price'); ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            قیمت با تخفیف:
                        </label>
                        <input type="text" class="form-control" placeholder="به تومان"
                               name="inp-add-stepped-discounted-price"
                               value="<?= $validator->setInput('inp-add-stepped-discounted-price'); ?>">
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
</div>
<!-- /content area -->