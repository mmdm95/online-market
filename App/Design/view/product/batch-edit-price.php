<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <form action="<?= url('admin.product.batch-edit-price')->getRelativeUrlTrimmed(); ?>" method="post"
          id="__form_batch_edit_product">
        <?php load_partial('admin/message/message-form', [
            'errors' => $product_batch_edit_errors ?? [],
            'success' => $product_batch_edit_success ?? '',
            'warning' => $product_batch_edit_warning ?? '',
        ]); ?>
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>" data-ignored>

        <input type="hidden" name="batch-edit-ids" value="<?= $ids ?? ''; ?>" data-ignored>

        <div class="row">

            <!-- Selected products -->
            <div class="col-lg-12">
                <div class="card">
                    <?php load_partial('admin/card-header', ['header_title' => 'محصولات انتخاب شده']); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-unstyled" style="max-height: 300px; overflow-y: auto">
                                    <?php foreach ($products ?? [] as $product): ?>
                                        <li class="d-flex align-items-center my-2">
                                            <img src="<?= url('image.show', ['filename' => $product['image']]); ?>"
                                                 alt="<?= $product['title']; ?>"
                                                 class="mr-2 rounded-full border"
                                                 width="80px" height="80px" style="object-fit: cover">
                                            <span><?= $product['title']; ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /selected products -->

            <!-- Product information -->
            <div class="col-lg-12">
                <div class="card">
                    <?php load_partial('admin/card-header', ['header_title' => 'تغییر قیمت به صورت درصدی']); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="form-check form-check-inline form-check-right">
                                    <label class="form-check-label">
                                        افزایش قیمت
                                        <input type="radio" class="form-check-input-styled"
                                               name="inp-edit-product-increase-price-radio"
                                               value="1"
                                               checked data-fouc>
                                    </label>
                                </div>

                                <div class="form-check form-check-inline form-check-right">
                                    <label class="form-check-label">
                                        کاهش قیمت
                                        <input type="radio" class="form-check-input-styled"
                                               name="inp-edit-product-increase-price-radio"
                                               value="2"
                                               data-fouc>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>درصد تغییر قیمت:</label>
                                <input type="number" class="form-control" placeholder="از نوع عددی"
                                       name="inp-edit-product-increase-price"
                                       min="1"
                                       max="99"
                                       value="<?= $validator->setInput('inp-edit-product-increase-price'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product information -->

            <div class="col-lg-12">
                <div class="row flex-row-reverse">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            ذخیره اطلاعات
                            <i class="icon-floppy-disks ml-2"></i>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= url('admin.product.view', ''); ?>" class="btn bg-white text-dark btn-lg btn-block">
                            بازگشت
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /content area -->