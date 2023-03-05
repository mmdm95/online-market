<?php

use Sim\Utils\StringUtil;

$formattedPrice = local_number(number_format((int)StringUtil::toEnglish(abs($price))));

?>

<?php if ($price > 0): ?>
    <span class="text-success">
        <span class="icon-arrow-up5" aria-hidden="true"></span>
        <?= $formattedPrice; ?>
    </span>
<?php else: ?>
    <span class="ltr text-danger">
        <span class="icon-arrow-down5" aria-hidden="true"></span>
        <?= $formattedPrice; ?>
        -
    </span>
<?php endif; ?>