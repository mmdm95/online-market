<?php

use Sim\Utils\StringUtil;

?>

<?php if ($order['payment_status'] == PAYMENT_STATUS_SUCCESS): ?>
    <div class="d-flex align-items-xl-center alert alert-success">
        <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
        <h4 class="ml-4 mb-0">
            سفارش شما پرداخت شده است و نیاز به پرداخت مجدد نمی‌باشد.
        </h4>
    </div>
<?php elseif ((
    in_array($order['payment_status'], [PAYMENT_STATUS_WAIT, PAYMENT_STATUS_NOT_PAYED]) &&
    isset($reserved_item['expire_at']) && $reserved_item['expire_at'] >= time()
)): ?>
    <div class="dashboard_content">
        <div class="card">
            <div class="card-header">
                <h3>انتخاب روش پرداخت</h3>
            </div>

            <div class="card-body">
                <form action="#"
                      method="post" id="__checkout_repay_gateway">
                    <input id="currentOrderId" type="hidden" value="<?= $order['id']; ?>" data-ignored>

                    <div class="mb-3">
                        <label>
                            مبلغ قابل پرداخت (به تومان):
                        </label>
                        <span class="ml-2 py-2 px-3 alert-success rounded">
                            <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($order['final_price']))); ?>
                            <small>تومان</small>
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="order_review">
                            <div class="payment_method">
                                <div class="heading_s1">
                                    <h6>روش پرداخت</h6>
                                </div>
                                <div class="payment_option">
                                    <?php if (count($payment_methods)): ?>
                                        <?php $counter = 0; ?>
                                        <?php foreach ($payment_methods as $k => $method): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="custome-radio">
                                                    <input class="form-check-input" required=""
                                                        <?= 0 == $counter++ ? 'checked="checked"' : ''; ?>
                                                           type="radio" name="inp-re-payment-method-option"
                                                           id="method<?= $k; ?>" value="<?= $method['code']; ?>">
                                                    <label class="form-check-label" for="method<?= $k; ?>">
                                                        <img src=""
                                                             data-src="<?= url('image.show', ['filename' => $method['image']])->getRelativeUrl(); ?>"
                                                             alt="<?= $method['title']; ?>" width="100px" height="auto"
                                                             class="lazy">
                                                        <span class="ml-2"><?= $method['title']; ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        هیچ روش پرداختی وجود ندارد.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit"
                                class="btn btn-fill-out btn-sm">
                            پرداخت سفارش
                            <i class="linearicons-arrow-left icon-half-x ml-2 mr-0" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Receiver info -->
    <div class="dashboard_content">
        <div class="card">
            <div class="card-header">
                <h3>اطلاعات گیرنده</h3>
            </div>
            <div class="row m-0">
                <div class="col-lg-7 border border-light px-3 py-2 text-center">
                    <label class="m-0">
                        <?= $order['receiver_name']; ?>
                    </label>
                </div>

                <?php if ((int)$order['receiver_type'] === RECEIVER_TYPE_LEGAL): ?>
                    <div class="col-lg-5 border border-light px-3 py-2">
                        <small class="mb-1">
                            تلفن ثابت:
                        </small>
                        <label class="mb-1">
                            <?= StringUtil::toPersian(trim($order['company_tel'])) ?? '-'; ?>
                        </label>
                    </div>
                    <div class="col-lg-6 border border-light px-3 py-2">
                        <small class="mb-1">
                            کد اقتصادی:
                        </small>
                        <label class="mb-1">
                            <?= StringUtil::toPersian(trim($order['company_economic_code'])) ?? '-'; ?>
                        </label>
                    </div>
                    <div class="col-lg-6 border border-light px-3 py-2">
                        <small class="mb-1">
                            شناسه ملی:
                        </small>
                        <label class="mb-1">
                            <?= StringUtil::toPersian(trim($order['company_economic_national_id'])) ?? '-'; ?>
                        </label>
                    </div>
                    <div class="col-lg-6 border border-light px-3 py-2">
                        <small class="mb-1">
                            شماره ثبت:
                        </small>
                        <label class="mb-1">
                            <?= StringUtil::toPersian(trim($order['company_registration_number'])) ?? '-'; ?>
                        </label>
                    </div>
                <?php else: ?>
                    <div class="col-lg-5 border border-light px-3 py-2">
                        <small class="mb-1">
                            کد ملی:
                        </small>
                        <label class="mb-1">
                            <?= StringUtil::toPersian(trim($order['user_national_number'])) ?? '-'; ?>
                        </label>
                    </div>
                    <div class="col-lg-6 border border-light px-3 py-2">
                        <small class="mb-1">
                            شماره تماس:
                        </small>
                        <label class="mb-1">
                            <?= StringUtil::toPersian(trim($order['receiver_mobile'])); ?>
                        </label>
                    </div>
                <?php endif; ?>

                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        استان:
                    </small>
                    <label class="mb-1">
                        <?= $order['province']; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        شهر:
                    </small>
                    <label class="mb-1">
                        <?= $order['city']; ?>
                    </label>
                </div>
                <div class="col-lg-6 border border-light px-3 py-2">
                    <small class="mb-1">
                        کد پستی:
                    </small>
                    <label class="mb-1">
                        <?= $order['postal_code']; ?>
                    </label>
                </div>
                <div class="col-lg-12 border border-light px-3 py-2 text-center">
                    <label class="m-0">
                        <?= $order['address']; ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!-- /receiver info -->

    <!-- Order items -->
    <div class="dashboard_content">
        <div class="card">
            <div class="card-header">
                <h3>آیتم‌های سفارش</h3>
            </div>
            <?php if (count($order_items)): ?>
                <?php $k = 0; ?>
                <?php foreach ($order_items as $item): ?>
                    <div class="card-body position-relative <?= 0 != $k++ ? 'border-top' : ''; ?>">
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

                                        <?php if ($item['separate_consignment'] == DB_YES): ?>
                                            <div>
                                                <span class="badge badge-info px-2 py-1 mx-1">مرسوله مجزا</span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($item['is_returned'] == DB_YES): ?>
                                            <div>
                                                <span class="badge badge-danger px-2 py-1 mx-1">مرجوع شده</span>
                                            </div>
                                        <?php elseif (!empty($item['order_item_id'])): ?>
                                            <div>
                                                <span class="badge badge-warning px-2 py-1">درخواست مرجوع</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($item['color']) && ($item['show_color'] == DB_YES || $item['is_patterned_color'] == DB_YES)): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="product_color_badge">
                                                <?php if ($item['is_patterned_color'] == DB_NO): ?>
                                                    <span class="mr-2"
                                                          style="background-color: <?= $item['color']; ?>;"></span>
                                                <?php endif; ?>
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
            <?php else: ?>
                <?php load_partial('main/not-found-rows', ['show_border' => false]); ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- /order items -->
<?php else: ?>
    <div class="d-flex align-items-xl-center alert alert-danger">
        <i class="fas fa-times-circle text-danger" style="font-size: 2rem;"></i>
        <h4 class="ml-4 mb-0">
            وضعیت پرداخت نامشخص می‌باشد!
        </h4>
    </div>
<?php endif; ?>
