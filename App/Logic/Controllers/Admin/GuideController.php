<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;

class GuideController extends AbstractAdminController
{
    /**
     * @return string
     * @throws \ReflectionException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Exceptions\Mvc\Controller\ControllerException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public function index()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/guide/index');
        return $this->render();
    }
}
