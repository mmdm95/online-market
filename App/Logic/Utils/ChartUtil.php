<?php

namespace App\Logic\Utils;

use App\Logic\Models\ChartModel;
use App\Logic\Models\OrderBadgeModel;

class ChartUtil
{
    /**
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getBoughStatus(): array
    {
        $data = [];
        $data['items'] = [];
        $data['times'] = [];
        $data['colors'] = [];
        $data['titles'] = [];

        $dates = self::createDates();
        $tmpDate = [];

        foreach ($dates as $date) {
            $tmpDate[$date['key']] = 0;
        }

        /**
         * @var OrderBadgeModel $badgeModel
         */
        $badgeModel = container()->get(OrderBadgeModel::class);
        $badges = $badgeModel->get(['code', 'title', 'color'], null, [], ['id ASC']);
        foreach ($badges as $badge) {
            $data['colors'][] = $badge['color'];
            $data['titles'][] = $badge['title'];
            $data['items'][$badge['code']] = [
                'title' => $badge['title'],
                'data' => $tmpDate,
            ];
        }

        foreach ($dates as $date) {
            /**
             * @var ChartModel $chartModel
             */
            $chartModel = container()->get(ChartModel::class);
            $counts = $chartModel->getBoughtProductsByStatusCount($date['prev'], $date['curr']);

            foreach ($counts as $item) {
                $data['items'][$item['status_code']]['data'][$date['key']] = $item['badge_count'];
            }

            $data['times'][] = $date['key'];
        }

        return $data;
    }

    /**
     * @return array
     */
    private static function createDates(): array
    {
        $dates = [];
        for ($i = 4; $i >= 1; $i--) {
            if ($i == 1) {
                $dates[4 - $i]['curr'] = strtotime('tomorrow, -1 second', time());
            } else {
                $dates[4 - $i]['curr'] = strtotime('tomorrow, -1 second, -' . ($i - 1) . ' week' . ($i > 2 ? 's' : ''), time());
            }
            $dates[4 - $i]['prev'] = strtotime('today, -' . $i . ' week' . ($i > 1 ? 's' : ''), time());
            $dates[4 - $i]['key'] = Jdf::jdate(CHART_BOUGHT_STATUS_TIME_FORMAT, $dates[4 - $i]['prev'])
                . "\n"
                . Jdf::jdate(CHART_BOUGHT_STATUS_TIME_FORMAT, $dates[4 - $i]['curr']);
        }
        return $dates;
    }
}
