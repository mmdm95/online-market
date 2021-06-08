<?php

namespace App\Logic\SMS;

use App\Logic\Interfaces\ICustomSMS as ISMS;
use Sim\SMS\Exceptions\SMSException;
use Sim\SMS\Factories\NiazPardaz;
use Sim\SMS\MessageProvider;

class CommonSMS implements ISMS
{
    /**
     * {@inheritdoc}
     * @param array $numbers
     * @param string $body
     * @return bool
     * @throws SMSException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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