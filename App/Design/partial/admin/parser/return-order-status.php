<?php switch ($type):
    case RETURN_ORDER_STATUS_CHECKING: ?>
        <span class="badge badge-primary">در حال بررسی</span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_ACCEPT: ?>
        <span class="badge badge-success">تایید شده</span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_DENIED: ?>
        <span class="badge badge-danger">تایید نشده</span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_SENDING: ?>
        <span class="badge badge-warning">در حال ارسال مرسوله</span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_RECEIVED: ?>
        <span class="badge badge-success">دریافت کالای مرجوعی</span>
        <?php break; ?>
    <?php case RETURN_ORDER_STATUS_MONEY_RETURNED: ?>
        <span class="badge badge-info">بازگشت مبلغ کالاها</span>
        <?php break; ?>
    <?php default: ?>
        نامشخص
        <?php break; ?>
    <?php endswitch; ?>
