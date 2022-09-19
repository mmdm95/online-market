<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Models\GatewayModel;
use App\Logic\Utils\PaymentUtil;
use App\Logic\Utils\SMSUtil;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;

class OrderResultController extends AbstractHomeController
{
    /**
     * @param $type
     * @param $method
     * @param $code
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     * @throws \Sim\SMS\Exceptions\SMSException
     */
    public function index($type, $method, $code)
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/order/order-completed');

        [$res, $msg, $paymentCode] = PaymentUtil::getResultProvider($type, $method, $code);

        if ($res) {
            /**
             * @var GatewayModel $gatewayModel
             */
            $gatewayModel = container()->get(GatewayModel::class);
            $info = $gatewayModel->getFirstUserAndOrderInfoFromGatewayFlowCode($code);
            $username = $info['username'] ?? null;
            $orderCode = $info['code'] ?? null;
            if (!is_null($username) && !is_null($orderCode)) {
                // send success order sms
                $body = replaced_sms_body(SMS_TYPE_BUY, [
                    SMS_REPLACEMENTS['mobile'] => $username,
                    SMS_REPLACEMENTS['orderCode'] => $orderCode,
                ]);
                $smsRes = SMSUtil::send([$username], $body);
                SMSUtil::logSMS([$username], $body, $smsRes, SMS_LOG_TYPE_BUY, SMS_LOG_SENDER_SYSTEM);

                // alert user(s) that an order is in queue
                $body = 'سلام مدیر' .
                    "\n" .
                    'سفارش ' . $orderCode . ' ثبت شده است و هم اکنون در وضعیت ' . $info['send_status_title'] . ' می باشد.' .
                    "\n" .
                    'آیتم های سفارش : ' . '[مراجعه به سایت]' .
                    "\n" .
                    'مبلغ سفارش : ' . local_number(number_format(StringUtil::toEnglish($info['final_price']))) . ' تومان';
                $users = config()->get('notify.orders.successful.users');
                $users = array_filter($users, function ($value) {
                    return is_string($value) && trim($value) != '';
                });
                if (count($users)) {
                    $smsRes = SMSUtil::send($users, $body);
                    SMSUtil::logSMS($users, $body, $smsRes, SMS_LOG_TYPE_ORDER_NOTIFY, SMS_LOG_SENDER_SYSTEM);
                }
            }
        }

        return $this->render([
            'result' => $res,
            'message' => $msg,
            'order_code' => $orderCode ?? null,
            'payment_code' => $paymentCode,
        ]);
    }
}