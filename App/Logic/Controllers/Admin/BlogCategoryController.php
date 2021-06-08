<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\BlogCategory\AddBlogCategoryForm;
use App\Logic\Forms\Admin\BlogCategory\EditBlogCategoryForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralAjaxStatusHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Utils\Jdf;
use Jenssegers\Agent\Agent;
use ReflectionException;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class BlogCategoryController extends AbstractAdminController implements IDatatableController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function view()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_BLOG_CATEGORY, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/blog/category/view');
        return $this->render();
    }

    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function add()
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_BLOG_CATEGORY, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddBlogCategoryForm::class, 'blog_category_add');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/blog/category/add');
        return $this->render($data);
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws ReflectionException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function edit($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_BLOG_CATEGORY, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        /**
         * @var BlogCategoryModel $categoryModel
         */
        $categoryModel = container()->get(BlogCategoryModel::class);

        $category = $categoryModel->get(['name'], 'id=:id', ['id' => $id]);

        if (0 == count($category)) {
            return $this->show404();
        }

        // store previous title to check for duplicate
        session()->setFlash('blog-category-prev-name', $category[0]['name']);
        session()->setFlash('blog-category-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditBlogCategoryForm::class, 'blog_category_edit');
        }

        $category = $categoryModel->getFirst(['*'], 'id=:id', ['id' => $id]);

        $this->setLayout($this->main_layout)->setTemplate('view/blog/category/edit');
        return $this->render(array_merge($data, [
            'category' => $category,
        ]));
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_BLOG_CATEGORY, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_BLOG_CATEGORIES, $id, 'deletable', DB_YES);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function sideStatusChange($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_BLOG_CATEGORY, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                $handler = new GeneralAjaxStatusHandler();
                $resourceHandler = $handler
                    ->setStatusCheckedMessage('نمایش در کنار صفحه فعال شد.')
                    ->setStatusUncheckedMessage('نمایش در کنار صفحه غیر فعال شد.')
                    ->handle(
                        BaseModel::TBL_BLOG_CATEGORIES,
                        $id,
                        'show_in_side',
                        input()->post('status')->getValue(),
                        'deletable=:del',
                        ['del' => DB_YES]
                    );
            } else {
                response()->httpCode(403);
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param array $_
     * @return void
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function getPaginatedDatatable(...$_): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_BLOG_CATEGORY, IAuth::PERMISSION_READ)) {
            show_403();
        }

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) {
                    $event->stopPropagation();

                    /**
                     * @var BlogCategoryModel $categoryModel
                     */
                    $categoryModel = container()->get(BlogCategoryModel::class);

                    $cols[] = 'deletable';

                    $data = $categoryModel->get($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $categoryModel->count($where, $bindValues);
                    $recordsTotal = $categoryModel->count();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'name', 'db_alias' => 'name', 'dt' => 'name'],
                    [
                        'db' => 'publish',
                        'db_alias' => 'publish',
                        'dt' => 'status',
                        'formatter' => function ($d) {
                            $status = $this->setTemplate('partial/admin/parser/active-status')
                                ->render([
                                    'status' => $d,
                                ]);
                            return $status;
                        }
                    ],
                    [
                        'db' => 'show_in_side',
                        'db_alias' => 'show_in_side',
                        'dt' => 'show_in_side',
                        'formatter' => function ($d, $row) {
                            if (DB_YES == $row['deletable']) {
                                $status = $this->setTemplate('partial/admin/parser/status-changer')
                                    ->render([
                                        'status' => $d,
                                        'row' => $row,
                                        'url' => url('ajax.blog.category.side.status')->getRelativeUrl(),
                                    ]);
                                return $status;
                            }
                            return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'db' => 'created_at',
                        'db_alias' => 'created_at',
                        'dt' => 'created_at',
                        'formatter' => function ($d, $row) {
                            if (DB_YES == $row['deletable']) {
                                return Jdf::jdate(DEFAULT_TIME_FORMAT, $d);
                            }
                            return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-blog-category')
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