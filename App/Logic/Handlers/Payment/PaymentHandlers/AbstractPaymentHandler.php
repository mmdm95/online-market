<?php

namespace App\Logic\Handlers\Payment\PaymentHandlers;

use App\Logic\Models\GatewayModel;
use App\Logic\Models\OrderPaymentModel;

class AbstractPaymentHandler
{
    /**
     * @var GatewayModel
     */
    protected $gatewayModel = null;

    /**
     * @var OrderPaymentModel
     */
    protected $orderPayModel = null;

    /**
     * @var array
     */
    protected array $credentials = [];

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->gatewayModel = container()->get(GatewayModel::class);
        $this->orderPayModel = container()->get(OrderPaymentModel::class);
    }

    /**
     * @param $type
     * @param $code
     * @param $msg
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected static function logConnectionError($type, $code, $msg)
    {
        logger_gateway()->error([
            'section' => 'product',
            'gateway_type' => $type,
            'code' => $code,
            'message' => $msg,
        ]);
    }
}
