<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Ajax\ProductFestival\AddProductFestivalCategoryForm as AjaxAddProductFestivalCategoryForm;
use App\Logic\Forms\Ajax\ProductFestival\AddProductFestivalForm as AjaxAddProductFestivalForm;
use App\Logic\Handlers\DatatableHandler;
use App\Logic\Handlers\GeneralAjaxFormHandler;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Interfaces\IDatatableController;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\FestivalModel;
use App\Logic\Models\ProductFestivalModel;
use App\Logic\Models\ProductModel;
use Jenssegers\Agent\Agent;
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

class ProductFestivalController extends AbstractAdminController implements IDatatableController
{
    /**
     * @param $f_id
     * @return string
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws PathNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function view($f_id)
    {
        /**
         * @var FestivalModel $festivalModel
         */
        $festivalModel = container()->get(FestivalModel::class);

        if (0 === $festivalModel->count('id=:id', ['id' => $f_id])) {
            return $this->show404();
        }

        $festivalTitle = $festivalModel->getFirst(['title'], 'id=:id', ['id' => $f_id])['title'];

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);

        $products = $productModel->get(['id', 'title'], 'publish=:pub AND is_available=:av', ['pub' => DB_YES, 'av' => DB_YES]);
        $categories = $categoryModel->get(['id', 'name'], 'publish=:pub AND level=:lvl', ['pub' => DB_YES, 'lvl' => 3]);

        $this->setLayout($this->main_layout)->setTemplate('view/festival/detail');
        return $this->render([
            'sub_title' => 'ویرایش محصولات جشنواره' . '-' . $festivalTitle,
            'festivalId' => $f_id,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * @param $f_id
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function add($f_id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            session()->setFlash('product-festival-f-id', $f_id);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('محصول با موفقیت به جشنواره اضافه شد.')
                ->handle(AjaxAddProductFestivalForm::class);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $f_id
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function addCategory($f_id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            session()->setFlash('product-festival-category-f-id', $f_id);
            $formHandler = new GeneralAjaxFormHandler();
            $resourceHandler = $formHandler
                ->setSuccessMessage('محصولات دسته‌بندی مورد نظر با موفقیت به جشنواره اضافه شدند.')
                ->setWarningMessage('متاسفانه ممکن است تمام یا برخی از محصولات به جشنواره اضافه نشده باشند.')
                ->handle(AjaxAddProductFestivalCategoryForm::class);
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
     * @throws \ReflectionException
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
            $resourceHandler = $handler->handle(BaseModel::TBL_PRODUCT_FESTIVAL, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $f_id
     */
    public function removeCategory($f_id)
    {
        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                /**
                 * @var ProductFestivalModel $festivalModel
                 */
                $festivalModel = container()->get(ProductFestivalModel::class);

                $c_id = input()->post('inp-modify-product-festival-category')->getValue();
                $res = $festivalModel->removeCategoryFromFestival($f_id, $c_id);
                if ($res) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_INFO)
                        ->data('محصولات دسته‌بندی مورد نظر با موفقیت از جشنواره حذف شدند.');
                } else {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('خطا در حذف محصولات، لطفا دوباره تلاش کنید.');
                }
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
     */
    public function getPaginatedDatatable(...$_): void
    {
        try {
            [$f_id] = $_;

            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                emitter()->addListener('datatable.ajax:load', function (IEvent $event, $cols, $where, $bindValues, $limit, $offset, $order) use ($f_id) {
                    $event->stopPropagation();

                    /**
                     * @var ProductFestivalModel $festivalModel
                     */
                    $festivalModel = container()->get(ProductFestivalModel::class);

                    if (!empty($where)) {
                        $where .= ' AND (festival_id=:fId)';
                    } else {
                        $where = 'festival_id=:fId';
                    }
                    $bindValues = array_merge($bindValues, [
                        'fId' => $f_id,
                    ]);

                    $data = $festivalModel->getFestivalProducts($cols, $where, $bindValues, $order, $limit, $offset);
                    //-----
                    $recordsFiltered = $festivalModel->getFestivalProductsCount($where, $bindValues);
                    $recordsTotal = $festivalModel->getFestivalProductsCount('festival_id=:fId', ['fId' => $f_id]);

                    return [$data, $recordsFiltered, $recordsTotal];
                });

                $columns = [
                    ['db' => 'pf.id', 'db_alias' => 'id', 'dt' => 'id'],
                    ['db' => 'p.title', 'db_alias' => 'product_name', 'dt' => 'product_name'],
                    ['db' => 'c.name', 'db_alias' => 'category_name', 'dt' => 'category_name'],
                    [
                        'db' => 'p.image',
                        'db_alias' => 'product_image',
                        'dt' => 'product_image',
                        'formatter' => function ($d, $row) {
                            return $this->setTemplate('partial/admin/parser/image-placeholder')
                                ->render([
                                    'img' => $d,
                                    'alt' => $row['product_name'],
                                ]);
                        }
                    ],
                    [
                        'dt' => 'operations',
                        'formatter' => function ($row) {
                            $operations = $this->setTemplate('partial/admin/datatable/actions-product-festival')
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