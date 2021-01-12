<?php if ($row['image'] && is_image_exists($row['image'])): ?>
    <button type="button" class="btn btn-success __item_cat_img_editor_btn"
            data-toggle="modal"
            data-target="#modal_form_edit_cat_img"
            data-edit-category-id="<?= $row['category_id']; ?>"
            data-edit-id="<?= $row['id']; ?>">
        ویرایش تصویر
    </button>
    <button type="button" class="btn-outline-danger __item_remover_btn"
            data-remove-url="<?= url('ajax.unit.remove'); ?>"
            data-remove-id="<?= $row['id']; ?>">
        حذف تصویر
    </button>
<?php else: ?>
    <button type="button" class="btn btn-primary __item_cat_img_add_btn"
            data-toggle="modal"
            data-target="#modal_form_add_cat_img"
            data-add-category-id="<?= $row['id']; ?>">
        افزودن تصویر
    </button>
<?php endif; ?>