<?php

namespace App\Logic\Utils;

use App\Logic\Models\ColorModel;
use App\Logic\Models\ProductModel;
use Pecee\Http\Input\IInputItem;
use Pecee\Http\Input\InputItem;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class TorobUtil
{
    /**
     * @param $product
     * @return string[]
     */
    public static function getMetaOfProduct($product): array
    {
        return [
            [
                'name' => 'product_id',
                'content' => $product['product_id'],
            ],
            [
                'name' => 'product_name',
                'content' => $product['title'],
            ],
            [
                'name' => 'product_price',
                'content' => get_discount_price($product)[0],
            ],
            [
                'name' => 'product_old_price',
                'content' => $product['price'],
            ],
            [
                'name' => 'availability',
                'content' => get_product_availability($product) ? 'instock' : 'outofstock',
            ],
            '<meta property="og:image" content="' . url('image.show') . $product['image'] . '">',
        ];
    }
}
