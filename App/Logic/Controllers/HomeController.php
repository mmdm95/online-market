<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\MenuModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class HomeController extends AbstractHomeController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function index()
    {
        $this->setLayout('main-index')->setTemplate('view/main/index');

        /**
         * @var MenuModel $menuModel
         */
        $menuModel = \container()->get(MenuModel::class);
        /**
         * @var CategoryModel $catModel
         */
        $catModel = \container()->get(CategoryModel::class);

        $menu = $menuModel->getMenuItems();
        $categories = $catModel->getCategories(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]);

        return $this->render([
            'menu' => $menu,
            'categories' => $categories,
        ]);
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function search()
    {
        $this->setLayout('main-index')->setTemplate('view/main/search');

        return $this->render([]);
    }
}