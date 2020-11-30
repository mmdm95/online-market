<?php

namespace App\Logic\Utils;

use App\Logic\Models\BaseModel;
use App\Logic\Models\Model;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class SliderUtil
{
    /**
     * @param array $info
     * @return array
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function getTabSliderItems(array $info): array
    {
        if (!isset($info['type'])) return [];

        /**
         * @var Model $model
         */
        $model = \container()->get(Model::class);

        $select = $model->select();
        $select
            ->from(BaseModel::TBL_PRODUCT_ADVANCED . ' AS pa')
            ->cols([
                'pa.title', 'pa.slug', 'pa.image', 'pa.category_id', 'pa.is_special', 'pa.product_availability',
                'pa.code', 'pa.product_id', 'pa.price', 'pa.discounted_price', 'pa.discount_until', 'pa.is_available',
                'pa.festival_id', 'pa.festival_discount',
            ]);

        $info['limit'] = isset($info['limit']) && (int)$info['limit'] > 0 ? (int)$info['limit'] : 3;
        switch ($info['type']) {
            case SLIDER_TABBED_NEWEST:
                $select->orderBy(['pa.product_id DESC']);
                break;
            case SLIDER_TABBED_MOST_SELLER:
                $select->join(
                    'INNER',
                    BaseModel::TBL_ORDER_ITEMS . ' AS oi',
                    'oi.product_id=pa.product_id'
                )
                    ->where('oi.is_returned=:is_ret')
                    ->bindValue('is_ret', DB_NO);
                break;
            case SLIDER_TABBED_FEATURED:
                $select
                    ->where('pa.is_special=:is_spec')
                    ->bindValue('is_spec', DB_YES);
                break;
            case SLIDER_TABBED_MOST_DISCOUNT:
                $select
                    ->orderBy(['CASE WHEN (pa.discount_until IS NULL OR pa.discount_until >= UNIX_TIMESTAMP()) AND pa.stock_count > 0 AND pa.is_available = 1 THEN 0 ELSE 1 END', '((pa.price - pa.discount_price) / pa.price * 100) DESC', 'pa.discount_price ASC', 'pa.id DESC']);
                break;
            case SLIDER_TABBED_SPECIAL:
                $select
                    ->join(
                        'INNER',
                        BaseModel::TBL_PRODUCT_FESTIVAL . ' AS pf',
                        'pa.product_id=pf.product_id'
                    )
                    ->where('pa.festival_id IS NOT NULL')
                    ->where('pa.festival_start<=:start')
                    ->bindValue('start', time());
                break;
            default:
                return [];
        }

        $select
            ->where('pa.product_availability=:is_available')
            ->bindValue('pa.is_available', DB_YES)
            ->limit($info['limit'])
            ->orderBy(['pa.title DESC'])
            ->groupBy(['pa.product_id']);

        if (isset($info['category']) && !empty($info['category'])) {
            $select
                ->where('pa.category_id=:cat_id')
                ->bindValue('cat_id', $info['category']);
        }

        return $model->get($select);
    }

    /**
     * @return array
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function getSpecialsSlider(): array
    {
        /**
         * @var Model $model
         */
        $model = \container()->get(Model::class);

        $select = $model->select();

        $select
            ->from(BaseModel::TBL_PRODUCT_ADVANCED . ' AS pa')
            ->cols([
                'pa.title', 'pa.slug', 'pa.image', 'pa.category_id', 'pa.product_availability',
                'pa.code', 'pa.product_id', 'pa.price', 'pa.discounted_price', 'pa.discount_until',
                'pa.is_available', 'pa.festival_id', 'pa.festival_discount', 'pa.festival_expire'
            ])
            ->join(
                'INNER',
                BaseModel::TBL_PRODUCT_FESTIVAL . ' AS pf',
                'pa.product_id=pf.product_id'
            )
            ->where('pa.festival_id IS NOT NULL')
            ->where('pa.festival_start<=:start')
            ->bindValue('start', time())
            ->limit(10);

        return $model->get($select);
    }
}