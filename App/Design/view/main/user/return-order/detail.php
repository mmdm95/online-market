<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- Return order info -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>اطلاعات مرجوع</h3>
        </div>
        <div class="row m-0">
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    کد درخواست مرجوع:
                </small>
                <label class="mb-1">
                    <span class="en-font"><?= $return_order['code']; ?></span>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    تاریخ درخواست:
                </small>
                <label class="mb-1">
                    <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $return_order['requested_at']); ?>
                </label>
            </div>
            <div class="col-lg-6 border border-light px-3 py-2">
                <small class="mb-1">
                    وضعیت مرجوع:
                </small>
                <label class="mb-1">
                    <?php load_partial('admin/parser/return-order-status', ['type' => $return_order['status']]); ?>
                </label>
            </div>
            <div class="col-lg-12 border border-light px-3 py-2">
                <small class="mb-1">
                    علت مرجوع:
                </small>
                <p class="m-1">
                    <?= $return_order['desc']; ?>
                </p>
            </div>
            <?php if (!empty(trim($return_order['respond']))): ?>
                <div class="col-lg-12 border border-light px-3 py-2 text-center">
                    <small class="mb-1">
                        نتیجه:
                    </small>
                    <p class="m-0">
                        <?= $return_order['respond']; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- /return order info -->

<!-- Return order items -->
<div class="dashboard_content">
    <div class="card">
        <div class="card-header">
            <h3>آیتم‌های مرجوعی</h3>
        </div>
        <?php if (count($return_order_items)): ?>
            <?php $k = 0; ?>
            <?php foreach ($return_order_items as $item): ?>
                <div class="card-body <?= 0 != $k++ ? 'border-top' : ''; ?>">
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

                                    <?php if (!empty($item['order_item_id'])): ?>
                                        <div>
                                            <?php if (DB_YES == $item['is_returned']): ?>
                                                <span class="badge badge-danger px-2 py-1">
                                                        تعداد
                                                        <?= local_number($item['return_count']); ?>
                                                    <?= $item['unit_title']; ?>
                                                        مرجوع شده
                                                    </span>
                                            <?php else: ?>
                                                <span class="badge badge-info px-2 py-1">
                                                        درخواست مرجوع تعداد
                                                        <?= local_number($item['return_count']); ?>
                                                    <?= $item['unit_title']; ?>
                                                    </span>
                                            <?php endif; ?>
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
<!-- /return order items  -->