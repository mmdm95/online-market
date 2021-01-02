<?php

use Sim\Auth\DBAuth;

/**
 * @var DBAuth $auth
 */
$auth = container()->get('auth_admin');
?>

<div class="list-icons">
    <div class="dropdown">
        <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="<?= url('admin.order.view') . $row['id']; ?>" class="dropdown-item">
                <i class="icon-eye"></i>
                مشاهده جزئیات
            </a>
            <?php if ($auth->hasRole(ROLE_DEVELOPER) || $auth->hasRole(ROLE_SUPER_USER)): ?>
                <a href="javascript:void(0);" data-remove-url="<?= url('ajax.user.order.remove'); ?>"
                   data-remove-id="<?= $row['id']; ?>"
                   class="dropdown-item text-danger __item_remover_btn">
                    <i class="icon-trash"></i>
                    حذف
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>