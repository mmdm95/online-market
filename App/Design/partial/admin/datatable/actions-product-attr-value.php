<div class="text-center">
    <div class="list-icons">
        <div class="dropdown">
            <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <a href="javascript:void(0);"
                   data-edit-id="<?= $row['id']; ?>"
                   class="dropdown-item __item_attr_val_editor_btn"
                   data-toggle="modal"
                   data-target="#modal_form_edit_attr_val">
                    <i class="icon-pencil"></i>
                    ویرایش
                </a>
                <a href="javascript:void(0);" data-remove-url="<?= url('ajax.product.attr.value.remove'); ?>"
                   data-remove-id="<?= $row['id']; ?>"
                   class="dropdown-item text-danger __item_remover_btn">
                    <i class="icon-trash"></i>
                    حذف
                </a>
            </div>
        </div>
    </div>
</div>