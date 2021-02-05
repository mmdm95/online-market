<?php if (($status ?? false) && DB_YES == $status): ?>
    <span class="badge badge-success">
        <?= $active ?? 'فعال' ?>
    </span>
<?php else: ?>
    <span class="badge badge-danger">
        <?= $deactive ?? 'غیر فعال'; ?>
    </span>
<?php endif; ?>