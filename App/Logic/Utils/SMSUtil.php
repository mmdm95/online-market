<?php

namespace App\Logic\Utils;

use App\Logic\Interfaces\ICustomSMS as ISMS;
use App\Logic\Models\SMSLogsModel;
use Sim\SMS\Abstracts\AbstractSMS;
use Sim\SMS\Exceptions\SMSException;
use Sim\SMS\Factories\NiazPardaz;
use Sim\SMS\MessageProvider;

class SMSUtil implements ISMS
{
    /**
     * {@inheritdoc}
     * @throws SMSException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function send(array $numbers, string $body)
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

        return $sms;
    }

    /**
     * @param array $numbers
     * @param string $body
     * @param AbstractSMS $smsFactory
     * @param $type
     * @param $senderType
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function logSMS(
        array $numbers,
        string $body,
        AbstractSMS $smsFactory,
        $type,
        $senderType
    )
    {
        $auth = auth_admin()->isLoggedIn() ? auth_admin() : (auth_home()->isLoggedIn() ? auth_home() : null);

        /**
         * @var AbstractSMS $sms
         */
        $sms = container()->get('sms_panel');
        /**
         * @var SMSLogsModel $smsModel
         */
        $smsModel = container()->get(SMSLogsModel::class);

        // contains [code] and [message]
        $status = $smsFactory->getStatus();

        $arr = [
            'sms_panel_number' => config()->get('sms.niaz.from') ?: 'unknown',
            'sms_panel_name' => $sms->getPanelName(),
            'type' => $type,
            'status' => $smsFactory->isSuccessful(),
            'message' => $body,
            'numbers' => implode(',', $numbers),
            'code' => $status['code'] ?? null,
            'result_msg' => $status['message'] ?? '',
            'sender' => $senderType,
            'sent_at' => time(),
        ];

        if (SMS_LOG_SENDER_USER == $senderType && !is_null($auth)) {
            $arr['sent_by'] = $auth->getCurrentUser()['id'];
        }

        $smsModel->insert($arr);
    }
}
