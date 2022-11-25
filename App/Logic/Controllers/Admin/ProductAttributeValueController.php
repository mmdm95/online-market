<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Ajax\Product\Attribute\Value\AddProductAttrValueForm;
use App\Logic\Forms\Ajax\Product\Attribute\Value\EditProductAttrValueForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\ProductAttributeModel;
use App\Logic\Utils\LogUtil;
use DI\DependencyException;
use DI\NotFoundException;
use Jenssegers\Agent\Agent;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Event\Interfaces\IEvent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class ProductAttributeValueController extends AbstractAdminController implements IDatatableController
{
    /**
     * @var string $tbl
     */
    protected $tbl = BaseModel::TBL_PRODUCT_ATTR_VALUES;

    /**
     * @param $a_id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function view($a_id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        /**
         * @var ProductAttributeModel $attrModel
         */
        $attrModel = container()->get(ProductAttributeModel::class);

        if (0 === $attrModel->count('id=:id', ['id' => $a_id])) {
            return $this->show404();
        }

        $attrTitle = $attrModel->getFirst(['title'], 'id=:id', ['id' => $a_id])['title'];

        $this->setLayout($this->main_layout)->setTemplate('view/product/attribute/value/view');
        return $this->render([
            'sub_title' => 'مدیریت مقادیر ویژگی‌های جستجو' . '-' . $attrTitle,
            'attrId' => $a_id,
        ]);
    }

    /**
     * @param $a_id
     * @return void
     * @throws DependencyException
     * @throws IDBException
     * @throws NotFoundException
     */
    public function add($a_id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            // store previous info to add with
            session()->setFlash('product-attr-val-curr-a-id', $a_id);

            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('مقدار با موفقیت به ویژگی اضافه شد.')
                ->handle(AddProductAttrValueForm::class);
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
     * @return void
     * @throws DependencyException
     * @throws IDBException
     * @throws NotFoundException
     */
    public function edit($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            // store previous info to update with
            session()->setFlash('product-attr-val-curr-e-id', $id);

            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('مقدار ویژگی با موفقیت بروزرسانی شد.')
                ->handle(EditProductAttrValueForm::class);
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
    public function remove($id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_DELETE)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle($this->tbl, $id);
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
    public function get($id): void
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var ProductAttributeModel $attrModel
             */
            $attrModel = container()->get(ProductAttributeModel::class);
            $res = $attrModel->getFirstAttrValues(['pav.id', 'pav.p_attr_id', 'pav.attr_val'], 'pav.id=:id', ['id' => $id]);
            if (count($res)) {
                $resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->data($res);
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('اطلاعات تصویر درخواست شده وجود ندارد!');
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
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        [$a_id] = $_;

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($a_id) {
                    $event->stopPropagation();

                    /**
                     * @var ProductAttributeModel $pAttrModel
                     */
                    $pAttrModel = container()->get(ProductAttributeModel::class);

                    if (!empty($where)) {
                        $where .= ' AND (p_attr_id=:aId)';
                    } else {
                        $where = 'p_attr_id=:aId';
                    }
                    $bindValues = array_merge($bindValues, [
                        'aId' => $a_id,
                    ]);

                    $data = $pAttrModel->getAttrValues($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $pAttrModel->getAttrValuesCount($where, $bindValues);
                    $recordsTotal = $pAttrModel->getAttrValuesCount();

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'pav.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'pa.title', 'db_alias' => 'attr_name', 'dt' => 'attr_name'],
                    ['db' => 'pav.attr_val', 'db_alias' => 'attr_val', 'dt' => 'attr_val'],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            return $this->setTemplate('partial/admin/datatable/actions-product-attr-value')
                                ->render([
                                    'row' => $row,
                                ]);
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
