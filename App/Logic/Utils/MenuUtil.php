<?php

namespace App\Logic\Utils;

use App\Logic\Models\MenuModel;

class MenuUtil
{
    /**
     * @var array
     */
    private $main_menu_items = null;

    /**
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getMainMenuItems(): array
    {
        if (!is_null($this->main_menu_items)) return $this->main_menu_items;

        /**
         * @var MenuModel $menuModel
         */
        $menuModel = \container()->get(MenuModel::class);

        $menu = $menuModel->getMenuItems();
        //------------------------
        // make categories nested
        //------------------------
        // menu level1
        $lvl1 = $this->getMenuLevelN($menu, 1);
        // menu level2
        $lvl2 = $this->getMenuLevelN($menu, 2);
        // menu level3
        $lvl3 = $this->getMenuLevelN($menu, 3);
        // assemble all menu items
        $this->main_menu_items = $this->assembleMenu($lvl1, $lvl2, $lvl3);

        return $this->main_menu_items;
    }

    /**
     * @param $menu
     * @param $sub_menu
     * @return array
     */
    public function assembleTopMenu($menu, $sub_menu): array
    {
        try {
            $assembledMenu = [];
            $counter = 0;
            foreach ($menu as $k => $main) {
                $children = [];
                $title = $main['title']->getValue();
                if ('' != trim($title)) {
                    $link = $main['link']->getValue();
                    $priority = $main['priority']->getValue();

                    foreach ($sub_menu[$k] as $sub) {
                        $subTitle = $sub['sub-title']->getValue();
                        if ('' != trim($subTitle)) {
                            $subLink = $sub['sub-link']->getValue();
                            $subPriority = $sub['sub-priority']->getValue();

                            if (is_numeric($subPriority)) {
                                $children[(int)$subPriority] = [
                                    'name' => $subTitle,
                                    'link' => $subLink,
                                ];
                            } else {
                                $children[] = [
                                    'name' => $subTitle,
                                    'link' => $subLink,
                                ];
                            }
                        }
                    }

                    if (is_numeric($priority)) {
                        $counter = (int)$priority;
                    }
                    $assembledMenu[$counter] = [
                        'name' => $title,
                        'link' => $link,
                    ];
                    $assembledMenu[$counter]['children'] = $children;
                    $counter++;
                }
            }
            return $assembledMenu;
        } catch (\Exception $e) {
            return [];
        }
    }

    //----------------------------------------------------------------------------------------
    // Extra functionality - Menu
    //----------------------------------------------------------------------------------------

    /**
     * @param array $arr
     * @param int $level
     * @return array
     */
    private function getMenuLevelN(array $arr, int $level): array
    {
        return array_filter($arr, function ($val) use ($level) {
            if ($level == $val['level']) return true;
            return false;
        });
    }

    /**
     * @param $lvl1
     * @param $lvl2
     * @param $lvl3
     * @return array
     */
    private function assembleMenu($lvl1, $lvl2, $lvl3): array
    {
        $menuItems = [];
        foreach ($lvl1 as $k1 => $level1) {
            $menuItems[$k1] = $level1;
            $itemsLvl2 = [];
            foreach ($lvl2 as $k2 => $level2) {
                if ($level1['id'] === $level2['parent_id']) {
                    $itemsLvl2[$k2] = $level2;

                    $itemsLvl3 = [];
                    foreach ($lvl3 as $k3 => $level3) {
                        if ($level2['id'] === $level3['parent_id']) {
                            $itemsLvl3[$k3] = $level3;
                        }
                    }

                    $itemsLvl2[$k2]['children'] = $itemsLvl3;
                }
            }
            $menuItems[$k1]['children'] = $itemsLvl2;
        }
        return $menuItems;
    }
}