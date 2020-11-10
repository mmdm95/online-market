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

    public function index()
    {
        $this->setTemplate('partial/simple');

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
        $this
            ->setLayout('admin-login')
            ->setTemplate('view/admin-login');

        return $this->render([
            'title' => \translate()->translate('admin.login.title'),
        ]);
    }
}