<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;

class CategoryController extends AbstractAdminController
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
        $this->setLayout($this->main_layout)->setTemplate('view/category/add');

        return $this->render();
    }
<<<<<<< HEAD
=======

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
        $this->setLayout($this->main_layout)->setTemplate('view/category/edit');

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
        $this->setLayout($this->main_layout)->setTemplate('view/category/view');

        return $this->render();
    }

>>>>>>> 38fa135840b78f8276d8c9e79c020469cd5260ba
}