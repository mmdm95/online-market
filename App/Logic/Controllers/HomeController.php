<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\IndexPageModel;
use App\Logic\Models\MenuModel;
use App\Logic\Utils\MenuUtil;
use App\Logic\Utils\TabSliderUtil;
use Aura\SqlQuery\Exception as AuraException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;
use Tests\FakeData;

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
     * @throws AuraException
     */
    public function index()
    {
        $this->setLayout($this->main_index_layout)->setTemplate('view/main/index');

        /**
         * @var MenuModel $menuModel
         */
        $menuModel = \container()->get(MenuModel::class);
        /**
         * @var CategoryModel $catModel
         */
        $catModel = \container()->get(CategoryModel::class);
        /**
         * @var MenuUtil $menuUtil
         */
        $menuUtil = \container()->get(MenuUtil::class);
        /**
         * @var IndexPageModel $indexModel
         */
        $indexModel = \container()->get(IndexPageModel::class);
        /**
         * @var TabSliderUtil $tabSliderUtil
         */
        $tabSliderUtil = \container()->get(TabSliderUtil::class);

        $menuItems = $menuUtil->getMainMenuItems();
        //-----
        $menuImages = $menuModel->getMenuImages();
        //----------------------
        // group by category id
        //----------------------
        $menuImages = ArrayUtil::arrayGroupBy('id', $menuImages);
        //-----
        $categories = $catModel->getCategories(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]);
        //-----
        $mainSlider = $indexModel->getMainSlider();
        // tabbed slider
        $tabbedSlider = [];
        foreach (\config()->get('settings.index_tabbed_slider.value.items') ?? [] as $k => $tab) {
            if (is_array($tab)) {
                $tabbedSlider[$k]['info'] = $tab;
                $tabbedSlider[$k]['items'] = $tabSliderUtil->getTabSliderItems($tab);
            }
        }

        return $this->render([
            'menu' => $menuItems,
            'menu_images' => $menuImages,
            'categories' => $categories,
            'main_slider' => $mainSlider,
            'tabbed_slider' => [
                'title' => \config()->get('settings.index_tabbed_slider.value.title'),
                'items' => $tabbedSlider,
            ],
            'instagram_images' => [],
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
        $this->setLayout($this->main_index_layout)->setTemplate('view/main/search');

        return $this->render([]);
    }

    /**
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    private function fakeIt()
    {
        $faker = new FakeData();
//        $faker->categories();
//        $faker->setupConfig();
    }
}