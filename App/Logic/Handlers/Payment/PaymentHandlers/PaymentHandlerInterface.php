<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerConnectionOutput;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Handlers\Payment\PaymentHandlerResultOutput;

interface PaymentHandlerInterface
{
    /**
     * @param PaymentHandlerConnectionInput $input
     * @return PaymentHandlerConnectionOutput
     */
    public function connection(PaymentHandlerConnectionInput $input): PaymentHandlerConnectionOutput;

    /**
     * @param PaymentHandlerResultInput $input
     * @return PaymentHandlerResultOutput
     */
    public function result(PaymentHandlerResultInput $input): PaymentHandlerResultOutput;
}
