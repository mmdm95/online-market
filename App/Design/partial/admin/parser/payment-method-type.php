<?php switch ($type):
    case METHOD_TYPE_GATEWAY_BEH_PARDAKHT: ?>
        به پرداخت
        <?php break; ?>
    <?php case METHOD_TYPE_GATEWAY_IDPAY: ?>
        آی‌دی پی
        <?php break; ?>
    <?php case METHOD_TYPE_GATEWAY_MABNA: ?>
        مبنا
        <?php break; ?>
    <?php case METHOD_TYPE_GATEWAY_ZARINPAL: ?>
        زرین پال
        <?php break; ?>
    <?php default: ?>
        سایر
        <?php break; ?>
    <?php endswitch; ?>
