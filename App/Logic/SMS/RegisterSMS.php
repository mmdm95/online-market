<?php

namespace App\Logic\SMS;

use App\Logic\Interfaces\ICustomSMS as ISMS;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\SMS\Exceptions\SMSException;
use Sim\SMS\Factories\NiazPardaz;
use Sim\SMS\MessageProvider;

class RegisterSMS implements ISMS
{
    /**
     * {@inheritdoc}
     * @param array $numbers
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws SMSException
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