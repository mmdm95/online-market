<?php

namespace App\Logic\Utils;

use App\Logic\Models\ColorModel;
use App\Logic\Models\ProductModel;
use Pecee\Http\Input\IInputItem;
use Pecee\Http\Input\InputItem;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class ProductUtil
{
    /**
     * @return array
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function paginatedProduct(): array
    {
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        // parse query
        try {
            $limit = input()->get('each_page', config()->get('settings.product_each_page.value'))->getValue();
            $page = input()->get('page', 1)->getValue();
        } catch (\Exception $e) {
            $limit = config()->get('settings.product_each_page.value');
            $page = 1;
        }
        $limit = $limit > 0 ? $limit : 1;
        $offset = ((int)$page - 1) * $limit;

        // where clause
        $where = 'pa.publish=:pub AND pa.is_deleted=:del';
        $bindValues = [
            'pub' => DB_YES,
            'del' => DB_NO,
        ];
        // query search parameter
        $q = input()->get('q', '');
        if (!is_array($q)) {
            $q = $q->getValue();
            if (is_string($q) && !empty($q)) {
                $where .= ' AND (pa.category_name LIKE :q_p_category';
                $where .= ' OR pa.fa_title LIKE :q_p_fa_title';
                $where .= ' OR pa.slug LIKE :q_p_slug';
                $where .= ' OR pa.keywords LIKE :q_p_keywords';
                $where .= ' OR pa.brand_fa_name LIKE :q_p_fa_brand';
                $where .= ' OR pa.festival_title LIKE :q_p_festival_title';
                $where .= ')';
                $bindValues['q_p_category'] = '%' . $q . '%';
                $bindValues['q_p_fa_title'] = '%' . StringUtil::toPersian($q) . '%';
                $bindValues['q_p_slug'] = '%' . StringUtil::slugify($q) . '%';
                $bindValues['q_p_keywords'] = '%' . $q . '%';
                $bindValues['q_p_fa_brand'] = '%' . $q . '%';
                $bindValues['q_p_festival_title'] = '%' . $q . '%';
            }
        }
        // tag parameter
        $tag = input()->get('tag', '');
        if (!is_array($tag)) {
            $tag = $tag->getValue();
            if (is_string($tag) && !empty($tag)) {
                $where .= ' OR pa.fa_title LIKE :tag_p_fa_title';
                $where .= ' OR pa.slug LIKE :tag_p_slug';
                $where .= ' OR pa.keywords LIKE :tag_p_keywords';
                $bindValues['tag_p_fa_title'] = '%' . StringUtil::toPersian($tag) . '%';
                $bindValues['tag_p_slug'] = '%' . StringUtil::slugify($tag) . '%';
                $bindValues['tag_p_keywords'] = '%' . $tag . '%';
            }
        }
        // category parameter
        $category = input()->get('category', null);
        if (!is_array($category)) {
            $category = $category->getValue();
            if (is_numeric($category)) {
                $where .= ' AND pa.category_id=:p_category_id';
                $bindValues['p_category_id'] = $category;
            }
        }
        // price parameter
        $price = input()->get('price', null);
        if (!is_array($price) && !empty($price)) {
            $price = json_decode($price->getValue(), true);
            if (!empty($price)) {
                if (isset($price['min']) && isset($price['max'])) {
                    $where .= " AND (CASE WHEN (pa.discount_until IS NULL OR pa.discount_until >= UNIX_TIMESTAMP()) THEN ";
                    $where .= "pa.discounted_price<=:p_max_price AND pa.discounted_price>=:p_min_price";
                    $where .= " ELSE ";
                    $where .= "pa.price<=:p_max_price AND pa.price>=:p_min_price";
                    $where .= " END)";
                    $bindValues['p_min_price'] = (int)$price['min'];
                    $bindValues['p_max_price'] = (int)$price['max'];
                }
            }
        }
        // color parameter
        $colors = input()->get('color', null);
        if (is_array($colors)) {
            $inClause = '';
            /**
             * @var IInputItem $color
             */
            foreach ($colors as $k => $color) {
                if (!is_array($color) && !empty($color->getValue())) {
                    $inClause .= ":p_color{$k},";
                    $bindValues["p_color{$k}"] = $color->getValue();
                }
            }
            $inClause = trim($inClause, ',');
            if (!empty($inClause)) {
                $where .= " AND pa.color IN ({$inClause})";
            }
        }
        // size parameter
        $sizes = input()->get('size', null);
        if (is_array($sizes)) {
            $inClause = '';
            /**
             * @var IInputItem $size
             */
            foreach ($sizes as $k => $size) {
                if (!is_array($size) && !empty($size->getValue())) {
                    $inClause .= ":p_size{$k},";
                    $bindValues["p_size{$k}"] = $size->getValue();
                }
            }
            $inClause = trim($inClause, ',');
            if (!empty($inClause)) {
                $where .= " AND pa.size IN ({$inClause})";
            }
        }
        // brands parameter
        $brands = input()->get('brands', null);
        if (is_array($brands)) {
            $inClause = '';
            /**
             * @var IInputItem $brand
             */
            foreach ($brands as $k => $brand) {
                if (!is_array($brand) && !empty($brand->getValue())) {
                    $inClause .= ":p_brand{$k},";
                    $bindValues["p_brand{$k}"] = $brand->getValue();
                }
            }
            $inClause = trim($inClause, ',');
            if (!empty($inClause)) {
                $where .= " AND pa.brand_id IN ({$inClause})";
            }
        }
        // is available parameter
        $isAvailable = input()->get('is_available', null);
        if (!is_array($isAvailable)) {
            $isAvailable = $isAvailable->getValue();
            if (is_numeric($isAvailable)) {
                $where .= ' AND pa.is_available=:p_is_available';
                $where .= ' AND pa.max_cart_count>:p_max_cart_count';
                $where .= ' AND pa.stock_count>:p_stock_count';
                $bindValues['p_is_available'] = DB_YES;
                $bindValues['p_max_cart_count'] = 0;
                $bindValues['p_stock_count'] = 0;
            }
        }
        // is special parameter
        $isSpecial = input()->get('is_special', null);
        if (!is_array($isSpecial)) {
            $isSpecial = $isSpecial->getValue();
            if (is_numeric($isSpecial)) {
                $where .= ' AND pa.is_special=:p_is_special';
                $bindValues['p_is_special'] = DB_YES;
            }
        }
        // order by parameter
        $orderBy = ['pa.product_id DESC'];
        $order = input()->get('sort', null);
        if (!is_array($order)) {
            $order = $order->getValue();
            if (is_numeric($order)) {
                switch ($order) {
                    case 4: // cheapest
                        $orderBy = ['pa.price ASC', 'pa.product_id DESC'];
                        break;
                    case 7: // most expensive
                        $orderBy = ['pa.price DESC', 'pa.product_id DESC'];
                        break;
                    case 12: // most view
                        $orderBy = ['pa.view_count DESC', 'pa.product_id DESC'];
                        break;
                    case 16: // most discount
                        $orderBy = ['CASE WHEN (pa.discount_until IS NULL OR pa.discount_until >= UNIX_TIMESTAMP()) AND pa.stock_count > 0 THEN 0 ELSE 1 END', '((pa.price - pa.discounted_price) / pa.price * 100) DESC', 'pa.discounted_price ASC', 'pa.product_id DESC'];
                        break;
                    default: // newest
                        $orderBy = ['pa.product_id DESC'];
                        break;
                }
            }
        }

        // other info
        $total = $productModel->getLimitedProductCount($where, $bindValues);
        $lastPage = ceil($total / $limit);

        return [
            'items' => $productModel->getLimitedProduct(
                $where,
                $bindValues,
                $orderBy,
                $limit,
                $offset
            ),
            'pagination' => [
                'base_url' => url('home.search')->getRelativeUrlTrimmed(),
                'total' => $total,
                'first_page' => 1,
                'last_page' => $lastPage,
                'current_page' => $page,
            ],
        ];
    }

    /**
     * @param $product_id
     * @return array
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function getRelatedProducts($product_id): array
    {
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $ids = $productModel->getRelatedProductsIds($product_id);

        if (!count($ids)) return [];

        $inClause = '';
        $bind_values = [];
        foreach ($ids as $id) {
            $inClause .= ":id{$id},";
            $bind_values["id{$id}"] = $id;
        }
        $inClause = trim($inClause, ',');
        //-----
        if (empty($inClause)) return [];

        return $productModel->getLimitedProduct(
            'pa.product_id IN (' . $inClause . ') AND pa.publish=:pub AND pa.is_deleted=:del',
            array_merge($bind_values, [
                'pub' => DB_YES,
                'del' => DB_NO,
            ])
        );
    }

    /**
     * Admin add product, create products object
     *
     * Use these keys for each record of array:
     *   [
     *     'price',
     *     'stock_count',
     *     'max_cart',
     *     'discount_price',
     *     'color_hex',
     *     'color_name',
     *     'size',
     *     'guarantee',
     *     'discount_until',
     *     'available',
     *   ]
     *
     * @return array
     */
    public function createProductObject(): array
    {
        try {
            /**
             * @var AntiXSS $xss
             */
            $xss = container()->get(AntiXSS::class);

            /**
             * @var ColorModel $colorModel
             */
            $colorModel = container()->get(ColorModel::class);

            // get all products values
            $stock = input()->post('inp-add-product-stock-count');
            $maxCart = input()->post('inp-add-product-max-count');
            $color = input()->post('inp-add-product-color');
            $size = input()->post('inp-add-product-size');
            $guarantee = input()->post('inp-add-product-guarantee');
            $weight = input()->post('inp-add-product-weight');
            $price = input()->post('inp-add-product-price');
            $disPrice = input()->post('inp-add-product-discount-price');
            $disDate = input()->post('inp-add-product-discount-date');
            $pAvailability = input()->post('inp-add-product-product-availability');

            // create products object
            $productObj = [];
            $i = 0;
            /**
             * @var InputItem $productPrice
             */
            foreach ($price as $productPrice) {
                $s = array_shift($stock);
                $mc = array_shift($maxCart);
                $dp = array_shift($disPrice);
                $c = array_shift($color);

                if (!empty($s) && !empty($mc) && !empty($dp) && !empty($c)) {
                    $colorName = $colorModel->getFirst(['name'], 'hex=:hex', ['hex' => $color])['name'];

                    $productObj[$i]['price'] = $xss->xss_clean($productPrice->getValue());
                    $productObj[$i]['stock_count'] = $xss->xss_clean($s);
                    $productObj[$i]['max_cart'] = $xss->xss_clean($mc);
                    $productObj[$i]['discount_price'] = $xss->xss_clean($dp);
                    $productObj[$i]['color_hex'] = $xss->xss_clean($c);
                    $productObj[$i]['color_name'] = $xss->xss_clean($colorName);
                    $productObj[$i]['size'] = $xss->xss_clean(array_shift($size)) ?: null;
                    $productObj[$i]['guarantee'] = $xss->xss_clean(array_shift($guarantee)) ?: null;
                    $productObj[$i]['weight'] = $xss->xss_clean(array_shift($weight)) ?: null;
                    $productObj[$i]['discount_until'] = $xss->xss_clean(array_shift($disDate)) ?: null;
                    $productObj[$i]['available'] = is_value_checked(array_shift($pAvailability)) ? DB_YES : DB_NO;
                }
            }
        } catch (\Exception $e) {
            return [];
        }
        return $productObj;
    }

    /**
     * @return array
     */
    public function createGalleryArray(): array
    {
        $galleryArr = [];
        try {
            /**
             * @var AntiXSS $xss
             */
            $xss = container()->get(AntiXSS::class);

            $gallery = input()->post('inp-add-product-gallery-img');

            /**
             * @var InputItem $item
             */
            foreach ($gallery as $item) {
                $g = $xss->xss_clean($item->getValue());
                if (is_image_exists(get_image_name($g))) {
                    $galleryArr[] = $g;
                }
            }
        } catch (\Exception $e) {
            return [];
        }
        return $galleryArr;
    }

    /**
     * @param $properties
     * @param $sub_properties
     * @return array
     */
    public function assembleProductProperties($properties, $sub_properties): array
    {
        try {
            $assembledProperties = [];
            $counter = 0;
            foreach ($properties as $k => $main) {
                $children = [];
                $title = $main['title']->getValue();
                if ('' != trim($title)) {
                    foreach ($sub_properties[$k] as $sub) {
                        $subTitle = $sub['sub-title']->getValue();
                        if ('' != trim($subTitle)) {
                            $subProperties = $sub['sub-properties']->getValue();

                            $children[] = [
                                'title' => $subTitle,
                                'properties' => $subProperties,
                            ];
                        }
                    }

                    $assembledProperties[$counter] = [
                        'title' => $title,
                    ];
                    $assembledProperties[$counter]['children'] = $children;
                    $counter++;
                }
            }
            return $assembledProperties;
        } catch (\Exception $e) {
            return [];
        }
    }
}