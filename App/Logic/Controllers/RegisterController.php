<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\Register\RegisterFormStep1;
use App\Logic\Forms\Register\RegisterFormStep2;
use App\Logic\Forms\Register\RegisterFormStep3;
use App\Logic\Middlewares\Logic\RegisterCodeCheckMiddleware;
use App\Logic\Middlewares\Logic\RegisterMobileCheckMiddleware;
use App\Logic\Models\BaseModel;
use App\Logic\SMS\CommonSMS;
use Sim\Auth\DBAuth;
use Sim\Auth\Exceptions\InvalidUserException;
use Sim\Auth\Interfaces\IDBException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\SMS\Exceptions\SMSException;

class RegisterController extends AbstractHomeController
{
    /**
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
     */
    public function index()
    {
        $data = [];
        if (is_post()) {
            /**
             * @var RegisterFormStep1 $registerForm
             */
            $registerForm = container()->get(RegisterFormStep1::class);
            [$status, $errors] = $registerForm->validate();
            if ($status) {
                $res = $registerForm->store();
                // success or warning message
                if ($res) {
                    $username = input()->post('inp-register-username', '')->getValue();
                    // save mobile for future usage
                    session()->setFlash('register.username', $username);

                    // send code as sms
//                    $registerSms = new CommonSMS();
//                    $registerSms->send([$username], replaced_sms_body(SMS_TYPE_ACTIVATION, [
//                        SMS_REPLACEMENTS['mobile'] => $username,
//                        SMS_REPLACEMENTS['code'] => session()->getFlash('register.code', ''),
//                    ]));

                    response()->redirect(url('home.signup.code')->getRelativeUrlTrimmed());
                } else {
                    $data['register_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                }
            } else {
                $data['register_errors'] = $errors;
            }
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/signup/step1');
        return $this->render($data);
    }

    /**
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
     */
    public function enterCode()
    {
        $this->setMiddleWare(RegisterMobileCheckMiddleware::class);
        if (!$this->middlewareResult()) {
            response()->redirect(url('home.signup')->getRelativeUrlTrimmed());
        } else {
            $this->removeAllMiddlewares();
        }

        $data = [];
        if (is_post()) {
            /**
             * @var RegisterFormStep2 $registerForm
             */
            $registerForm = container()->get(RegisterFormStep2::class);
            [$status, $errors] = $registerForm->validate();
            if ($status) {
                $res = $registerForm->store();
                // success or warning message
                if ($res) {
                    session()->setFlash('register.code-step', 'I am ready to set password');
                    response()->redirect(url('home.signup.password')->getRelativeUrlTrimmed());
                } else {
                    $data['register_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                }
            } else {
                $data['register_errors'] = $errors;
            }
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/signup/step2');
        return $this->render($data);
    }

    /**
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
     */
    public function enterPassword()
    {
        $this->setMiddleWare(RegisterCodeCheckMiddleware::class);
        if (!$this->middlewareResult()) {
            response()->redirect(url('home.signup')->getRelativeUrlTrimmed());
        } else {
            $this->removeAllMiddlewares();
        }

        if (is_post()) {
            /**
             * @var RegisterFormStep3 $registerForm
             */
            $registerForm = container()->get(RegisterFormStep3::class);
            [$status, $errors] = $registerForm->validate();
            if ($status) {
                $res = $registerForm->store();
                // success or warning message
                if ($res) {
                    // login user and redirect to user's panel
                    /**
                     * @var DBAuth $auth
                     */
                    $auth = container()->get('auth_home');
                    try {
                        $auth->loginWithUsername(
                            session()->getFlash('register.username', ''),
                            BaseModel::TBL_USERS . '.is_activated=:is_active',
                            ['is_active' => DB_YES]
                        );
                        response()->redirect(url('user.index')->getRelativeUrlTrimmed());
                    } catch (InvalidUserException|IDBException $e) {
                        $data['register_warning'] = encode_html('خطا در عملیات ورود! لطفا از '
                            . "<a href=\"" . url('home.login')
                            . "\" class=\"btn-link\">"
                            . 'صفحه ورود'
                            . "</a>"
                            . ' استفاده کنید.');
                    }
                } else {
                    $data['register_warning'] = 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.';
                }
            } else {
                $data['register_errors'] = $errors;
            }
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/signup/step3');
        return $this->render([]);
    }
}