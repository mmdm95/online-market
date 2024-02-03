<?php

$validator = form_validator();

?>

<!-- Content area -->
<div class="content">
    <form action="<?= url('admin.product.batch-edit')->getRelativeUrlTrimmed(); ?>" method="post"
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
                <?php load_partial('admin/message/message-info', [
                    'info' => 'برای عدم تغییر مشخصات، منوهای کشویی را بر روی' .
                        '<span class="badge badge-primary mx-1">انتخاب کنید</span>' .
                        'و باقی موارد را' .
                        '<span class="badge badge-warning mx-1">خالی</span>' .
                        'بگذارید.',
                    'dismissible' => false,
                ]); ?>

                <div class="card">
                    <?php load_partial('admin/card-header', ['header_title' => 'مشخصات اولیه محصول']); ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-5">
                                <label>
                                    واحد:
                                </label>
                                <select data-placeholder="واحد کالا را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-edit-product-unit" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($units as $unit): ?>
                                        <option value="<?= $unit['id']; ?>"
                                            <?= $validator->setSelect('inp-edit-product-unit', $unit['id']); ?>>
                                            <?= $unit['title'] . (!empty($unit['sign']) ? ' - ' . $unit['sign'] : ''); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-7">
                                <label>
                                    برند:
                                </label>
                                <select data-placeholder="برند را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-edit-product-brand" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= $brand['id']; ?>"
                                            <?= $validator->setSelect('inp-edit-product-brand', $brand['id']); ?>>
                                            <?= $brand['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-7">
                                <label>
                                    دسته‌بندی:
                                </label>
                                <select data-placeholder="دسته‌بندی را انتخاب کنید..."
                                        class="form-control form-control-select2-searchable"
                                        name="inp-edit-product-category" data-fouc>
                                    <option value="<?= DEFAULT_OPTION_VALUE; ?>"
                                            selected="selected">
                                        انتخاب کنید
                                    </option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id']; ?>"
                                            <?= $validator->setSelect('inp-edit-product-category', $category['id']); ?>>
                                            <?= $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-5">
                                <label>تعداد کالا برای هشدار:</label>
                                <input type="text" class="form-control" placeholder="از نوع عددی"
                                       name="inp-edit-product-alert-product"
                                       value="<?= $validator->setInput('inp-edit-product-alert-product'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product information -->

            <!-- Product publish -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-light">
                        <label class="m-0 cursor-pointer" for="__pubStatusChk">
                            عدم در نظر گرفتن وضعیت نمایش
                        </label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox"
                                       id="__pubStatusChk"
                                       class="form-check-input-styled"
                                       checked="checked"
                                       name="inp-edit-product-status-chk"
                                       data-fouc>
                            </label>
                        </div>
                    </div>
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__pubStatus">
                                وضعیت نمایش
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__pubStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-status"
                                            <?= $validator->setCheckbox('inp-edit-product-status', 'on'); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product publish -->

            <!-- Product availability -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-light">
                        <label class="m-0 cursor-pointer" for="__avStatusChk">
                            عدم در نظر گرفتن وضعیت موجودی
                        </label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox"
                                       id="__avStatusChk"
                                       class="form-check-input-styled"
                                       checked="checked"
                                       name="inp-edit-product-availability-chk"
                                       data-fouc>
                            </label>
                        </div>
                    </div>
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__avStatus">
                                وضعیت موجودی
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__avStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-availability"
                                            <?= $validator->setCheckbox('inp-edit-product-availability', 'on'); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product availability -->

            <!-- Product special -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-light">
                        <label class="m-0 cursor-pointer" for="__spStatusChk">
                            عدم در نظر گرفتن به عنوان محصول ویژه
                        </label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox"
                                       id="__spStatusChk"
                                       class="form-check-input-styled"
                                       checked="checked"
                                       name="inp-edit-product-special-chk"
                                       data-fouc>
                            </label>
                        </div>
                    </div>
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__spStatus">
                                محصول ویژه
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__spStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-special"
                                            <?= $validator->setCheckbox('inp-edit-product-special', 'on'); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product special -->

            <!-- Product returnable -->
            <!--            <div class="col-lg-6">-->
            <!--                <div class="card">-->
            <!--                    <div class="card-header header-elements-inline bg-light">-->
            <!--                        <label class="m-0 cursor-pointer" for="__rtStatusChk">-->
            <!--                            عدم در نظر گرفتن امکان مرجوع کردن-->
            <!--                        </label>-->
            <!--                        <div class="form-check form-check-inline">-->
            <!--                            <label class="form-check-label">-->
            <!--                                <input type="checkbox"-->
            <!--                                       id="__rtStatusChk"-->
            <!--                                       class="form-check-input-styled"-->
            <!--                                       checked="checked"-->
            <!--                                       name="inp-edit-product-returnable-chk"-->
            <!--                                       data-fouc>-->
            <!--                            </label>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                    <div class="card-header header-elements-inline">-->
            <!--                        <h5 class="card-title">-->
            <!--                            <label class="m-0 cursor-pointer" for="__rtStatus">-->
            <!--                                امکان مرجوع کردن-->
            <!--                            </label>-->
            <!--                        </h5>-->
            <!--                        <div class="header-elements">-->
            <!--                            <div class="list-icons">-->
            <!--                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">-->
            <!--                                    <label class="form-check-label">-->
            <!--                                        <input id="__rtStatus" type="checkbox" class="form-check-input-switchery"-->
            <!--                                               name="inp-edit-product-returnable"-->
            <?= '';//$validator->setCheckbox('inp-edit-product-returnable', 'on');  ?>
            <!--                                    </label>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!-- /product returnable -->

            <!-- Product commenting -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-light">
                        <label class="m-0 cursor-pointer" for="__cStatusChk">
                            عدم در نظر گرفتن اجازه ارسال نظر
                        </label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox"
                                       id="__cStatusChk"
                                       class="form-check-input-styled"
                                       checked="checked"
                                       name="inp-edit-product-commenting-chk"
                                       data-fouc>
                            </label>
                        </div>
                    </div>
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__cStatus">
                                اجازه ارسال نظر
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__cStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-commenting"
                                            <?= $validator->setCheckbox('inp-edit-product-commenting', 'on'); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product commenting -->

            <!-- Product coming soon -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-light">
                        <label class="m-0 cursor-pointer" for="__csStatusChk">
                            عدم در نظر گرفتن نمایش بزودی
                        </label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox"
                                       id="__csStatusChk"
                                       class="form-check-input-styled"
                                       checked="checked"
                                       name="inp-edit-product-coming-soon-chk"
                                       data-fouc>
                            </label>
                        </div>
                    </div>
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__csStatus">
                                نمایش بزودی
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__csStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-coming-soon"
                                            <?= $validator->setCheckbox('inp-edit-product-coming-soon', 'on'); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product coming soon -->

            <!-- Product call for more -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header header-elements-inline bg-light">
                        <label class="m-0 cursor-pointer" for="__cfmStatusChk">
                            عدم در نظر گرفتن تماس برای اطلاعات بیشتر
                        </label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox"
                                       id="__cfmStatusChk"
                                       class="form-check-input-styled"
                                       checked="checked"
                                       name="inp-edit-product-call-for-more-chk"
                                       data-fouc>
                            </label>
                        </div>
                    </div>
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">
                            <label class="m-0 cursor-pointer" for="__cfmStatus">
                                تماس برای اطلاعات بیشتر
                            </label>
                        </h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <div class="list-icons-item form-check form-check-switchery form-check-switchery-double">
                                    <label class="form-check-label">
                                        <input id="__cfmStatus" type="checkbox" class="form-check-input-switchery"
                                               name="inp-edit-product-call-for-more"
                                            <?= $validator->setCheckbox('inp-edit-product-call-for-more', 'on'); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product call for more -->

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