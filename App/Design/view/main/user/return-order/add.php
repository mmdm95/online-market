<?php

use Sim\Utils\StringUtil;

$validator = form_validator();

?>

<?php load_partial('main/message/message-form', [
    'errors' => $return_order_add_errors ?? [],
    'success' => $return_order_add_success ?? '',
    'warning' => $return_order_add_warning ?? '',
]); ?>

<?php load_partial('main/message/message-info.php', [
    'info' => 'علت مرجوع کردن را وارد، آیتم‌های مرجوعی را انتخاب کرده و سپس دکمه مرجوع کردن را کلیک کنید.',
    'dismissible' => false,
]); ?>

<form action="<?= url('user.return-order.add', ['code' => $code])->getRelativeUrlTrimmed(); ?>"
      method="post">
    <div class="dashboard_content">
        <div class="card">
            <div class="card-header">
                <h3>علت مرجوعی</h3>
            </div>

            <div class="card-body">
                <textarea
                        class="form-control form-control-min-height"
                        name="inp-return-order-desc"
                        cols="30"
                        rows="10"
                        placeholder="توضیحات خود را وارد کنید"
                        required="required"
                ><?= $validator->setInput('inp-return-order-desc', $returnOrder['desc'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>

    <div class="dashboard_content">
        <div class="card">
            <div class="card-header">
                <h3>آیتم‌های سفارش</h3>
            </div>
            <?php
            $k = 0;
            $returnItemsCount = 0;
            ?>
            <div class="card-body p-0">
                <?php foreach ($orderItems as $item): ?>
                    <div class="position-relative <?= 0 != $k++ ? 'border-top' : ''; ?> p-3">
                        <div class="order-detail-order-items-more">
                            <?php //if ($item['is_returnable'] == DB_YES): ?>
                                <?php $returnItemsCount++; ?>

                                <div class="custome-checkbox">
                                    <input type="hidden" name="inp-order-item-id[<?= $item['id']; ?>]"
                                           value="<?= $item['id']; ?>">
                                    <input class="form-check-input"
                                           type="checkbox" id="chk_num_<?= $k; ?>"
                                           name="inp-order-item-check[<?= $item['id']; ?>]" value="<?= $item['id']; ?>">
                                    <label class="form-check-label"
                                           for="chk_num_<?= $k; ?>">
                                        <span>انتخاب جهت مرجوع</span>
                                    </label>
                                </div>
                                <div>
                                    <div class="cart-product-quantity">
                                        <div class="quantity">
                                            <input type="button" value="-" class="minus">
                                            <input type="text" name="inp-return-item-quantity[<?= $item['id']; ?>]"
                                                   value="<?= $item['product_count']; ?>"
                                                   title="Qty"
                                                   class="qty" size="4"
                                                   data-max-cart-count="<?= $item['product_count']; ?>">
                                            <input type="button" value="+" class="plus">
                                        </div>
                                    </div>
                                </div>
                            <?php //else: ?>
<!--                                <span class="badge badge-warning p-2">این محصول قابل مرجوع نمی‌باشد</span>-->
                            <?php //endif; ?>
                        </div>
                        <div class="d-block d-lg-flex m-0 align-items-start">
                            <?php if (!empty($item['product_image'])): ?>
                                <div>
                                    <a href="<?= url('home.product.show', [
                                        'id' => $item['product_id'],
                                        'slug' => $item['product_slug'],
                                    ]); ?>">
                                        <img src=""
                                             data-src="<?= url('image.show', ['filename' => $item['product_image']]); ?>"
                                             alt="<?= $item['product_title']; ?>"
                                             class="mx-0 mx-lg-3 lazy"
                                             width="160px" height="auto">
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="col">
                                <div>
                                    <div class="d-flex justify-content-between pr-4">
                                        <?php if (!empty($item['product_image'])): ?>
                                            <a href="<?= url('home.product.show', [
                                                'id' => $item['product_id'],
                                                'slug' => $item['product_slug'],
                                            ]); ?>">
                                                <h6>
                                                    <?= $item['product_title']; ?>
                                                </h6>
                                            </a>
                                        <?php else: ?>
                                            <h6>
                                                <?= $item['product_title']; ?>
                                            </h6>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($item['color'])): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="product_color_badge">
                                                <span class="mr-2"
                                                      style="background-color: <?= $item['color']; ?>;"></span>
                                                <div class="d-inline-block"><?= $item['color_name']; ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['size'])): ?>
                                        <div class="d-flex align-items-center mt-1">
                                            <i class="icon-size-fullscreen mr-2 ml-1" aria-hidden="true"></i>
                                            <?= $item['size']; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['guarantee'])): ?>
                                        <div class="d-flex align-items-center mt-1">
                                            <i class="linearicons-shield-check mr-2 ml-1" aria-hidden="true"></i>
                                            <?= $item['guarantee']; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['order_item_id'])): ?>
                                        <div>
                                            <?php if (DB_YES == $item['is_returned']): ?>
                                                <span class="badge badge-danger px-2 py-1">
                                                        تعداد
                                                        <?= local_number($item['return_count']); ?>
                                                    <?= $item['unit_title']; ?>
                                                        مرجوع شده
                                                    </span>
                                            <?php elseif (!empty($item['order_item_id'])): ?>
                                                <span class="badge badge-info px-2 py-1">
                                                        درخواست مرجوع تعداد
                                                        <?= local_number($item['return_count']); ?>
                                                    <?= $item['unit_title']; ?>
                                                    </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <ul class="list-inline list-inline-dotted my-3">
                                    <li class="list-inline-item my-1">
                                        <small>قیمت واحد:</small>
                                        <label class="m-0">
                                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['unit_price']))); ?>
                                            <small>تومان</small>
                                        </label>
                                    </li>
                                    <li class="list-inline-item my-1">
                                        <small>تعداد:</small>
                                        <label class="m-0">
                                            <?= local_number($item['product_count']); ?>
                                            <?= $item['unit_title']; ?>
                                        </label>
                                    </li>
                                    <?php
                                    $discount = ((float)$item['price'] - (float)$item['discounted_price']);
                                    ?>
                                    <?php if (!empty($discount)): ?>
                                        <li class="list-inline-item my-1">
                                            <small>تخفیف:</small>
                                            <label class="m-0">
                                                <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($discount))); ?>
                                                <small>تومان</small>
                                            </label>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($returnItemsCount > 0): ?>
                <button type="submit"
                        class="btn btn-fill-out btn-sm btn-block">
                    <i class="ti-check" aria-hidden="true"></i>
                    مرجوع کردن آیتم‌های انتخاب شده
                </button>
            <?php else: ?>
                <div class="alert alert-danger text-center m-0 py-4">
                    آیتم‌های سفارش قابل مرجوع نمی‌باشند
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>