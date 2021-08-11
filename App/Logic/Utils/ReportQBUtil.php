<?php

namespace App\Logic\Utils;

class ReportQBUtil
{
    /*-----------------------------------------------
    |------ Use this as query builder template ------
        [
            'id' => '',
            'label' => '',
            'type' => '',
            'input' => '',
            'values' => [
            ],
            'operators' => [
            ],
        ],
    -------------------------------------------------
    -----------------------------------------------*/

    /**
     * [u] and [r] are aliases that used in [ReportUserController]
     * in fetching datatable data
     *
     * @return array
     */
    public static function getUserQB(): array
    {
        return [
            [
                'id' => 'u.username',
                'label' => 'نام کاربری',
                'type' => 'integer',
                'operators' => [
                    'equal',
                    'not_equal',
                    'begins_with',
                    'not_begins_with',
                    'contains',
                    'not_contains',
                    'ends_with',
                    'not_ends_with',
                    'is_empty',
                    'is_not_empty',
                    'is_null',
                    'is_not_null'
                ],
            ],
            [
                'id' => 'u.first_name',
                'label' => 'نام',
                'type' => 'string',
                'operators' => [
                    'equal',
                    'not_equal',
                    'begins_with',
                    'not_begins_with',
                    'contains',
                    'not_contains',
                    'ends_with',
                    'not_ends_with',
                    'is_empty',
                    'is_not_empty',
                    'is_null',
                    'is_not_null'
                ],
            ],
            [
                'id' => 'u.last_name',
                'label' => 'نام خانوادگی',
                'type' => 'string',
                'operators' => [
                    'equal',
                    'not_equal',
                    'begins_with',
                    'not_begins_with',
                    'contains',
                    'not_contains',
                    'ends_with',
                    'not_ends_with',
                    'is_empty',
                    'is_not_empty',
                    'is_null',
                    'is_not_null'
                ],
            ],
            [
                'id' => 'r.name',
                'label' => 'نقش',
                'type' => 'string',
                'input' => 'select',
                'operators' => [
                    'equal',
                    'not_equal'
                ],
                'values' => ROLES_ARRAY_ACCEPTABLE,
            ],
            [
                'id' => 'u.is_activated',
                'label' => 'وضعیت فعال بودن',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'غیر فعال',
                    1 => 'فعال',
                ],
                'operators' => [
                    'equal',
                    'not_equal'
                ],
            ],
            [
                'id' => 'u.is_login_locked',
                'label' => 'عملیات ورود فقل شده',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'خیر',
                    1 => 'بله',
                ],
                'operators' => [
                    'equal',
                    'not_equal'
                ],
            ],
            [
                'id' => 'u.is_deleted',
                'label' => 'حذف شده',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'خیر',
                    1 => 'بله',
                ],
                'operators' => [
                    'equal',
                    'not_equal'
                ],
            ],
            [
                'id' => 'u.ban',
                'label' => 'بن شده',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'خیر',
                    1 => 'بله',
                ],
                'operators' => [
                    'equal',
                    'not_equal'
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getProductQB(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getOrderQB(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getWalletQB(): array
    {
        return [];
    }
}
