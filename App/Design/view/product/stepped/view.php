<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="card">
        <?php load_partial('admin/card-header', ['header_title' => 'قیمت‌های پلکانی']); ?>

        <div class="card-body">
            با استفاده از ستون عملیات می‌توانید اقدام به حذف، ویرایش و مشاهده قیمت‌های پلکانی کنید.
        </div>

        <table class="table table-bordered table-hover datatable-highlight">
            <thead>
            <tr>
                <th>#</th>
                <th>تعداد در انبار</th>
                <th>تعداد مجاز در سبد خرید</th>
                <th>رنگ</th>
                <th>سایز</th>
                <th>گارانتی</th>
                <th>قیمت</th>
                <th>قیمت با تخفیف</th>
                <th>تخفیف تا تاریخ</th>
                <th>وضعیت موجودی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $item): ?>
                <tr>
                    <td><?= $item['id']; ?></td>
                    <td><?= $item['stock_count']; ?></td>
                    <td><?= $item['max_cart_count']; ?></td>
                    <td>
                        <?php if (!empty($item['color']) && ($item['show_color'] === DB_YES || $item['is_patterned_color'] === DB_YES)): ?>
                            <?php if ($item['is_patterned_color'] === DB_NO): ?>
                                <?php load_partial('admin/parser/color-shape', ['hex' => $item['color_hex']]); ?>
                            <?php endif; ?>
                            <span class="ml-2"><?= $item['color_name']; ?></span>
                        <?php else: ?>
                            <?php load_partial('admin/parser/dash-icon'); ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $item['size']; ?></td>
                    <td><?= $item['guarantee']; ?></td>
                    <td data-order="<?= (int)StringUtil::toEnglish($item['price']); ?>">
                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['price']))); ?>
                    </td>
                    <td data-order="<?= (int)StringUtil::toEnglish($item['discounted_price']); ?>">
                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['discounted_price']))); ?>
                    </td>

                    <td data-order="<?= (int)$item['discount_from']; ?>">
                        <?php if (isset($item['discount_from'])): ?>
                            <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $item['discount_from']); ?>
                        <?php else: ?>
                            <?php load_partial('admin/parser/dash-icon'); ?>
                        <?php endif; ?>
                    </td>

                    <td data-order="<?= (int)$item['discount_until']; ?>">
                        <?php if (isset($item['discount_until'])): ?>
                            <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $item['discount_until']); ?>
                        <?php else: ?>
                            <?php load_partial('admin/parser/dash-icon'); ?>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php load_partial('admin/parser/active-status', [
                            'status' => $item['is_available'],
                            'active' => 'موجود',
                            'deactive' => 'ناموجود',
                        ]); ?>
                    </td>
                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left">
                                    <a href="<?= url('admin.stepped-price.view_all', ['code' => $item['code']]); ?>"
                                       class="dropdown-item">
                                        <i class="icon-pencil"></i>
                                        ویرایش
                                    </a>
                                    <a href="javascript:void(0);"
                                       data-remove-url="<?= url('ajax.product.stepped.remove.all'); ?>"
                                       data-remove-id="<?= $item['code']; ?>"
                                       class="dropdown-item text-danger __item_remover_btn">
                                        <i class="icon-trash"></i>
                                        حذف تمام قیمت‌های پلکانی این محصول
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th>تعداد در انبار</th>
                <th>تعداد مجاز در سبد خرید</th>
                <th>رنگ</th>
                <th>سایز</th>
                <th>گارانتی</th>
                <th>قیمت</th>
                <th>قیمت با تخفیف</th>
                <th>تخفیف تا تاریخ</th>
                <th>وضعیت موجودی</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
