<?php

namespace App\Logic\Utils;

use App\Logic\Models\ProductAttributeModel;
use Sim\Utils\ArrayUtil;

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
