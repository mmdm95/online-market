<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;

class UserController extends AbstractAdminController
{
    /**
     * @param null $id
     * @return string
     * @throws \ReflectionException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Exceptions\Mvc\Controller\ControllerException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public function view($id = null)
    {
        if (!is_null($id)) {
            $this->setLayout($this->main_layout)->setTemplate('view/user/view-profile');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/user/view');
        }

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
    public function add()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/user/add');

        return $this->render();
    }

    /**
     * @param $id
     * @return string
     * @throws \ReflectionException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Exceptions\Mvc\Controller\ControllerException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public function edit($id)
    {
        $this->setLayout($this->main_layout)->setTemplate('view/user/edit');

        return $this->render();
    }
}