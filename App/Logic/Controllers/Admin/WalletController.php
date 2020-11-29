<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use ReflectionException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class WalletController extends AbstractAdminController
{
    /**
     * @param null $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     */
    public function view($id = null)
    {
        if (!is_null($id)) {
            $this->setLayout($this->main_layout)->setTemplate('view/wallet/user-wallet');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/wallet/view');
        }

        return $this->render();
    }

    /**
     * @return string
     * @throws ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function depositType()
    {

        $this->setLayout($this->main_layout)->setTemplate('view/wallet/deposit-type');

        return $this->render();
    }
}