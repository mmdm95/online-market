<?php

namespace App\Logic\Handlers\Payment;

use App\Logic\Handlers\Payment\PaymentHandlers\BehPardakhtPaymentHandler;
use App\Logic\Handlers\Payment\PaymentHandlers\IDPayPaymentHandler;
use App\Logic\Handlers\Payment\PaymentHandlers\IranKishPaymentHandler;
use App\Logic\Handlers\Payment\PaymentHandlers\MabnaPaymentHandler;
use App\Logic\Handlers\Payment\PaymentHandlers\NullPaymentHandler;
use App\Logic\Handlers\Payment\PaymentHandlers\PaymentHandlerInterface;
use App\Logic\Handlers\Payment\PaymentHandlers\SadadPaymentHandler;
use App\Logic\Handlers\Payment\PaymentHandlers\TapPaymentHandler;
use App\Logic\Handlers\Payment\PaymentHandlers\ZarinpalPaymentHandler;

class PaymentHandlerFactory
{
    /**
     * @param string $gatewayType
     * @param array $credentials
     * @return PaymentHandlerInterface
     */
    public static function getInstance(string $gatewayType, array $credentials): PaymentHandlerInterface
    {
        switch ($gatewayType) {
            case METHOD_TYPE_GATEWAY_BEH_PARDAKHT:
                return new BehPardakhtPaymentHandler($credentials);
            case METHOD_TYPE_GATEWAY_IDPAY:
                return new IDPayPaymentHandler($credentials);
            case METHOD_TYPE_GATEWAY_MABNA:
                return new MabnaPaymentHandler($credentials);
            case METHOD_TYPE_GATEWAY_ZARINPAL:
                return new ZarinpalPaymentHandler($credentials);
            case METHOD_TYPE_GATEWAY_SADAD:
                return new SadadPaymentHandler($credentials);
            case METHOD_TYPE_GATEWAY_TAP:
                return new TapPaymentHandler($credentials);
            case METHOD_TYPE_GATEWAY_IRAN_KISH:
                return new IranKishPaymentHandler($credentials);
            default:
                return new NullPaymentHandler($credentials);
        }
    }
}
