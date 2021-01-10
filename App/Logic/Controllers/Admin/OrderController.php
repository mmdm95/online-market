<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Interfaces\IDatatableController;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class OrderController extends AbstractAdminController implements IDatatableController
{
    /**
     * @param null $id
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
            $this->setLayout($this->main_layout)->setTemplate('view/order/order-detail');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/order/view');
        }

        return $this->render();
    }

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
    public function returnOrder($id = null)
    {
        if (!is_null($id)) {
            $this->setLayout($this->main_layout)->setTemplate('view/order/return-order-detail');
        } else {
            $this->setLayout($this->main_layout)->setTemplate('view/order/return-order');
        }
        return $this->render();
    }

    /**
     * @param array $_
     * @return void
     */
    public function getPaginatedDatatable(...$_): void
    {

    }
}