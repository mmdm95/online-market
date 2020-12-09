<?php

namespace App\Logic\SMS;

use App\Logic\Interfaces\ICustomSMS as ISMS;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\SMS\Exceptions\SMSException;
use Sim\SMS\Factories\NiazPardaz;
use Sim\SMS\MessageProvider;

class CommonSMS implements ISMS
{
    /**
     * {@inheritdoc}
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws SMSException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function send(array $numbers, string $body): bool
    {
        /**
         * @var NiazPardaz $sms
         */
        $sms = container()->get('sms_panel');
        $provider = new MessageProvider();

        $provider
            ->setNumbers($numbers)
            ->withBody($body);

        $sms->send($provider);

        return $sms->isSuccessful();
    }
}