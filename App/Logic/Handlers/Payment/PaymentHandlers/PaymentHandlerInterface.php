<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Handlers\Payment\PaymentHandlerConnectionInput;
use App\Logic\Handlers\Payment\PaymentHandlerConnectionOutput;
use App\Logic\Handlers\Payment\PaymentHandlerResultInput;
use App\Logic\Handlers\Payment\PaymentHandlerResultOutput;
use Closure;

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

    /**
     * @param Closure $closure
     * @return static
     */
    public function onSuccessConnectionEvent(Closure $closure);

    /**
     * @param Closure $closure
     * @return static
     */
    public function onFailedConnectionEvent(Closure $closure);

    /**
     * @param Closure $closure
     * @return static
     */
    public function onSuccessResultEvent(Closure $closure);

    /**
     * @param Closure $closure
     * @return static
     */
    public function onFailedResultEvent(Closure $closure);
}
