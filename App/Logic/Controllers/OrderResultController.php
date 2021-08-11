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
            $info = $gatewayModel->getUserAndOrderInfoFromGatewayFlowCode($code);
            $username = $info['user']['username'] ?? null;
            $orderCode = $info['order']['code'] ?? null;
            if (!is_null($username) && !is_null($orderCode)) {
                // send success order sms
                $body = 'سفارش شما با موفقیت ثبت گردید.' . "\n" .
                    'کد سفارش شما : ' . $orderCode . "\n" .
                    'برای پیگیری به آدرس ' .
                    url('user.order.detail', ['id' => $info['order']['id']])->getRelativeUrlTrimmed() .
                    ' مراجعه نمایید.';
                $smsRes = SMSUtil::send([$username], $body);
                SMSUtil::logSMS([$username], $body, $smsRes, SMS_LOG_TYPE_ORDER_SUCCESS, SMS_LOG_SENDER_SYSTEM);
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
