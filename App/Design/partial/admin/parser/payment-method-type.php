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
    <?php case METHOD_TYPE_WALLET: ?>
        کیف پول
        <?php break; ?>
    <?php case METHOD_TYPE_IN_PLACE: ?>
        درب منزل
        <?php break; ?>
    <?php case METHOD_TYPE_RECEIPT: ?>
        رسید پرداخت
        <?php break; ?>
    <?php default: ?>
        سایر
        <?php break; ?>
    <?php endswitch; ?>
