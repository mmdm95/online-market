<?php

namespace App\Logic\Utils;

use Sim\Payment\Factories\Sadad;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\Sadad\SadadRequestProvider;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;

class PaymentUtil
{
    /**
     * {@inheritdoc}
     */
    public static function getGatewayInfo($gatewayType, $gatewayInfo): array
    {
        /**
         * @var Sadad $gateway
         */
        // $key, $merchantId, $terminalId
        $gateway = PaymentFactory::instance(PaymentFactory::GATEWAY_SADAD, ...$gatewayInfo);
        // provider
        $provider = new SadadRequestProvider();
        $provider
            ->setReturnUrl(url('pay.test')->getRelativeUrlTrimmed())
            ->setAmount(10000)
            ->setOrderId(1);
        // events
        $gateway->createRequestOkClosure(function (SadadRequestResultProvider $result) {
            // store gateway info in session to store all order things at once
            session()->setFlash(SESSION_GATEWAY_INFO, $result);
        });
        //
        $gateway->createRequest($provider);
    }

    /**
     * {@inheritdoc}
     */
    public static function getResultProvider()
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function getAdviceProvider()
    {

    }
}
