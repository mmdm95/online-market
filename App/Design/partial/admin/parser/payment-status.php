<?php if ($status ?? false): ?>
    <?php $extra_padding = $extra_padding ?? false; ?>
    <?php if (PAYMENT_STATUS_SUCCESS == $status): ?>
        <span class="badge badge-success <?= $extra_padding ? 'p-2' : ''; ?>"><?= PAYMENT_STATUSES[PAYMENT_STATUS_SUCCESS]; ?></span>
    <?php elseif (PAYMENT_STATUS_NOT_PAYED == $status): ?>
        <span class="badge badge-danger <?= $extra_padding ? 'p-2' : ''; ?>"><?= PAYMENT_STATUSES[PAYMENT_STATUS_NOT_PAYED]; ?></span>
    <?php elseif (PAYMENT_STATUS_WAIT == $status): ?>
        <span class="badge badge-primary <?= $extra_padding ? 'p-2' : ''; ?>"><?= PAYMENT_STATUSES[PAYMENT_STATUS_WAIT]; ?></span>
    <?php elseif (PAYMENT_STATUS_WAIT_VERIFY == $status): ?>
        <span class="badge badge-warning <?= $extra_padding ? 'p-2' : ''; ?>"><?= PAYMENT_STATUSES[PAYMENT_STATUS_WAIT_VERIFY]; ?></span>
    <?php elseif (PAYMENT_STATUS_FAILED == $status): ?>
        <span class="badge badge-danger <?= $extra_padding ? 'p-2' : ''; ?>"><?= PAYMENT_STATUSES[PAYMENT_STATUS_FAILED]; ?></span>
    <?php else: ?>
        <span class="badge badge-dark <?= $extra_padding ? 'p-2' : ''; ?>">نامشخص</span>
    <?php endif; ?>
<?php endif; ?>