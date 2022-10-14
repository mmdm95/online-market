<?php

use App\Logic\Models\WalletFlowModel;

include_once __DIR__ . '/bootstrap.php';

// do your job thing here

/**
 * @var WalletFlowModel $walletFlowModel
 */
$walletFlowModel = container()->get(WalletFlowModel::class);

$walletFlowModel->removeUnwantedWalletCharges();
