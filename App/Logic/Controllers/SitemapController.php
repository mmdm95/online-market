<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Models\BlogModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\StaticPageModel;

class SitemapController extends AbstractHomeController
{
    /**
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Exceptions\Mvc\Controller\ControllerException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public function index()
    {
        /**
         * @var StaticPageModel $pageModel
         */
        $pageModel = container()->get(StaticPageModel::class);
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var BlogModel $blogModel
         */
        $blogModel = container()->get(BlogModel::class);

        $pages = $pageModel->get(['url', 'created_at', 'updated_at'], 'publish=:pub', ['pub' => DB_YES]);
        $products = $productModel->get(['id', 'slug', 'created_at', 'updated_at']);
        $blogs = $blogModel->get(['id', 'slug', 'created_at', 'updated_at']);

        header('Content-Type: text/xml');
        return $this->setTemplate('view/main/sitemap')
            ->render([
                'pages' => $pages,
                'products' => $products,
                'blogs' => $blogs,
            ]);
    }
}
