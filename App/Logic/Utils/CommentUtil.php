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

        // where clause
        $where = 'c.status=:status';
        $bindValues = ['status' => COMMENT_STATUS_ACCEPT];
        // add product id to where
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