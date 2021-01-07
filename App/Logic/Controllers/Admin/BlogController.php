<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Blog\AddBlogForm;
use App\Logic\Forms\Admin\Blog\EditBlogForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Models\BlogModel;
use App\Logic\Utils\Jdf;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class BlogController extends AbstractAdminController implements IDatatableController
{
    /**
     * @return string
     * @throws ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/blog/view');
        return $this->render();
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function add()
    {
        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddBlogForm::class, 'blog_add');
        }

        /**
         * @var BlogCategoryModel $categoryModel
         */
        $categoryModel = container()->get(BlogCategoryModel::class);
        $categories = $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]);

        $this->setLayout($this->main_layout)->setTemplate('view/blog/add');
        return $this->render(array_merge($data, [
            'categories' => $categories,
        ]));
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function edit($id)
    {
        /**
         * @var BlogModel $blogModel
         */
        $blogModel = container()->get(BlogModel::class);

        $blog = $blogModel->get(['title'], 'id=:id', ['id' => $id]);

        if (0 == count($blog)) {
            return $this->show404();
        }

        // store previous title to check for duplicate
        session()->setFlash('blog-prev-title', $blog[0]['title']);
        session()->setFlash('blog-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditBlogForm::class, 'blog_edit');
        }

        $blog = $blogModel->getFirst(['*'], 'id=:id', ['id' => $id]);

        $this->setLayout($this->main_layout)->setTemplate('view/blog/edit');
        return $this->render(array_merge($data, [
            'blog' => $blog,
        ]));
    }

    /**
     * @param $id
     * @throws ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function remove($id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_BLOG, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param array $_
     * @return void
     */
    public function getPaginatedDatatable(...$_): void
    {
        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) {
                    $event->stopPropagation();

                    /**
                     * @var BlogModel $blogModel
                     */
                    $blogModel = container()->get(BlogModel::class);

                    $data = $blogModel->getBlog($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $blogModel->getBlogCount($where, $bindValues);
                    $recordsTotal = $blogModel->getBlogCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'b.id', 'db_alias' => 'id', 'dt' => 'id'],
                    [
                        'db' => 'b.image',
                        'db_alias' => 'image',
                        'dt' => 'image',
                        'formatter' => function ($d, $row) {
                            return '<img class="img-preview img-rounded lazy" data-src="' . url('image.show')->getRelativeUrl() . $d . '" alt="' . $row['title'] . '">';
                        }
                    ],
                    ['db' => 'b.title', 'db_alias' => 'title', 'dt' => 'title'],
                    [
                        'db' => 'b.publish',
                        'db_alias' => 'publish',
                        'dt' => 'pub_status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/badge-parser/active-status')
                                ->render([
                                    'status' => $d,
                                ]);
                            return $status;
                        }
                    ],
                    ['db' => 'b.writer', 'db_alias' => 'writer', 'dt' => 'writer'],
                    ['db' => 'bc.name', 'db_alias' => 'category_name', 'dt' => 'category'],
                    [
                        'db' => 'b.created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'pub_date',
                        'formatter' => function ($d) {
                            return Jdf::jdate('j F Y', $d);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-blog')
                                ->render([
                                    'row' => $row,
                                ]);
                            return $operations;
                        }
                    ],
                ];

                $response = DatatableHandler::handle($_POST, $columns);
            } else {
                response()->httpCode(403);
                $response = [
                    'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}