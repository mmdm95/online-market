<div class="text-center">
    <div class="list-icons">
        <div class="dropdown">
            <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <a href="javascript:void(0);"
                   data-edit-id="<?= $row['id']; ?>"
                   class="dropdown-item __item_unit_editor_btn"
                   data-toggle="modal"
                   data-target="#modal_form_edit_unit">
                    <i class="icon-pencil"></i>
                    ویرایش
                </a>
                <?php if (DB_YES == $row['deletable']): ?>
                    <a href="javascript:void(0);"
                       data-remove-url="<?= url('ajax.unit.remove'); ?>"
                       data-remove-id="<?= $row['id']; ?>"
                       class="dropdown-item text-danger __item_remover_btn">
                        <i class="icon-trash"></i>
                        حذف
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>