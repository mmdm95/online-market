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
    public static function send(array $numbers, string $body): bool
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

    /**
     * @param array $numbers
     * @param string $body
     * @param $status
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
        $status,
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

        $arr = [
            'sms_panel_number' => config()->get('sms.niaz.from') ?: 'unknown',
            'sms_panel_name' => $sms->getPanelName(),
            'type' => $type,
            'status' => $status,
            'message' => $body,
            'numbers' => implode(',', $numbers),
            'sender' => $senderType,
            'sent_at' => time(),
        ];

        if (SMS_LOG_SENDER_USER == $senderType && !is_null($auth)) {
            $arr['sent_by'] = $auth->getCurrentUser()['id'];
        }

        $smsModel->insert($arr);
    }
}