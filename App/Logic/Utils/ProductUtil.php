<?php

namespace App\Logic\Utils;

use App\Logic\Models\ProductModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;

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
        $limit = input()->get('each_page', config()->get('settings.product_each_page.value'));
        $limit = $limit > 0 ? $limit : 1;
        $page = input()->get('page', 1);
        $offset = ((int)$page - 1) * $limit;

        // where clause
        $where = 'pa.publish=:pub';
        $bindValues = ['pub' => DB_YES];
        // query search parameter
        $q = input()->get('q', '');
        if (is_string($q) && !empty($q)) {
            $where .= ' AND (pa.category_name LIKE :q_p_category';
            $where .= ' OR pa.fa_title LIKE :q_p_fa_title';
            $where .= ' OR pa.slug LIKE :q_p_slug';
            $where .= ' OR pa.keywords LIKE :q_p_keywords';
            $where .= ')';
            $bindValues['q_p_category'] = '%' . $q . '%';
            $bindValues['q_p_fa_title'] = '%' . StringUtil::toPersian($q) . '%';
            $bindValues['q_p_slug'] = '%' . StringUtil::slugify($q) . '%';
            $bindValues['q_p_keywords'] = '%' . $q . '%';
        }
        // category parameter
        $category = input()->get('category', 0);
        if (is_numeric($category) && !empty($category)) {
            $where .= ' AND pa.category_id=:p_category_id';
            $bindValues['p_category_id'] = $category;
        }
        // price parameter
        $price = input()->get('price_filter', null);
        if (empty($price) && isset($price['min']) && isset($price['max'])) {
            $where .= " AND (CASE WHEN (pa.discount_until IS NULL OR pa.discount_until >= UNIX_TIMESTAMP()) THEN ";
            $where .= "pa.discounted_price<=:p_max_price AND pa.discounted_price>=:p_min_price";
            $where .= " ELSE ";
            $where .= "pa.price<=:p_max_price AND pa.price>=:p_min_price";
            $where .= " END)";
            $bindValues['p_min_price'] = (int)$price['min'];
            $bindValues['p_max_price'] = (int)$price['max'];
        }
        // color parameter
        $color = input()->get('color', '');
        if (empty($color)) {
            $where .= ' AND pa.color=:p_color';
            $bindValues['p_color'] = $color;
        }
        // size parameter
        $size = input()->get('size', '');
        if (!empty($size)) {
            $where .= ' AND pa.size=:p_size';
            $bindValues['p_size'] = $size;
        }
        // model parameter
        $model = input()->get('model', '');
        if (!empty($model)) {
            $where .= ' AND pa.model=:p_model';
            $bindValues['p_model'] = $model;
        }
        // brands parameter
        $brands = input()->get('brands', null);
        if (!empty($brands) && is_array($brands)) {
            $inClause = '';
            foreach ($brands as $k => $brand) {
                $inClause .= ":brand{$k},";
                $bindValues["brand{$k}"] = $brand;
            }
            $inClause = trim($inClause, ',');
            $where .= " AND pa.brand_id IN ({$inClause})";
        }
        // is available parameter
        $isAvailable = input()->get('is_available', null);
        if (!empty($isAvailable)) {
            $where .= ' AND pa.is_available=:p_is_available';
            $where .= ' AND pa.max_cart_count>:p_max_cart_count';
            $where .= ' AND pa.stock_count>:p_stock_count';
            $bindValues['p_is_available'] = DB_YES;
            $bindValues['p_max_cart_count'] = 0;
            $bindValues['p_stock_count'] = 0;
        }
        // is special parameter
        $isSpecial = input()->get('is_special', null);
        if (!empty($isSpecial)) {
            $where .= ' AND pa.is_special=:p_is_special';
            $bindValues['p_is_special'] = DB_YES;
        }
        // order by parameter
        $orderBy = [];
        $order = input()->get('order_by', null);
        if (!empty($order)) {
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

        // other info
        $total = $productModel->getLimitedProductCount($where, $bindValues);
        $lastPage = ceil($total / $limit);

        return [
            'items' => $productModel->getLimitedProduct(
                $where,
                $bindValues,
                $orderBy,
                $limit,
                $offset,
                ),
            'pagination' => [
                'base_url' => url('home.search')->getOriginalUrl(),
                'total' => $total,
                'first_page' => 1,
                'last_page' => $lastPage,
                'current_page' => $page,
            ],
        ];
    }
}