<div class="list-icons">
    <div class="dropdown">
        <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="<?= url('admin.user.view') . $row['id']; ?>" class="dropdown-item"><i class="icon-pencil"></i>ویرایش</a>
            <a href="javascript:void(0);" data-remove-url="<?= url('admin.user.remove'); ?>"
               data-remove-id="<?= $row['id']; ?>"
               class="dropdown-item __item_remover_btn"><i class="icon-trash"></i>حذف</a>
            <a href="#" class="dropdown-item"><i class="icon-cart"></i>خریدها</a>
        </div>
    </div>
</div>