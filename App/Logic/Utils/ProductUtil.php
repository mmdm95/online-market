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
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class ProductUtil
{
    /**
     * @return array
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
        $limit = max($limit, 1);
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
            $q = urldecode($q);
            if (!empty(trim($q))) {
                $where .= ' AND (pa.category_name LIKE :q_p_category';
                $where .= ' OR pa.title LIKE :q_p_the_title';
                $where .= ' OR pa.fa_title LIKE :q_p_fa_title';
                $where .= ' OR pa.slug LIKE :q_p_slug';
                $where .= ' OR pa.keywords LIKE :q_p_keywords';
                $where .= ' OR pa.brand_latin_name LIKE :q_p_la_brand';
                $where .= ' OR pa.brand_fa_name LIKE :q_p_fa_brand';
                $where .= ' OR pa.festival_title LIKE :q_p_festival_title';
                $where .= ')';
                $bindValues['q_p_category'] = '%' . $q . '%';
                $bindValues['q_p_the_title'] = '%' . $q . '%';
                $bindValues['q_p_fa_title'] = '%' . StringUtil::toPersian($q) . '%';
                $bindValues['q_p_slug'] = '%' . StringUtil::slugify($q) . '%';
                $bindValues['q_p_keywords'] = '%' . $q . '%';
                $bindValues['q_p_fa_brand'] = '%' . $q . '%';
                $bindValues['q_p_la_brand'] = '%' . $q . '%';
                $bindValues['q_p_festival_title'] = '%' . $q . '%';
            }
        }
        // tag parameter
        $tag = input()->get('tag', '');
        if (!is_array($tag)) {
            $tag = $tag->getValue();
            $tag = urldecode($tag);
            if (is_string($tag) && !empty(trim($tag))) {
                $where .= ' AND (pa.fa_title LIKE :tag_p_fa_title';
                $where .= ' OR pa.slug LIKE :tag_p_slug';
                $where .= ' OR pa.keywords LIKE :tag_p_keywords)';
                $bindValues['tag_p_fa_title'] = '%' . StringUtil::toPersian($tag) . '%';
                $bindValues['tag_p_slug'] = '%' . StringUtil::slugify($tag) . '%';
                $bindValues['tag_p_keywords'] = '%' . $tag . '%';
            }
        }
        // category parameter
        $category = input()->get('category', null);
        if (!is_array($category)) {
            $category = $category->getValue();
            if (is_numeric($category) && $category != DEFAULT_OPTION_VALUE) {
                $where .= ' AND (pa.category_id=:p_category_id';
                $where .= ' OR pa.category_parent_id=:p_category_parent_id';
                $where .= ' OR pa.category_all_parents_id REGEXP :p_category_all_parents_id)';
                $bindValues['p_category_id'] = $category;
                $bindValues['p_category_parent_id'] = $category;
                $bindValues['p_category_all_parents_id'] = getDBCommaRegexString($category);

                // if there is category, we'll have attribute values too!
                /**
                 * @var ProductAttributeModel $attrModel
                 */
                $attrModel = container()->get(ProductAttributeModel::class);

                $attrIds = $attrModel->getProductAttrValuesOfCategory($category, ['pa.id', 'pa.type'], ['pav.id ASC'], ['pa.id']);
                $inClause = '';
                foreach ($attrIds as $k2 => $attrId) {
                    $attrs = input()->get('attrs_' . $attrId['id'], null);
                    if ($attrId['type'] == PRODUCT_SIDE_SEARCH_ATTR_TYPE_MULTI_SELECT) {
                        if (is_array($attrs)) {
                            /**
                             * @var IInputItem $attr
                             */
                            foreach ($attrs as $k => $attr) {
                                if (!is_array($attr) && !empty($attr->getValue())) {
                                    $inClause .= ":p_attr$k$k2,";
                                    $bindValues["p_attr$k$k2"] = urldecode($attr->getValue());
                                }
                            }
                        } elseif (!empty($attrs->getValue())) {
                            $inClause .= ":p_attr$k2,";
                            $bindValues["p_attr$k2"] = urldecode($attrs->getValue());
                        }
                    } elseif ($attrId['type'] == PRODUCT_SIDE_SEARCH_ATTR_TYPE_SINGLE_SELECT && !is_array($attrs)) {
                        if (!empty($attrs->getValue())) {
                            $inClause .= ":p_attr$k2,";
                            $bindValues["p_attr$k2"] = urldecode($attrs->getValue());
                        }
                    }
                }
                $inClause = trim($inClause, ',');
                if (!empty($inClause)) {
                    $where .= " AND pap.p_attr_val_id IN ({$inClause})";
                }
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
                    $inClause .= ":p_color$k,";
                    $bindValues["p_color$k"] = urldecode($color->getValue());
                }
            }
            $inClause = trim($inClause, ',');
            if (!empty($inClause)) {
                $where .= " AND pa.color_hex IN ({$inClause})";
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
                    $inClause .= ":p_size$k,";
                    $bindValues["p_size$k"] = urldecode($size->getValue());
                }
            }
            $inClause = trim($inClause, ',');
            if (!empty($inClause)) {
                $where .= " AND pa.size IN ({$inClause})";
            }
        } elseif (null !== $sizes && !empty($sizes->getValue())) {
            $where .= " AND pa.size=:p_size_one";
            $bindValues["p_size_one"] = $sizes->getValue();
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
                    $inClause .= ":p_brand$k,";
                    $bindValues["p_brand$k"] = $brand->getValue();
                }
            }
            $inClause = trim($inClause, ',');
            if (!empty($inClause)) {
                $where .= " AND pa.brand_id IN ({$inClause})";
            }
        } elseif (null !== $brands && !empty($brands->getValue())) {
            $where .= " AND pa.brand_id=:p_brand_one";
            $bindValues["p_brand_one"] = $brands->getValue();
        }
        // is available parameter
        $isAvailable = input()->get('is_available', null);
        if (!is_array($isAvailable)) {
            $isAvailable = $isAvailable->getValue();
            if (is_numeric($isAvailable)) {
                $where .= ' AND pa.product_availability=:p_p_available';
                $where .= ' AND pa.is_available=:p_is_available';
                $where .= ' AND pa.max_cart_count>:p_max_cart_count';
                $where .= ' AND pa.stock_count>:p_stock_count';
                $bindValues['p_p_available'] = DB_YES;
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
                    case PRODUCT_ORDERING_CHEAPEST: // cheapest
                        $orderBy = ['pa.price ASC', 'pa.product_id DESC'];
                        break;
                    case PRODUCT_ORDERING_MOST_EXPENSIVE: // most expensive
                        $orderBy = ['pa.price DESC', 'pa.product_id DESC'];
                        break;
                    case PRODUCT_ORDERING_MOST_VIEWED: // most view
                        $orderBy = ['pa.view_count DESC', 'pa.product_id DESC'];
                        break;
                    case PRODUCT_ORDERING_MOST_DISCOUNT: // most discount
                        $orderBy = ['CASE WHEN (pa.discount_until IS NULL OR pa.discount_until >= UNIX_TIMESTAMP()) AND pa.stock_count > 0 THEN 0 ELSE 1 END', '((pa.price - pa.discounted_price) / pa.price * 100) DESC', 'pa.discounted_price ASC', 'pa.product_id DESC'];
                        break;
                }
            }
        }

        // other info
        $total = $productModel->getProductsWithAttrsCount($where, $bindValues);
        $lastPage = ceil($total / $limit);

        return [
            'items' => $productModel->getProductsWithAttrs(
                $where,
                $bindValues,
                $orderBy,
                $limit,
                $offset,
                ['pa.product_id'],
                ['pa.*']
            ),
            'pagination' => [
                'base_url' => url('home.search')->getRelativeUrlTrimmed(),
                'total' => $total,
                'first_page' => 1,
                'last_page' => $lastPage,
                'current_page' => intval($page),
            ],
        ];
    }

    /**
     * @param $product_id
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
            ]),
            ['pa.product_id DESC'],
            null,
            0,
            ['pa.product_id'],
            ['pa.*',]
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
     * @param bool $isUpdate
     * @return array
     */
    public function createProductObject(bool $isUpdate = false): array
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
            $stock = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-stock-count');
            $maxCart = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-max-count');
            $color = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-color');
            $size = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-size');
            $guarantee = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-guarantee');
            $weight = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-weight');
            $price = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-price');
            $disPrice = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-discount-price');
            $disDate = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-discount-date');
            $considerDisDate = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-consider-discount-date');
            $pAvailability = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-product-availability');

            // create products object
            $productObj = [];
            $i = 0;
            /**
             * @var InputItem $productPrice
             */
            foreach ($price as $productPrice) {
                /**
                 * @var InputItem $s
                 */
                $s = is_array($stock) ? array_shift($stock) : new InputItem('', null);
                /**
                 * @var InputItem $mc
                 */
                $mc = is_array($maxCart) ? array_shift($maxCart) : new InputItem('', null);
                /**
                 * @var InputItem $dp
                 */
                $dp = is_array($disPrice) ? array_shift($disPrice) : new InputItem('', null);
                /**
                 * @var InputItem $c
                 */
                $c = is_array($color) ? array_shift($color) : new InputItem('', null);
                $c = null === $c ? new InputItem('', null) : $c;
                /**
                 * @var InputItem $eachSize
                 */
                $eachSize = is_array($size) ? array_shift($size) : new InputItem('', null);
                /**
                 * @var InputItem $eachGuarantee
                 */
                $eachGuarantee = is_array($guarantee) ? array_shift($guarantee) : new InputItem('', null);
                /**
                 * @var InputItem $eachWeight
                 */
                $eachWeight = is_array($weight) ? array_shift($weight) : new InputItem('', null);
                /**
                 * @var InputItem $eachDisDate
                 */
                $eachDisDate = is_array($disDate) ? array_shift($disDate) : new InputItem('', null);
                /**
                 * @var InputItem $eachCDD
                 */
                $eachCDD = is_array($considerDisDate) ? array_shift($considerDisDate) : new InputItem('', null);
                /**
                 * @var InputItem $eachPAvailability
                 */
                $eachPAvailability = is_array($pAvailability) ? array_shift($pAvailability) : new InputItem('', null);

                if (
                    !empty($s->getValue()) && !empty($mc->getValue()) && !empty($dp->getValue()) &&
                    (!empty($c->getValue()) || $isUpdate)
                ) {
                    $colorName = $c->getValue()
                        ? $colorModel->getFirst(['name'], 'hex=:hex', ['hex' => strtolower($c->getValue())])['name']
                        : null;

                    $productObj[$i]['price'] = $xss->xss_clean($productPrice->getValue());
                    $productObj[$i]['stock_count'] = $xss->xss_clean($s->getValue());
                    $productObj[$i]['max_cart'] = $xss->xss_clean($mc->getValue());
                    $productObj[$i]['discount_price'] = $xss->xss_clean($dp->getValue());
                    $productObj[$i]['color_hex'] = $xss->xss_clean($c->getValue()) ?: null;
                    $productObj[$i]['color_name'] = $xss->xss_clean($colorName);
                    $productObj[$i]['size'] = $xss->xss_clean($eachSize->getValue()) ?: null;
                    $productObj[$i]['guarantee'] = $xss->xss_clean($eachGuarantee->getValue()) ?: null;
                    $productObj[$i]['weight'] = $xss->xss_clean($eachWeight->getValue()) ?: null;
                    $productObj[$i]['discount_until'] = is_null($eachCDD->getValue()) ? ($xss->xss_clean($eachDisDate->getValue()) ?: null) : null;
                    $productObj[$i]['available'] = is_value_checked($eachPAvailability->getValue()) ? DB_YES : DB_NO;
                    $i++;
                }
            }
        } catch (\Exception $e) {
            echo $e;
            return [];
        }
        return $productObj;
    }

    /**
     * @param bool $isUpdate
     * @return array
     */
    public function createGalleryArray($isUpdate = false): array
    {
        $galleryArr = [];
        try {
            /**
             * @var AntiXSS $xss
             */
            $xss = container()->get(AntiXSS::class);

            $gallery = input()->post('inp-' . ($isUpdate ? 'edit' : 'add') . '-product-gallery-img');

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