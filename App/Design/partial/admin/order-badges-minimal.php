<?php

use Sim\Utils\StringUtil;

$authAdmin = auth_admin();
$allowOrder = $authAdmin->isAllow(RESOURCE_ORDER, OWN_PERMISSION_READ);

?>

<?php if ($allowOrder && count($order_badges_count)): ?>
    <div class="d-flex flex-wrap justify-content-center">
        <?php foreach ($order_badges_count as $value): ?>
            <div class="card mx-2" style="background-color: <?= $value['color']; ?>;">
                <a href="<?= url('admin.order.view', ['status' => $value['code']])->getRelativeUrlTrimmed(); ?>"
                   style="color: <?= get_color_from_bg($value['color']); ?>; !important;"
                   class="card-body py-1" data-popup="tooltip" data-title="<?= $value['title']; ?>">
                    <div class="d-flex align-items-center">
                        <h3 class="font-weight-semibold mb-0">
                            <?= StringUtil::toPersian($value['count']); ?>
                        </h3>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>