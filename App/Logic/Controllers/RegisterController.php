<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class RegisterController extends AbstractHomeController
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
    public function index()
    {


        $this->setLayout($this->main_layout)->setTemplate('view/main/signup');
        return $this->render([]);
    }

    /**
     * @param string $step
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function activation($step = 'step1')
    {
        if (is_post()) {
            switch (strtolower($step)) {
                case 'step1':

                    break;
                case 'step2':

                    break;
                case 'step3':

                    break;
            }
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/activation/' . $step);
        return $this->render();
    }
}