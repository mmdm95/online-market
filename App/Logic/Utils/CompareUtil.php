<?php

namespace App\Logic\Utils;

use App\Logic\Models\ProductModel;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;

class CompareUtil
{
    /**
     * @return array
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function paginatedCompareProducts(): array
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

        $category = session()->get('compare_category_id_sess', null);
        if (!empty($category)) {
            $where .= " AND pa.category_id=:p_category_one";
            $bindValues["p_category_one"] = $category;
        } else {
            return [
                'items' => [],
                'pagination' => [
                    'base_url' => url('home.search')->getRelativeUrlTrimmed(),
                    'total' => 0,
                    'first_page' => 1,
                    'last_page' => 1,
                    'current_page' => intval($page),
                ],
            ];
        }

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

        // other info
        $total = $productModel->getLimitedProductCount($where, $bindValues);
        $lastPage = ceil($total / $limit);

        return [
            'items' => $productModel->getLimitedProduct(
                $where,
                $bindValues,
                ['pa.stock_count DESC', 'pa.product_availability DESC', 'pa.is_available DESC', 'pa.product_id DESC'],
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
     * @param int $product_id
     * @param array $properties
     * @return void
     */
    public static function assembleCompareProperties(int $product_id, array $properties)
    {
        self::setAssembleCompareArray();
        $arr = self::getAssembleCompareProperties();

        if (!empty($properties)) {
            foreach ($properties as $property) {
                $mainTitle = self::hasSameMainTitle($property['title']);
                if ($mainTitle['has']) {
                    foreach ($property['children'] as $child) {
                        $subTitle = self::hasSameSubTitle($child['title'], $mainTitle['arr']);
                        if ($subTitle['has']) {
                            if (!is_array($arr[$mainTitle['idx']]['children'][$subTitle['idx']]['properties'])) {
                                $arr[$mainTitle['idx']]['children'][$subTitle['idx']]['properties'] = [];
                            }
                            $arr[$mainTitle['idx']]['children'][$subTitle['idx']]['properties'][$product_id] = $child['properties'];
                        } else {
                            $arr[$mainTitle['idx']]['children'][] = [
                                'title' => $child['title'],
                                'properties' => [
                                    $product_id => $child['properties'],
                                ],
                            ];
                        }
                    }
                } else {
                    $children = [];
                    foreach ($property['children'] as $child) {
                        $children[] = [
                            'title' => $child['title'],
                            'properties' => [
                                $product_id => $child['properties'],
                            ],
                        ];
                    }

                    $arr[] = [
                        'title' => $property['title'],
                        'children' => $children,
                    ];
                }
            }

            session()->set('compare_assemble_properties_sess', $arr);
        }
    }

    /**
     * @return void
     */
    public static function resetAssembleCompareProperties()
    {
        session()->set('compare_assemble_properties_sess', []);
    }

    /**
     * @return array
     */
    public static function getAssembleCompareProperties(): array
    {
        return session()->get('compare_assemble_properties_sess', []);
    }


    /**
     * @return void
     */
    private static function setAssembleCompareArray()
    {
        $p = session()->get('compare_assemble_properties_sess');
        if (is_null($p)) {
            session()->set('compare_assemble_properties_sess', []);
        }
    }

    /**
     * @param string $title
     * @return array
     */
    private static function hasSameMainTitle(string $title): array
    {
        return self::hasTitleInArr($title, self::getAssembleCompareProperties(), 'children');
    }

    /**
     * @param string $title
     * @param array $searchingArray
     * @return array
     */
    private static function hasSameSubTitle(string $title, array $searchingArray): array
    {
        return self::hasTitleInArr($title, $searchingArray);
    }

    /**
     * @param $title
     * @param $arr
     * @param string|null $extraKey
     * @return array
     */
    private static function hasTitleInArr($title, $arr, ?string $extraKey = null): array
    {
        $has = false;
        $key = -1;

        foreach ($arr as $k => $value) {
            if (trim($value['title']) == trim($title)) {
                $has = true;
                $key = $k;
                break;
            }
        }

        return [
            'has' => $has,
            'arr' => $has ? (!empty($extraKey) ? $arr[$key][$extraKey] : $arr[$key] ?? []) : [],
            'idx' => $key,
        ];
    }
}
