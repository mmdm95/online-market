<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\BlogModel;
use App\Logic\Utils\BlogUtil;
use Jenssegers\Agent\Agent;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class BlogController extends AbstractHomeController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function index()
    {
        /**
         * @var BlogModel $blogModel
         */
        $blogModel = container()->get(BlogModel::class);

        $last_blog = $blogModel->get([
            'id', 'slug', 'title', 'image', 'created_at'
        ], 'publish=:pub', [
            'pub' => DB_YES
        ], ['id DESC'], 4);

        $tmpTags = array_map(function ($val) {
            return $val['keywords'];
        }, $last_blog);
        $tags = [];
        foreach ($tmpTags as $tag) {
            $allTags = explode(',', $tag);
            foreach ($allTags as $t) {
                if (is_string($t) && !empty(trim($t))) {
                    $tags[] = trim($t);
                }
            }
        }
        $tags = array_unique($tags);

        $this->setLayout($this->main_layout)->setTemplate('view/main/blog/index');
        return $this->render([
            'last_blog' => $last_blog,
            'archives' => $blogModel->getArchive(),
            'tags' => $tags,
        ]);
    }

    /**
     * @param $id
     * @param null $slug
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function show($id, $slug = null)
    {
        /**
         * @var BlogModel $blogModel
         */
        $blogModel = container()->get(BlogModel::class);

        if (empty($id) || !$blogModel->count('id=:id AND publish=:pub', ['id' => $id, 'pub' => DB_YES])) {
            return $this->show404();
        }

        $last_blog = $blogModel->get([
            'id', 'slug', 'title', 'image', 'created_at'
        ], 'publish=:pub', [
            'pub' => DB_YES
        ], ['id DESC'], 4);
        $blog = $blogModel->getFirstBlog('b.id=:id', ['id' => $id]);
        $prev_blog = $blogModel->getSiblings('id<:id', ['id' => $id], 1);
        if (count($prev_blog)) {
            $prev_blog = $prev_blog[0];
        }
        $next_blog = $blogModel->getSiblings('id>:id', ['id' => $id], 1);
        if (count($next_blog)) {
            $next_blog = $next_blog[0];
        }

        $keywords = explode(',', $blog['keywords']);
        $keywords = array_filter($keywords, function ($val) {
            if (is_string($val) && !empty(trim($val))) return true;
            return false;
        });

        $related_blog = $blogModel->getRelatedBlog(['keywords' => $keywords], 2);

        $this->setLayout($this->main_layout)->setTemplate('view/main/blog/index');
        return $this->render([
            'title' => title_concat(\config()->get('settings.title.value'), 'بلاگ', $blog['title']),
            'sub_title' => $blog['title'],
            'blog' => $blog,
            'keywords' => $keywords,
            'prev_blog' => $prev_blog,
            'next_blog' => $next_blog,
            'related_blog' => $related_blog,
            'last_blog' => $last_blog,
            'archives' => $blogModel->getArchive(),
        ]);
    }

    /**
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function search()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var BlogUtil $blogUtil
             */
            $blogUtil = container()->get(BlogUtil::class);
            $blogInfo = $blogUtil->paginatedBlog();
            $resourceHandler->data($this->setTemplate('partial/main/blog/filtered')->render([
                'blog' => $blogInfo['items'],
                'pagination' => $blogInfo['pagination'],
            ]));
        } else {
            response()->httpCode(403);
            $resourceHandler->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }
}