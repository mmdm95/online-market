<?php

namespace App\Logic\Interfaces;

interface IPaymentUtil
{
    public function getResultProvider();

    public function getAdviceProvider();
}
