<?php switch ($type):
    case RETURN_ORDER_STATUS_CHECKING: ?>
        <span class="badge badge-primary"><?= RETURN_ORDER_STATUSES[RETURN_ORDER_STATUS_CHECKING]; ?></span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_ACCEPT: ?>
        <span class="badge badge-success"><?= RETURN_ORDER_STATUSES[RETURN_ORDER_STATUS_ACCEPT]; ?></span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_DENIED: ?>
        <span class="badge badge-danger"><?= RETURN_ORDER_STATUSES[RETURN_ORDER_STATUS_DENIED]; ?></span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_SENDING: ?>
        <span class="badge badge-warning">در حال ارسال مرسوله</span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_RECEIVED: ?>
        <span class="badge badge-success"><?= RETURN_ORDER_STATUSES[RETURN_ORDER_STATUS_RECEIVED]; ?></span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_MONEY_RETURNED: ?>
        <span class="badge badge-info"><?= RETURN_ORDER_STATUSES[RETURN_ORDER_STATUS_MONEY_RETURNED]; ?></span>
        <?php break; ?>
    <?php default: ?>
        نامشخص
        <?php break; ?>
    <?php endswitch; ?>
