<?php

$auth = auth_admin();

?>

<div class="text-center">
    <ul class="list-unstyled icons-list m-0">
        <li class="mb-2">
            <a href="<?= url('admin.order.detail') . $row['id']; ?>"
               class="text-dark text-nowrap">
                <i class="icon-eye"></i>
                مشاهده جزئیات
            </a>
        </li>
        <li>
            <?php if ($auth->userHasRole(ROLE_DEVELOPER)): ?>
                <a href="javascript:void(0);" data-remove-url="<?= url('ajax.user.order.remove'); ?>"
                   data-remove-id="<?= $row['id']; ?>"
                   class="text-danger __item_remover_btn">
                    <i class="icon-trash"></i>
                    حذف
                </a>
            <?php endif; ?>
        </li>
    </ul>
</div>