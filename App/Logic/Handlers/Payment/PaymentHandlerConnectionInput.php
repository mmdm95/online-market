<?php

namespace App\Logic\Handlers\Payment;

class PaymentHandlerConnectionInput
{
    /**
     * @var int
     */
    protected int $userId = 0;

    /**
     * @var string
     */
    protected string $orderCode;

    /**
     * @var float
     */
    protected float $price;

    /**
     * @var string
     */
    protected string $callbackUrl;

    /**
     * This is a unique string for gateway to don't pay twice
     *
     * @var string
     */
    protected string $uniqueCode;

    public function __construct(
        int    $userId,
        string $orderCode,
        float  $price,
        string $callbackUrl,
        string $uniqueCode
    )
    {
        $this->userId = $userId;
        $this->orderCode = $orderCode;
        $this->price = $price;
        $this->callbackUrl = $callbackUrl;
        $this->uniqueCode = $uniqueCode;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return max($this->userId, 0);
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

    /**
     * @return string
     */
    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    /**
     * @return string
     */
    public function getUniqueCode(): string
    {
        return $this->uniqueCode;
    }
}
