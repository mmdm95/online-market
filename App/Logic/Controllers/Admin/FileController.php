<?php

namespace App\Logic\Controllers\Admin;

use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class FileController extends AbstractAdminController
{
    public function index()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/file-manager');

        return $this->render();
    }
}