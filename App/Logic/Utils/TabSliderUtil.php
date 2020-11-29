<?php

namespace App\Logic\Utils;

use App\Logic\Models\BaseModel;
use App\Logic\Models\Model;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class TabSliderUtil
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
            ->cols(['pa.*']);

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
            case SLIDER_TABBED_SPECIAL:
                $select
                    ->join(
                        'INNER',
                        BaseModel::TBL_PRODUCT_FESTIVAL . ' AS pf',
                        'pa.product_id=pf.product_id'
                    )
                    ->where('pa.festival_id IS NOT NULL')
                    ->where('pa.is_main_festival=:is_main')
                    ->bindValue('is_main', DB_YES);
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

        return $model->get($select);
    }
}