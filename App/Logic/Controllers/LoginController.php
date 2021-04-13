<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\ForgetPassword\ForgetFormStep1;
use App\Logic\Forms\ForgetPassword\ForgetFormStep2;
use App\Logic\Forms\ForgetPassword\ForgetFormStep3;
use App\Logic\Forms\LoginForm;
use App\Logic\Middlewares\Logic\ForgetCodeCheckMiddleware;
use App\Logic\Middlewares\Logic\ForgetCompletionCheckMiddleware;
use App\Logic\Middlewares\Logic\ForgetMobileCheckMiddleware;
use App\Logic\Models\BaseModel;
use App\Logic\SMS\CommonSMS;
use Sim\Auth\DBAuth;
use Sim\Auth\Exceptions\IncorrectPasswordException;
use Sim\Auth\Exceptions\InvalidUserException;
use Sim\Auth\Interfaces\IDBException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\SMS\Exceptions\SMSException;
use Sim\Utils\ArrayUtil;

class LoginController extends AbstractHomeController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws FormException
     * @throws IDBException
     */
    public function index()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');

        if ($auth->isLoggedIn()) {
            response()->redirect(url('home.index')->getRelativeUrl(), 301);
        }

        $data = [];
        if (is_post()) {
            $this->logout();
            try {
                /**
                 * @var LoginForm $loginForm
                 */
                $loginForm = container()->get(LoginForm::class);
                [$status, $errors] = $loginForm->validate();
                if ($status) {
                    $auth->login([
                        'username' => input()->post('inp-login-username', '')->getValue(),
                        'password' => input()->post('inp-login-password', '')->getValue()
                    ], BaseModel::TBL_USERS . '.is_activated=:isActive',
                        [
                            'isActive' => DB_YES
                        ]);
                    if ($auth->isLoggedIn()) {
                        $backUrl = ArrayUtil::get($_GET, 'back_url', null);
                        if (!empty($backUrl)) {
                            response()->redirect($backUrl);
                        } elseif ($auth->userHasRole(ROLE_COLLEAGUE)) {
                            response()->redirect(url('colleague.index')->getRelativeUrlTrimmed());
                        } else {
                            response()->redirect(url('user.index')->getRelativeUrlTrimmed());
                        }
                    } else {
                        $data['login_errors'] = [
                            'عملیات ورود ناموفق بود، لطفا مجددا تلاش نمایید.'
                        ];
                    }
                } else {
                    $data['login_errors'] = $errors;
                }
            } catch (IncorrectPasswordException|InvalidUserException|IDBException $e) {
                $data['login_errors'] = [
                    'نام کاربری یا کلمه عبور نادرست است!'
                ];
            }
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/login');
        return $this->render($data);
    }

    /**
     * @throws IDBException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function logout()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        $auth->logout();

        response()->redirect(url('home.login')->getRelativeUrl(), 301);
    }

    /**
     * @param string $step
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws SMSException
     */
    public function forgetPassword($step = 'step1')
    {
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

                            // send code as sms
//                            $forgetSms = new CommonSMS();
//                            $forgetSms->send([$username], replaced_sms_body(SMS_TYPE_ACTIVATION, [
//                                SMS_REPLACEMENTS['mobile'] => $username,
//                                SMS_REPLACEMENTS['code'] => session()->getFlash('register.code', ''),
//                            ]));

                            response()->redirect(url('home.forget-password', ['step' => 'step2'])->getRelativeUrlTrimmed());
                        } else {
                            $data['register_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                        }
                    } else {
                        $data['register_errors'] = $errors;
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
                            response()->redirect(url('home.forget-password', ['step' => 'step3'])->getRelativeUrlTrimmed());
                        } else {
                            $data['register_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                        }
                    } else {
                        $data['register_errors'] = $errors;
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
                            $data['register_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                        }
                    } else {
                        $data['register_errors'] = $errors;
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
     * Remove all previous sessions
     */
    private function removeAllForgetSessions()
    {
        session()->removeFlash('forget.username');
        session()->removeFlash('forget.code-step');
        session()->removeFlash('forget.completion');
    }
}