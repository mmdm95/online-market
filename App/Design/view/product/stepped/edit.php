<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <div class="card col-lg-10">
        <?php load_partial('admin/card-header', ['header_title' => 'تغییر قیمت پلکانی']); ?>

        <div class="card-body">
            <form action="<?= url('admin.stepped-price.edit', ['code' => $product_code, 'id' => $product_id])->getRelativeUrlTrimmed(); ?>"
                  method="post" id="__form_edit_stepped">
                <?php load_partial('admin/message/message-form', [
                    'errors' => $stepped_edit_errors ?? [],
                    'success' => $stepped_edit_success ?? '',
                    'warning' => $stepped_edit_warning ?? '',
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
                               name="inp-edit-stepped-min-count"
                               value="<?= $validator->setInput('inp-edit-stepped-min-count') ?: $product['min_count']; ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            حداکثر تعداد در سبد خرید:
                        </label>
                        <input type="text" class="form-control" placeholder="از نوع عددی"
                               name="inp-edit-stepped-max-count"
                               value="<?= $validator->setInput('inp-edit-stepped-max-count') ?: $product['max_count']; ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            قیمت:
                        </label>
                        <input type="text" class="form-control" placeholder="به تومان" name="inp-edit-stepped-price"
                               value="<?= $validator->setInput('inp-edit-stepped-price') ?: $product['price']; ?>">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            <span class="text-danger">*</span>
                            قیمت با تخفیف:
                        </label>
                        <input type="text" class="form-control" placeholder="به تومان"
                               name="inp-edit-stepped-discounted-price"
                               value="<?= $validator->setInput('inp-edit-stepped-discounted-price') ?: $product['discounted_price']; ?>">
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
</div>
<!-- /content area -->