<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;

class ColorController extends AbstractAdminController
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
    public function add()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/color/add');

        return $this->render();
    }

    /**
     * @return string
     * @throws \ReflectionException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Exceptions\Mvc\Controller\ControllerException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public function view()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/color/view');

        return $this->render();
    }

}