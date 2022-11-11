<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\ProductModel;
use App\Logic\Utils\CompareUtil;
use DI\DependencyException;
use DI\NotFoundException;
use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class CompareController extends AbstractHomeController
{
    /**
     * @param int|null $p1
     * @param int|null $p2
     * @param int|null $p3
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws DependencyException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws NotFoundException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function compare(?int $p1 = null, ?int $p2 = null, ?int $p3 = null)
    {
        if (empty($p1) && empty($p2) && empty($p3)) {
            $this->show404();
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        // get existing products from sent parameters
        $tmpProducts = [];
        if (!empty($p1)) {
            $tmpProducts[] = $productModel->getFirst(['id', 'properties', 'category_id'], 'id=:id', ['id' => $p1]);
        }
        if (!empty($p2)) {
            $tmpProducts[] = $productModel->getFirst(['id', 'properties', 'category_id'], 'id=:id', ['id' => $p2]);
        }
        if (!empty($p3)) {
            $tmpProducts[] = $productModel->getFirst(['id', 'properties', 'category_id'], 'id=:id', ['id' => $p3]);
        }
        $categoryId = $tmpProducts[0]['category_id'] ?? -1;

        $products = [];
        foreach ($tmpProducts as $product) {
            if ($product['category_id'] == $categoryId) {
                $products[] = $product;
            }
        }

        if (empty($products)) {
            $this->show404();
        }

        // get default category from first existing product
        session()->set('compare_category_id_sess', $categoryId);

        // for each existing product, assemble its properties
        foreach ($products as $product) {
            $properties = json_decode($product['properties'], true);
            $properties = is_array($properties) ? $properties : [];
            CompareUtil::assembleCompareProperties($product['id'], $properties);
        }

        $this->setLayout($this->main_layout)->setTemplate('view/main/product/compare');
        return $this->render([
            'properties' => CompareUtil::getAssembleCompareProperties(),
        ]);
    }

    /**
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function products()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var CompareUtil $compareUtil
             */
            $compareUtil = container()->get(CompareUtil::class);
            $compareInfo = $compareUtil->paginatedCompare();
            $resourceHandler->data($this->setTemplate('partial/main/product/compare-filtered')->render([
                'products' => $compareInfo['items'],
                'pagination' => $compareInfo['pagination'],
            ]));
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }
}
