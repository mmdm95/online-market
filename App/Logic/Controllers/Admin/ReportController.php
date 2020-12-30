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
     * @param $id
     * @return string
     * @throws ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view($id = null)
    {
        if (!is_null($id)) {
            $this->setLayout($this->main_layout)->setTemplate('view/complaints/message');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/complaints/view');
        }
        return $this->render();
    }
}