<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use ReflectionException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ReportController extends AbstractAdminController
{
    /**
     * @return string
     * @throws ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function usersReport()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/report/user');
        return $this->render();
    }
}