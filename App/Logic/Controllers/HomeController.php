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
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;

class HomeController extends AbstractHomeController
{
    /**
     * @return string
     * @throws AuraException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
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
                $tabbedSlider[$k]['items'] = $sliderUtil->getFilteredSliderItems($tab);
            }
        }
        // general slider
        $generalSlider = [];
        foreach (\config()->get('settings.index_general_sliders.value') ?: [] as $k => $slider) {
            if (is_array($slider)) {
                $generalSlider[$k]['info'] = $slider;
                $generalSlider[$k]['items'] = $sliderUtil->getFilteredSliderItems($slider);
            }
        }

        return $this->render([
            'menu' => $menuUtil->getMainMenuItems(),
            'menu_images' => $menuImages,
            'categories' => $catModel->get(['id', 'name'], 'level=:lvl AND publish=:pub', ['pub' => DB_YES, 'lvl' => 1], ['name ASC']),
            'main_slider' => $indexModel->getMainSlider(),
            'tabbed_slider_side_image' => \config()->get('settings.index_tabbed_slider_side_image.value'),
            'tabbed_slider' => [
                'title' => \config()->get('settings.index_tabbed_slider.value.title'),
                'items' => $tabbedSlider,
            ],
            'general_slider' => $generalSlider,
            'instagram_images' => $instagramImagesModel->get(['image', 'link']),
            'special_slider' => $sliderUtil->getSpecialsSlider(),
            'three_images' => \config()->get('settings.index_3_images.value'),
            'blog' => $blogModel->getBlog(
                ['b.id', 'b.title', 'b.slug', 'b.image', 'b.abstract', 'b.created_at'],
                'b.publish=:b_pub',
                ['b_pub' => DB_YES],
                ['b.id DESC'],
                3
            ),
            'brands' => $brandModel->get(
                ['name', 'image'],
                'publish=:pub AND show_in_sliders=:sis',
                ['pub' => DB_YES, 'sis' => DB_YES]
            )
        ]);
    }
}