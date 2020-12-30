<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use ReflectionException as ReflectionExceptionAlias;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class CouponController extends AbstractAdminController
{

    /**
     * @return string
     * @throws ReflectionExceptionAlias
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function add()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/coupon/add');

        return $this->render();
    }

    /**
     * @param $id
     * @return string
     * @throws ReflectionExceptionAlias
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function edit($id)
    {
        $this->setLayout($this->main_layout)->setTemplate('view/coupon/edit');

        return $this->render();
    }

    /**
     * @return string
     * @throws ReflectionExceptionAlias
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/coupon/view');

        return $this->render();
    }

}