<?php

namespace App\Logic\Utils;

use App\Logic\Models\CommentModel;

class CommentUtil
{
    public function paginatedComment($product_id): array
    {
        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);

        // parse query
        try {
            $page = input()->get('page', 1)->getValue();
        } catch (\Exception $e) {
            $page = 1;
        }
        $limit = 10;
        $offset = ((int)$page - 1) * $limit;

        $where = '';
        $bindValues = [];

        // where clause
        // - add condition clause
        $where .= 'c.the_condition=:condition';
        $bindValues['condition'] = COMMENT_CONDITION_ACCEPT;
        // - add product id to where
        $where .= ' AND c.product_id=:p_id';
        $bindValues['p_id'] = $product_id;

        // other info
        $total = $commentModel->getCommentsCount($where, $bindValues);
        $lastPage = ceil($total / $limit);

        return [
            'items' => $commentModel->getComments(
                $where,
                $bindValues,
                $limit,
                $offset,
                ['c.id DESC'],
                [
                    'c.product_id',
                    'c.user_id',
                    'c.body',
                    'c.reply',
                    'c.status',
                    'c.the_condition',
                    'c.sent_at',
                    'c.reply_at',
                    'u.first_name',
                    'u.image AS user_image',
                ]
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
}