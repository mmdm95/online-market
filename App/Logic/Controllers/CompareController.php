<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class CompareController extends AbstractHomeController
{
    /**
     * @return string
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function compare()
    {


        $this->setLayout($this->main_layout)->setTemplate('view/main/product/compare');
        return $this->render();
    }
}