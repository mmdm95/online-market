<?php if ($status ?? false): ?>
    <?php if (PAYMENT_STATUS_SUCCESS == $status): ?>
        <span class="badge badge-success"><?= PAYMENT_STATUSES[PAYMENT_STATUS_SUCCESS]; ?></span>
    <?php elseif (PAYMENT_STATUS_NOT_PAYED == $status): ?>
        <span class="badge badge-danger"><?= PAYMENT_STATUSES[PAYMENT_STATUS_NOT_PAYED]; ?></span>
    <?php elseif (PAYMENT_STATUS_WAIT == $status): ?>
        <span class="badge badge-warning"><?= PAYMENT_STATUSES[PAYMENT_STATUS_WAIT]; ?></span>
    <?php elseif (PAYMENT_STATUS_WAIT_VERIFY == $status): ?>
        <span class="badge bg-orange-400"><?= PAYMENT_STATUSES[PAYMENT_STATUS_WAIT_VERIFY]; ?></span>
    <?php else: ?>
        <span class="badge badge-danger"><?= PAYMENT_STATUSES[PAYMENT_STATUS_FAILED]; ?></span>
    <?php endif; ?>
<?php endif; ?>