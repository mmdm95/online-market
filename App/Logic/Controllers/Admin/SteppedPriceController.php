<?php

namespace App\Logic\Controllers\Admin;

use App\Logic\Abstracts\AbstractAdminController;
use App\Logic\Forms\Admin\Stepped\AddSteppedForm;
use App\Logic\Forms\Admin\Stepped\EditSteppedForm;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\BaseModel;
use App\Logic\Models\ProductModel;
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

class SteppedPriceController extends AbstractAdminController
{
    /**
     * @param $p_id
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
    public function view($p_id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->getFirst(['title'], 'id=:id', ['id' => $p_id]);

        if (0 === count($product)) {
            return $this->show404();
        }

        $products = $productModel->getProductProperty($p_id);

        $this->setLayout($this->main_layout)->setTemplate('view/product/stepped/view');
        return $this->render([
            'product_id' => $p_id,
            'products' => $products,
            'sub_title' => 'محصولات موجود' . '-' . $product['title'],
        ]);
    }

    /**
     * @param $code
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
    public function viewStepped($code)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_READ)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->getProductPropertyWithInfo(['product_id'], 'code=:code', ['code' => $code]);

        if (0 === count($product)) {
            return $this->show404();
        }

        $product = $product[0];
        $title = $productModel->getFirst(['title'], 'id=:id', ['id' => $product['product_id']])['title'];

        $products = $productModel->getSteppedPrices($code);

        $this->setLayout($this->main_layout)->setTemplate('view/product/stepped/view-all');
        return $this->render([
            'product_code' => $code,
            'products' => $products,
            'sub_title' => 'قیمت‌های پلکانی' . '-' . $title,
            'breadcrumb' => [
                [
                    'url' => url('admin.index')->getRelativeUrl(),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.product.view', '')->getRelativeUrl(),
                    'text' => 'مدیریت محصولات',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.stepped-price.view', ['p_id' => $product['product_id']])->getRelativeUrl(),
                    'text' => 'محصولات موجود برای قیمت پلکانی',
                    'is_active' => false,
                ],
                [
                    'text' => 'قیمت‌های پلکانی',
                    'is_active' => true,
                ],
            ],
        ]);
    }

    /**
     * @param $code
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
    public function add($code)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_CREATE)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->getProductPropertyWithInfo(['product_id'], 'code=:code', ['code' => $code]);

        if (0 === count($product)) {
            return $this->show404();
        }

        $product = $product[0];
        $title = $productModel->getFirst(['title'], 'id=:id', ['id' => $product['product_id']])['title'];

        session()->setFlash('stepped-add-curr-code', $code);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(AddSteppedForm::class, 'stepped_add');
        }

        $this->setLayout($this->main_layout)->setTemplate('view/product/stepped/add');
        return $this->render(array_merge($data, [
            'product_code' => $code,
            'sub_title' => 'افزودن قیمت پلکانی' . '-' . $title,
            'breadcrumb' => [
                [
                    'url' => url('admin.index')->getRelativeUrl(),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.product.view', '')->getRelativeUrl(),
                    'text' => 'مدیریت محصولات',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.stepped-price.view', ['p_id' => $product['product_id']])->getRelativeUrl(),
                    'text' => 'محصولات موجود برای قیمت پلکانی',
                    'is_active' => false,
                ],
                [
                    'text' => 'افزودن قیمت پلکانی جدید',
                    'is_active' => true,
                ],
            ],
        ]));
    }

    /**
     * @param $code
     * @param $id
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
    public function edit($code, $id)
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        if (!$auth->isAllow(RESOURCE_PRODUCT, IAuth::PERMISSION_UPDATE)) {
            show_403();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $product = $productModel->getProductPropertyWithInfo(['product_id'], 'code=:code', ['code' => $code]);

        if (0 === count($product)) {
            return $this->show404();
        }

        $product = $product[0];
        $title = $productModel->getFirst(['title'], 'id=:id', ['id' => $product['product_id']])['title'];

        // store previous info to check for duplicate
        session()->setFlash('product-stepped-curr-id', $id);
        session()->setFlash('stepped-edit-curr-code', $code);
        session()->setFlash('stepped-edit-prev-info', $productModel->getSteppedPrices($code)[0]);

        $data = [];
        if (is_post()) {
            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(EditSteppedForm::class, 'stepped_edit');
        }

        $steppedProduct = $productModel->getSteppedPrices($code);
        $steppedProduct = $steppedProduct[0];

        $this->setLayout($this->main_layout)->setTemplate('view/product/stepped/edit');
        return $this->render(array_merge($data, [
            'product_code' => $code,
            'product_id' => $id,
            'product' => $steppedProduct,
            'sub_title' => 'ویرایش قیمت پلکانی' . '-' . $title,
            'breadcrumb' => [
                [
                    'url' => url('admin.index')->getRelativeUrl(),
                    'icon' => 'icon-home2',
                    'text' => 'خانه',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.product.view', '')->getRelativeUrl(),
                    'text' => 'مدیریت محصولات',
                    'is_active' => false,
                ],
                [
                    'url' => url('admin.stepped-price.view', ['p_id' => $product['product_id']])->getRelativeUrl(),
                    'text' => 'محصولات موجود برای قیمت پلکانی',
                    'is_active' => false,
                ],
                [
                    'text' => 'ویرایش قیمت پلکانی',
                    'is_active' => true,
                ],
            ],
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
            $resourceHandler = $handler->handle(BaseModel::TBL_STEPPED_PRICES, $id);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $code
     * @throws IDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function removeAll($code)
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
            emitter()->addListener('remove.general.ajax:success', function (IEvent $event, ResourceHandler $resourceHandler) {
                $event->stopPropagation();

                $resourceHandler->data('تمام آیتم‌های قیمت پلکانی با موفقیت حذف شدند.');
            });
            emitter()->addListener('remove.general.ajax:not_exists', function (IEvent $event, ResourceHandler $resourceHandler) {
                $event->stopPropagation();

                $resourceHandler->errorMessage('هیچ آیتمی برای حذف وجود ندارد');
            });

            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_STEPPED_PRICES, null, 'product_code=:code', ['code' => $code], true);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }
}
