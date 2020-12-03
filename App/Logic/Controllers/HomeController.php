<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Models\BlogModel;
use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\IndexPageModel;
use App\Logic\Models\InstagramImagesModel;
use App\Logic\Models\MenuModel;
use App\Logic\Utils\MenuUtil;
use App\Logic\Utils\SliderUtil;
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
         * @var IndexPageModel $indexModel
         */
        $indexModel = \container()->get(IndexPageModel::class);
        /**
         * @var InstagramImagesModel $instagramImagesModel
         */
        $instagramImagesModel = \container()->get(InstagramImagesModel::class);
        /**
         * @var BlogModel $blogModel
         */
        $blogModel = \container()->get(BlogModel::class);
        /**
         * @var BrandModel $brandModel
         */
        $brandModel = \container()->get(BrandModel::class);
        /**
         * @var MenuUtil $menuUtil
         */
        $menuUtil = \container()->get(MenuUtil::class);
        /**
         * @var SliderUtil $sliderUtil
         */
        $sliderUtil = \container()->get(SliderUtil::class);

        $menuImages = $menuModel->getMenuImages();
        //----------------------
        // group by category id
        //----------------------
        $menuImages = ArrayUtil::arrayGroupBy('id', $menuImages);
        //-----
        // tabbed slider
        $tabbedSlider = [];
        foreach (\config()->get('settings.index_tabbed_slider.value.items') ?? [] as $k => $tab) {
            if (is_array($tab)) {
                $tabbedSlider[$k]['info'] = $tab;
                $tabbedSlider[$k]['items'] = $sliderUtil->getTabSliderItems($tab);
            }
        }

        return $this->render([
            'menu' => $menuUtil->getMainMenuItems(),
            'menu_images' => $menuImages,
            'categories' => $catModel->get(['id', 'name'], 'level=:lvl AND publish=:pub', ['pub' => DB_YES, 'lvl' => 1], ['name ASC']),
            'main_slider' => $indexModel->getMainSlider(),
            'tabbed_slider' => [
                'title' => \config()->get('settings.index_tabbed_slider.value.title'),
                'items' => $tabbedSlider,
            ],
            'instagram_images' => $instagramImagesModel->get(['image', 'link']),
            'special_slider' => $sliderUtil->getSpecialsSlider(),
            'three_images' => \config()->get('settings.index_3_images.value'),
            'blog' => $blogModel->get(
                ['b.id', 'b.title', 'b.slug', 'b.image', 'b.abstract', 'b.created_at'],
                'b.publish=:b_pub',
                ['b_pub' => DB_YES],
                ['id DESC'],
                3
            ),
            'brands' => $brandModel->get(
                ['name', 'image'],
                'publish=:pub AND show_in_sliders=:sis',
                ['pub' => DB_YES, 'sis' => DB_YES]
            )
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