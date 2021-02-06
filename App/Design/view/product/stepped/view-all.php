<?php

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
                <th>حداقل تعداد در سبد خرید</th>
                <th>حداکثر تعداد در سبد خرید</th>
                <th>قیمت</th>
                <th>قیمت با تخفیف</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $item): ?>
                <tr>
                    <td><?= $item['id']; ?></td>
                    <td><?= $item['min_count']; ?></td>
                    <td><?= $item['max_count']; ?></td>
                    <td>
                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['price']))); ?>
                    </td>
                    <td>
                        <?= StringUtil::toPersian(number_format(StringUtil::toEnglish($item['discounted_price']))); ?>
                    </td>
                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left">
                                    <a href="<?= url('admin.stepped-price.edit', ['code' => '', 'id' => '']); ?>"
                                       class="dropdown-item">
                                        <i class="icon-pencil"></i>
                                        ویرایش
                                    </a>
                                    <a href="javascript:void(0);"
                                       data-remove-url="<?= url('ajax.product.stepped.remove'); ?>"
                                       data-remove-id="<?= $item['id']; ?>"
                                       class="dropdown-item text-danger __item_remover_btn">
                                        <i class="icon-trash"></i>
                                        حذف
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
                <th>حداقل تعداد در سبد خرید</th>
                <th>حداکثر تعداد در سبد خرید</th>
                <th>قیمت</th>
                <th>قیمت با تخفیف</th>
                <th class="text-center">عملیات</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
