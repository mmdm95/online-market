<?php

use App\Logic\Models\OrderModel;

include_once __DIR__ . '/bootstrap.php';

// do your job thing here

/**
 * @var OrderModel $orderModel
 */
$orderModel = container()->get(OrderModel::class);

$unwantedOrders = $orderModel->get(['code'], 'must_delete_later=:mdl', ['mdl' => DB_YES]);
foreach ($unwantedOrders as $order) {
    try {
        $orderModel->removeIssuedFactor($order['code']);
    } catch (\DI\DependencyException|\DI\NotFoundException  $e) {
        // do nothing or now
    }
}
