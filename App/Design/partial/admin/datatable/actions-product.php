<div class="text-center">
    <div class="list-icons">
        <div class="dropdown">
            <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <a href="<?= url('admin.product.edit', ['id' => $row['id']]); ?>"
                   class="dropdown-item">
                    <i class="icon-pencil"></i>
                    ویرایش
                </a>
                <a href="javascript:void(0);" data-remove-url="<?= url('ajax.product.remove'); ?>"
                   data-remove-id="<?= $row['id']; ?>"
                   class="dropdown-item text-danger __item_remover_btn">
                    <i class="icon-trash"></i>
                    حذف
                </a>
            </div>
        </div>
    </div>
</div>