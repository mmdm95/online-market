<?php

namespace App\Logic\Utils;

use Pecee\Http\Input\InputItem;

class SettingUtil
{
    /**
     * @param $title
     * @param $itemNames
     * @param $itemTypes
     * @param $itemLimits
     * @param $itemCategories
     * @return array
     */
    public static function assembleTabbedSlider($title, $itemNames, $itemTypes, $itemLimits, $itemCategories): array
    {
        $assembled = [
            'title' => $title->getValue(),
            'items' => [],
        ];
        $counter = 0;

        /**
         * @var InputItem $name
         */
        foreach ($itemNames as $name) {
            if ('' !== trim($name->getValue())) {
                $assembled['items'][$counter]['name'] = $name->getValue();
                $assembled['items'][$counter]['type'] = $itemTypes[$counter]->getValue();
                $assembled['items'][$counter]['category'] = $itemCategories[$counter]->getValue();
                $assembled['items'][$counter]['limit'] = $itemLimits[$counter]->getValue();
                ++$counter;
            }
        }

        return $assembled;
    }

    /**
     * @param $itemImagesLink
     * @param $itemImages
     * @param $itemTitles
     * @param $itemTypes
     * @param $itemLimits
     * @param $itemCategories
     * @param $itemLinks
     * @return array
     */
    public static function assembleGeneralSlider(
        $itemImagesLink,
        $itemImages,
        $itemTitles,
        $itemTypes,
        $itemLimits,
        $itemCategories,
        $itemLinks
    ): array
    {
        $assembled = [];
        $counter = 0;

        /**
         * @var InputItem $title
         */
        foreach ($itemTitles as $title) {
            if ('' !== trim($title->getValue()) && in_array($itemTypes[$counter]->getValue(), array_keys(SLIDER_TABBED_TYPES))) {
                $assembled[$counter]['image_link'] = $itemImagesLink[$counter]->getValue();
                $assembled[$counter]['image'] = $itemImages[$counter]->getValue();
                $assembled[$counter]['title'] = $title->getValue();
                $assembled[$counter]['type'] = $itemTypes[$counter]->getValue();
                $assembled[$counter]['category'] = $itemCategories[$counter]->getValue();
                $assembled[$counter]['limit'] = $itemLimits[$counter]->getValue();
                $assembled[$counter]['link'] = $itemLinks[$counter]->getValue();
                ++$counter;
            }
        }

        return $assembled;
    }

    /**
     * @param $itemImages
     * @param $itemLinks
     * @return array
     */
    public static function assembleThreeImages($itemImages, $itemLinks): array
    {
        $assembled = [];
        $counter = 0;

        /**
         * @var InputItem $image
         */
        foreach ($itemImages as $image) {
            $assembled[$counter]['image'] = $image->getValue();
            $assembled[$counter]['link'] = $itemLinks[$counter]->getValue();
            ++$counter;
        }

        return $assembled;
    }

    /**
     * @param $itemTitles
     * @param $itemSubTitles
     * @param $itemImages
     * @param $itemColors
     * @param $itemLinks
     * @return array
     */
    public static function assembleMainSliderSideImages(
        $itemTitles,
        $itemSubTitles,
        $itemImages,
        $itemColors,
        $itemLinks
    ): array
    {
        $assembled = [];
        $counter = 0;

        /**
         * @var InputItem $image
         */
        foreach ($itemImages as $image) {
            $assembled[$counter]['title'] = $itemTitles[$counter]->getValue();
            $assembled[$counter]['sub_title'] = $itemSubTitles[$counter]->getValue();
            $assembled[$counter]['image'] = $image->getValue();
            $assembled[$counter]['color'] = $itemColors[$counter]->getValue();
            $assembled[$counter]['link'] = $itemLinks[$counter]->getValue();
            ++$counter;
        }

        return $assembled;
    }
}
