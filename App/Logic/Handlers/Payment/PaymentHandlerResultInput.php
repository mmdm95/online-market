<?php

namespace App\Logic\Handlers\Payment;

class PaymentHandlerResultInput
{
    /**
     * @var string
     */
    protected string $gatewayCode;

    /**
     * @var string
     */
    protected string $orderCode;

    /**
     * @var float
     */
    protected $price;

    public function __construct(string $gatewayCode, string $orderCode, float $price)
    {
        $this->gatewayCode = $gatewayCode;
        $this->orderCode = $orderCode;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getGatewayCode(): string
    {
        return $this->gatewayCode;
    }

    /**
     * @return string
     */
    public function getOrderCode(): string
    {
        return $this->orderCode;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}
