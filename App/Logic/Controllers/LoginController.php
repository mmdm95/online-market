<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\LoginForm;
use App\Logic\Models\BaseModel;
use Sim\Auth\DBAuth;
use Sim\Auth\Exceptions\IncorrectPasswordException;
use Sim\Auth\Exceptions\InvalidUserException;
use Sim\Auth\Interfaces\IDBException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;

class LoginController extends AbstractHomeController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws FormException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
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
            $auth->logout();
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
                        $backUrl = urldecode($backUrl);
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
}