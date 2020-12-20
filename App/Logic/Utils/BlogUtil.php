<?php

namespace App\Logic\Utils;

use App\Logic\Models\BlogModel;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;

class BlogUtil
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
    public function paginatedBlog(): array
    {
        /**
         * @var BlogModel $blogModel
         */
        $blogModel = container()->get(BlogModel::class);
        // parse query
        try {
            $limit = input()->get('each_page', config()->get('settings.blog_each_page.value'))->getValue();
            $page = input()->get('page', 1)->getValue();
        } catch (\Exception $e) {
            $limit = config()->get('settings.blog_each_page.value');
            $page = 1;
        }
        $limit = $limit > 0 ? $limit : 1;
        $offset = ($page - 1) * $limit;

        $withCategory = false;
        $withUsers = false;
        // where clause
        $where = 'b.publish=:pub';
        $bindValues = ['pub' => DB_YES];
        // query search parameter
        $q = input()->get('q', null);
        if (!is_array($q) && !empty($q)) {
            $q = $q->getValue();
            if (is_string($q) && !empty($q)) {
                $withCategory = true;
                $withUsers = true;
                //-----
                $where .= ' AND (bc.name LIKE :b_category';
                $where .= ' OR b.fa_title LIKE :b_fa_title';
                $where .= ' OR b.slug LIKE :b_slug';
                $where .= ' OR b.keywords LIKE :b_keywords';
                $where .= ' OR b.writer LIKE :b_writer';
                $where .= ' OR b.abstract LIKE :b_abstract';
                $where .= ' OR bc.slug LIKE :b_slug';
                $where .= ' OR u.first_name LIKE :c_f_name';
                $where .= ')';
                $bindValues['b_category'] = '%' . $q . '%';
                $bindValues['b_fa_title'] = '%' . StringUtil::toPersian($q) . '%';
                $bindValues['b_slug'] = '%' . StringUtil::slugify($q) . '%';
                $bindValues['b_keywords'] = '%' . $q . '%';
                $bindValues['b_writer'] = '%' . $q . '%';
                $bindValues['b_abstract'] = '%' . $q . '%';
                $bindValues['b_slug'] = '%' . $q . '%';
                $bindValues['c_f_name'] = '%' . $q . '%';
            }
        }
        // time parameter for blog in that entire day
        $time = input()->get('time', null);
        if (!is_array($time)) {
            $time = $time->getValue();
            if (is_numeric($time) && !empty($time)) {
                $where .= ' AND b.created_at >= :b_t1';
                $where .= ' AND b.created_at <= :b_t2';
                $bindValues['b_t1'] = get_today_start_of_time($time);
                $bindValues['b_t2'] = get_today_end_of_time($time);
            }
        }
        // tag parameter
        $tag = input()->get('tag', null);
        if (!is_array($tag)) {
            $tag = $tag->getValue();
            if (is_string($tag) && !empty($tag)) {
                $where .= ' AND b.keywords LIKE :be_keywords';
                $bindValues['be_keywords'] = '%' . $tag . '%';
            }
        }
        // category parameter
        $category = input()->get('category', null);
        if (!is_array($category)) {
            $category = $category->getValue();
            if (is_numeric($category) && !empty($category)) {
                $withCategory = true;
                //-----
                $where .= ' AND bc.id=:b_category_id';
                $bindValues['b_category_id'] = $category;
            }
        }

        // other info
        $total = $blogModel->getLimitedBlogCount($where, $bindValues, $withCategory, $withUsers);
        $lastPage = ceil($total / $limit);

        return [
            'items' => $blogModel->getLimitedBlog(
                $where,
                $bindValues,
                $limit,
                $offset,
                $withCategory,
                $withUsers
            ),
            'pagination' => [
                'base_url' => url('home.blog')->getOriginalUrl(),
                'total' => $total,
                'first_page' => 1,
                'last_page' => $lastPage,
                'current_page' => $page,
            ],
        ];
    }
}