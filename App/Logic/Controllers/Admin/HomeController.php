<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class HomeController extends AbstractAdminController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function index()
    {
        $this->setTemplate('view/index');
        return $this->render();
    }

    /**
     * @return string
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function login()
    {
        if (is_post()) {

        }

        $this
            ->setLayout('admin-login')
            ->setTemplate('view/admin-login');
        return $this->render();
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function browser()
    {
        $this->setTemplate('partial/editor/browser');
        return $this->render([
            'the_options' => [
                'allow_upload' => false,
                'allow_create_folder' => false,
                'allow_direct_link' => false,
            ],
        ]);
    }

    /**
     * Logout from system
     */
    public function logout()
    {
        response()->redirect(url('admin.login')->getRelativeUrl(), 301);
    }
}