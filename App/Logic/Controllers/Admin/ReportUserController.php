<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Models\UserModel;
use ReflectionException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ReportUserController extends AbstractAdminController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function usersReport()
    {
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);



        $this->setLayout($this->main_layout)->setTemplate('view/report/user');
        return $this->render([

        ]);
    }

    public function userFilter()
    {

    }
}