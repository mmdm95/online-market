<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Category\AddCategoryForm;
use App\Logic\Forms\Admin\Category\EditCategoryForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralAjaxStatusHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\Model;
use App\Logic\Utils\LogUtil;
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

class CategoryController extends AbstractAdminController implements IDatatableController
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
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $this->setLayout($this->main_layout)->setTemplate('view/category/view');
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
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddCategoryForm::class, 'cat_add');
        }

        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        $categories = $categoryModel->get(['id', 'name', 'level'], null, [], ['level ASC']);

        $this->setLayout($this->main_layout)->setTemplate('view/category/add');
        return $this->render(array_merge($data, [
            'categories' => $categories,
        ]));
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
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);

        $category = $categoryModel->get(['name'], 'id=:id', ['id' => $id]);

        if (0 == count($category)) {
            return $this->show404();
        }

        // store previous title to check for duplicate
        session()->setFlash('category-prev-name', $category[0]['name']);
        session()->setFlash('category-curr-id', $id);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditCategoryForm::class, 'cat_edit');
        }

        $category = $categoryModel->getFirst(['*'], 'id=:id', ['id' => $id]);
        $categories = $categoryModel->get(['id', 'name', 'level'], null, [], ['level ASC']);

        $this->setLayout($this->main_layout)->setTemplate('view/category/edit');
        return $this->render(array_merge($data, [
            'category' => $category,
            'categories' => $categories,
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
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            emitter()->addListener('remove.general.ajax:success', function (ResourceHandler $recHandler) use ($id) {
                /**
                 * @var Model $model
                 */
                $model = container()->get(Model::class);
                $update = $model->update();
                // there is no need to "where" clause because we want to update all parents ids
                $update
                    ->table(BaseModel::TBL_CATEGORIES)
                    // replace regex of id with nothing in all categories
                    // old: (,(?<![0-9])id(?![0-9])|(?<![0-9])id(?![0-9])|(?<![0-9])id(?![0-9]),)
                    // new: ([^0-9]|^)id([^0-9]|$)
                    // will match "id", ",id", "id," literally
                    ->set('all_parents_id', 'REGEXP_REPLACE("all_parents_id", ' . getDBCommaRegexString($id) . ', ",")');
                $model->execute($update);
            });
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_CATEGORIES, $id, 'deletable=:del', ['del' => DB_YES]);
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
    public function menuStatusChange($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_READ)) {
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
                    ->setStatusCheckedMessage('نمایش در منو فعال شد.')
                    ->setStatusUncheckedMessage('نمایش در منو غیر فعال شد.')
                    ->handle(
                        BaseModel::TBL_CATEGORIES,
                        $id,
                        'show_in_menu',
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
            LogUtil::logException($e, __LINE__, self::class);
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
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_READ)) {
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
                     * @var CategoryModel $categoryModel
                     */
                    $categoryModel = container()->get(CategoryModel::class);

                    $cols[] = 'c.deletable';

                    $data = $categoryModel->getCategories($cols, $where, $bindValues, $limit, $offset, $order);
                    //-----
                    $recordsFiltered = $categoryModel->getCategoriesCount($where, $bindValues);
                    $recordsTotal = $categoryModel->getCategoriesCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'c.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'c.name', 'db_alias' => 'name', 'dt' => 'name'],
                    [
                        'db' => 'cc.name',
                        'db_alias' => 'parent_name',
                        'dt' => 'parent_name',
                        'formatter' => function ($d) {
                            if (empty($d)) {
                                return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                            }
                            return $d;
                        }
                    ],
                    ['db' => 'c.priority', 'db_alias' => 'priority', 'dt' => 'priority'],
                    [
                        'db' => 'c.publish',
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
                        'db' => 'c.show_in_menu',
                        'db_alias' => 'show_in_menu',
                        'dt' => 'show_in_menu',
                        'formatter' => function ($d, $row) {
                            if (DB_YES == $row['deletable']) {
                                $status = $this->setTemplate('partial/admin/parser/status-changer')
                                    ->render([
                                        'status' => $d,
                                        'row' => $row,
                                        'url' => url('ajax.category.menu.status')->getRelativeUrl(),
                                    ]);
                                return $status;
                            }
                            return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-category')
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
            LogUtil::logException($e, __LINE__, self::class);
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}