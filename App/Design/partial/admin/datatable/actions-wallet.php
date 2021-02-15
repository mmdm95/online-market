<div class="text-center">
    <div class="list-icons">
        <div class="dropdown">
            <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <a href="<?= url('admin.wallet.charge') . $row['id']; ?>" class="dropdown-item">
                    <i class="icon-stack-plus"></i>
                    شارژ کیف پول
                </a>
                <a href="<?= url('admin.wallet.detail') . $row['id']; ?>" class="dropdown-item">
                    <i class="icon-eye"></i>
                    مشاهده جزئیات
                </a>
                <a href="javascript:void(0);" data-remove-url="<?= url('ajax.wallet.remove'); ?>"
                   data-remove-id="<?= $row['id']; ?>"
                   class="dropdown-item text-danger __item_remover_btn">
                    <i class="icon-trash"></i>
                    حذف
                </a>
            </div>
        </div>
    </div>
</div>