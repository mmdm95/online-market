<div class="text-center">
    <div class="list-icons">
        <div class="dropdown">
            <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <a href="<?= url('admin.comment.detail', ['p_id' => $row['product_id'], 'id' => $row['id']]); ?>"
                   class="dropdown-item text-info">
                    <i class="icon-comment-discussion"></i>
                    مشاهده
                </a>
                <a href="javascript:void(0);" data-remove-url="<?= url('ajax.comment.remove'); ?>"
                   data-remove-id="<?= $row['id']; ?>"
                   class="dropdown-item text-danger __item_remover_btn">
                    <i class="icon-trash"></i>
                    حذف
                </a>
            </div>
        </div>
    </div>
</div>