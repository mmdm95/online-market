<?php if (($status ?? false) && DB_YES == $status): ?>
    <span class="badge badge-success">فعال</span>
<?php else: ?>
    <span class="badge badge-danger">غیر فعال</span>
<?php endif; ?>