<?php

namespace App\Logic\Handlers\Payment;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class PaymentHandlerConnectionOutput
{
    /**
     * @var AbstractBaseParameterProvider|null
     */
    protected ?AbstractBaseParameterProvider $connRespnse;

    /**
     * @var bool
     */
    protected bool $connResult;

    public function __construct(?AbstractBaseParameterProvider $connectionResponse, bool $connectionResult)
    {
        $this->connRespnse = $connectionResponse;
        $this->connResult = $connectionResult;
    }

    /**
     * @return AbstractBaseParameterProvider|null
     */
    public function getConnectionResponse(): ?AbstractBaseParameterProvider
    {
        return $this->connRespnse;
    }

    /**
     * @return bool
     */
    public function getConnectionResult(): bool
    {
        return $this->connResult;
    }
}
