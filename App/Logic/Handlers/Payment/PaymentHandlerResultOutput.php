<?php

namespace App\Logic\Handlers\Payment;

class PaymentHandlerResultOutput
{
    /**
     * @var bool
     */
    protected bool $result;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var string|null
     */
    protected ?string $referenceId;

    public function __construct(bool $result, string $message, ?string $referenceId)
    {
        $this->result = $result;
        $this->message = $message;
        $this->referenceId = $referenceId;
    }

    /**
     * @return bool
     */
    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }
}
