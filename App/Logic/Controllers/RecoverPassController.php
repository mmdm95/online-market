<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\ForgetPassword\ForgetFormStep1;
use App\Logic\Forms\ForgetPassword\ForgetFormStep2;
use App\Logic\Forms\ForgetPassword\ForgetFormStep3;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Middlewares\Logic\ForgetCodeCheckMiddleware;
use App\Logic\Middlewares\Logic\ForgetCompletionCheckMiddleware;
use App\Logic\Middlewares\Logic\ForgetMobileCheckMiddleware;
use App\Logic\Models\UserModel;
use App\Logic\Utils\SMSUtil;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\SMS\Exceptions\SMSException;

class RecoverPassController extends AbstractHomeController
{
    /**
     * @param string $step
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     * @throws SMSException
     */
    public function forgetPassword($step = 'step1')
    {
        notify_or_redirect_logged_in_user(auth_home());

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $data = [];
        switch (strtolower($step)) {
            case 'step1':
                $this->removeAllForgetSessions();
                if (is_post()) {
                    /**
                     * @var ForgetFormStep1 $forgetForm
                     */
                    $forgetForm = container()->get(ForgetFormStep1::class);
                    [$status, $errors] = $forgetForm->validate();
                    if ($status) {
                        $res = $forgetForm->store();
                        // success or warning message
                        if ($res) {
                            $username = input()->post('inp-forget-mobile', '')->getValue();
                            // save mobile for future usage
                            session()->setFlash('forget.username', $username);
                            if ($userModel->count('username=:username AND recover_password_type=:rpt', ['username' => $username, 'rpt' => RECOVER_PASS_TYPE_SMS])) {
                                session()->set('forget.recover_by_mobile', RECOVER_PASS_TYPE_SMS);
                                // send code as sms
                                $body = replaced_sms_body(SMS_TYPE_RECOVER_PASS, [
                                    SMS_REPLACEMENTS['mobile'] => $username,
                                    SMS_REPLACEMENTS['code'] => session()->getFlash('forget.code', ''),
                                ]);
                                $smsRes = SMSUtil::send([$username], $body);
                                SMSUtil::logSMS([$username], $body, $smsRes, SMS_LOG_TYPE_RECOVER_PASS, SMS_LOG_SENDER_SYSTEM);
                            }

                            response()->redirect(url('home.forget-password', ['step' => 'step2'])->getRelativeUrlTrimmed());
                        } else {
                            $data['forget_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                        }
                    } else {
                        $data['forget_errors'] = $errors;
                    }
                }
                break;
            case 'step2':
                /**
                 * @var ForgetMobileCheckMiddleware $checkStep1
                 */
                $checkStep1 = container()->get(ForgetMobileCheckMiddleware::class);
                $isValid = $checkStep1->handle();
                if (!$isValid) {
                    response()->redirect(url('home.forget-password', ['step' => 'step1'])->getRelativeUrlTrimmed());
                }

                // get security question of user
                $username = session()->getFlash('forget.username', null, false);
                $question = $userModel->getFirst(['security_question'], 'username=:username', ['username' => $username]);

                if (!count($question) || empty($question['security_question'])) {
                    $data['sec_question'] = $question['security_question'];
                } else {
                    $data['forget_warning'] = 'سؤال امنیتی برای این کاربر تعریف نشده است.';
                    session()->remove('forget.recover_by_mobile');
                }

                // submit code
                if (is_post()) {
                    /**
                     * @var ForgetFormStep2 $forgetForm
                     */
                    $forgetForm = container()->get(ForgetFormStep2::class);
                    [$status, $errors] = $forgetForm->validate();
                    if ($status) {
                        $res = $forgetForm->store();
                        // success or warning message
                        if ($res) {
                            session()->setFlash('forget.code-step', 'I am ready to set password');
                            session()->remove('forget.recover_by_mobile');
                            response()->redirect(url('home.forget-password', ['step' => 'step3'])->getRelativeUrlTrimmed());
                        } else {
                            $data['forget_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                        }
                    } else {
                        $data['forget_errors'] = $errors;
                    }
                }
                break;
            case 'step3':
                /**
                 * @var ForgetMobileCheckMiddleware $checkStep1
                 */
                $checkStep1 = container()->get(ForgetMobileCheckMiddleware::class);
                $isValid = $checkStep1->handle();
                /**
                 * @var ForgetCodeCheckMiddleware $checkStep1
                 */
                $checkStep2 = container()->get(ForgetCodeCheckMiddleware::class);
                $isValid2 = $checkStep2->handle();
                if (!$isValid || !$isValid2) {
                    $this->removeAllForgetSessions();
                    response()->redirect(url('home.forget-password', ['step' => 'step1'])->getRelativeUrlTrimmed());
                }
                // submit new password
                if (is_post()) {
                    /**
                     * @var ForgetFormStep3 $forgetForm
                     */
                    $forgetForm = container()->get(ForgetFormStep3::class);
                    [$status, $errors] = $forgetForm->validate();
                    if ($status) {
                        $res = $forgetForm->store();
                        // success or warning message
                        if ($res) {
                            session()->setFlash('forget.completion', 'Password changed!');
                            response()->redirect(url('home.forget-password', ['step' => 'step4'])->getRelativeUrlTrimmed());
                        } else {
                            $data['forget_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                        }
                    } else {
                        $data['forget_errors'] = $errors;
                    }
                }
                break;
            case 'step4':
                /**
                 * @var ForgetCompletionCheckMiddleware $checkSteps
                 */
                $checkSteps = container()->get(ForgetCompletionCheckMiddleware::class);
                $isValid = $checkSteps->handle();
                if (!$isValid) {
                    response()->redirect(url('home.forget-password', ['step' => 'step1'])->getRelativeUrlTrimmed());
                }
                $this->removeAllForgetSessions();
                break;
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/forget-password/' . $step);
        return $this->render($data);
    }

    /**
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws SMSException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function resendRecoverCode()
    {
        $resourceHandler = new ResourceHandler();
        $resourceHandler->data('کد فراموشی کلمه عبور مجددا برای شما ارسال شد.');

        notify_or_redirect_logged_in_user(auth_home());

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        // get security question of user
        $username = session()->getFlash('forget.username', null, false);
        $user = $userModel->getFirst(['forget_password_code', 'activate_code_request_free_at'], 'username=:username AND recover_password_type=:rpt', ['username' => $username, 'rpt' => RECOVER_PASS_TYPE_SMS]);
        if (count($user)) {
            if ($user['activate_code_request_free_at'] >= time()) {
                $t = time() - $user['activate_code_request_free_at'];
                $min = (int)($t / 60);
                $sec = $t % 60;
                $resourceHandler
                    ->type(RESPONSE_TYPE_WARNING)
                    ->errorMessage('تا زمان ارسال مجدد کد فراموشی کلمه عبور ' . ($min . ':' . $sec) . ' باقی مانده است.');
            } else {
                // update forget password time
                $userModel->update([
                    'activate_code_request_free_at' => time() + TIME_ACTIVATE_CODE,
                ], 'username=:username', ['username' => $username]);

                // send code as sms
                $body = replaced_sms_body(SMS_TYPE_RECOVER_PASS, [
                    SMS_REPLACEMENTS['mobile'] => $username,
                    SMS_REPLACEMENTS['code'] => $user['forget_password_code'],
                ]);
                $smsRes = SMSUtil::send([$username], $body);
                SMSUtil::logSMS([$username], $body, $smsRes, SMS_LOG_TYPE_RECOVER_PASS, SMS_LOG_SENDER_SYSTEM);
            }
        } else {
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('کاربر مورد نظر برای ارسال کد فراموشی کلمه عبور، نامعتبر است.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * Remove all previous sessions
     */
    private function removeAllForgetSessions()
    {
        session()->removeFlash('forget.username');
        session()->removeFlash('forget.code-step');
        session()->removeFlash('forget.completion');
    }
}