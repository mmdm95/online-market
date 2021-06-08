<?php


namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Ajax\CategoryImage\AddCategoryImageForm as AjaxAddCategoryImageForm;
use App\Logic\Forms\Ajax\CategoryImage\EditCategoryImageForm as AjaxEditCategoryImageForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CategoryImageModel;
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

class CategoryImageController extends AbstractAdminController implements IDatatableController
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

        $this->setLayout($this->main_layout)->setTemplate('view/category/image/view');
        return $this->render();
    }

    /**
     * @param $c_id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function add($c_id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            session()->setFlash('cat-img-add-cat-id', $c_id);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('تصویر با موفقیت به دسته اضافه شد.')
                ->handle(AjaxAddCategoryImageForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $c_id
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function edit($c_id, $id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            session()->setFlash('cat-img-edit-cat-id', $c_id);
            session()->setFlash('cat-img-edit-id', $id);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('تصویر دسته با موفقیت ویرایش شد.')
                ->handle(AjaxEditCategoryImageForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $c_id
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($c_id, $id)
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
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_CATEGORY_IMAGES, $id, 'category_id=:cId', ['cId' => $c_id]);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $c_id
     * @param $id
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get($c_id, $id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_CATEGORY, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var CategoryImageModel $categoryModel
             */
            $categoryModel = container()->get(CategoryImageModel::class);
            $res = $categoryModel->getFirst(['*'], 'category_id=:cId AND id=:id', ['cId' => $c_id, 'id' => $id]);
            if (count($res)) {
                $resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->data($res);
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('تصویر درخواست شده نامعتبر است!');
            }
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
                     * @var CategoryImageModel $categoryModel
                     */
                    $categoryModel = container()->get(CategoryImageModel::class);

                    $cols[] = 'ci.category_id';
                    $cols[] = 'c.deletable';

                    if (!empty($where)) {
                        $where .= ' AND (c.level=:c_lvl)';
                    } else {
                        $where = 'c.level=:c_lvl';
                    }
                    $bindValues = array_merge($bindValues, [
                        'c_lvl' => 1,
                    ]);

                    $data = $categoryModel->getCategoryImages($cols, $where, $bindValues, $limit, $offset, $order);
                    //-----
                    $recordsFiltered = $categoryModel->getCategoryImagesCount($where, $bindValues);
                    $recordsTotal = $categoryModel->getCategoryImagesCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'c.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'c.name', 'db_alias' => 'name', 'dt' => 'name'],
                    [
                        'db' => 'ci.link',
                        'db_alias' => 'link',
                        'dt' => 'link',
                        'formatter' => function ($d) {
                            return $d ?: $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'db' => 'ci.image',
                        'db_alias' => 'image',
                        'dt' => 'image',
                        'formatter' => function ($d, $row) {
                            if ($d) {
                                return $this->setTemplate('partial/admin/parser/image-placeholder')
                                    ->render([
                                        'img' => $d,
                                        'alt' => 'تصویر ' . $row['name'],
                                    ]);
                            }
                            return $this->setTemplate('partial/admin/parser/dash-icon')->render();
                        }
                    ],
                    [
                        'dt' => 'modify',
                        'formatter' => function ($row) {
                            return $this->setTemplate('partial/admin/datatable/category-image-btn')
                                ->render([
                                    'row' => $row,
                                ]);
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
            $response = [
                'error' => 'خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.',
            ];
        }

        response()->json($response);
    }
}