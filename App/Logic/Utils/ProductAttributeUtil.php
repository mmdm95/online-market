<?php

namespace App\Logic\Utils;

use App\Logic\Models\ColorModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Models\ProductModel;
use Pecee\Http\Input\IInputItem;
use Pecee\Http\Input\InputItem;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class ProductAttributeUtil
{
    /**
     * @param int $categoryId
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getRefinedProductAttributes(int $categoryId): array
    {
        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        $refinedAttrs = [];
        $attrs = ArrayUtil::arrayGroupBy(
            'id',
            $attrModel->getProductAttrValuesOfCategory(
                $categoryId,
                ['pav.attr_val', 'pav.id AS val_id', 'pa.id', 'pa.type', 'pa.title'],
                ['pac.priority DESC', 'pav.id ASC']
            )
        );
        foreach ($attrs as $attr => $values) {
            $vals = [];
            foreach ($values as $v) {
                $vals[$v['val_id']] = $v['attr_val'];
            }
            $refinedAttrs[$attr] = [
                'title' => $values[0]['title'],
                'type' => $values[0]['type'],
                'values' => $vals,
            ];
        }

        return $refinedAttrs;
    }
}
