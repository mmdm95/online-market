<?php

namespace App\Logic;

use App\Logic\Handlers\CustomExceptionHandler;
use App\Logic\Middlewares\AdminApiVerificationMiddleware;
use App\Logic\Middlewares\AdminAuthMiddleware;
use App\Logic\Middlewares\ApiVerificationMiddleware;
use Pecee\SimpleRouter\Event\EventArgument;
use Pecee\SimpleRouter\Handlers\EventHandler;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Sim\Interfaces\IInitialize;

class Route implements IInitialize
{
    /**
     * Route definitions
     * @throws \Exception
     */
    public function init()
    {
        $this->setDependencyInjection();
        $this->setEventHandlers();
        $this->setDefaultNamespace();
        $this->webRoutes();
        $this->apiRoutes();
    }

    /**
     * @throws \Exception
     */
    protected function setDependencyInjection()
    {
        // Development code for container
        // Create our new php-di container
        $container = (new \DI\ContainerBuilder())
            ->useAutowiring(true)
            ->build();

        // Production code for container
//        // Cache directory
//        $cacheDir = cache_path('simple-router');
//
//        // Create our new php-di container
//        $container = (new \DI\ContainerBuilder())
//            ->enableCompilation($cacheDir)
//            ->writeProxiesToFile(true, $cacheDir . '/proxies')
//            ->useAutowiring(true)
//            ->build();

        // Add our container to simple-router and enable dependency injection
        Router::enableDependencyInjection($container);
    }

    protected function setEventHandlers()
    {
        $eventHandler = new EventHandler();

        // Add event that fires when a route is rendered
        $eventHandler->register(EventHandler::EVENT_RENDER_ROUTE, function (EventArgument $argument) {
            // read config from database and store in config manager
            // ...
        });

        Router::addEventHandler($eventHandler);
    }

    /**
     * Default namespace for route
     */
    protected function setDefaultNamespace()
    {
        /**
         * The default namespace for route-callbacks, so we don't have to specify it each time.
         * Can be overwritten by using the namespace config option on your routes.
         */
        Router::setDefaultNamespace('\App\Logic\Controllers');
    }

    /**
     * Routes of web part
     */
    protected function webRoutes()
    {
        //==========================
        // not found route in admin
        //==========================
        Router::get('/admin/page/404', 'PageController@adminNotFound')->name(NOT_FOUND_ADMIN);

        //==========================
        // not found route
        //==========================
        Router::get('/page/404', 'PageController@notFound')->name(NOT_FOUND);

        //==========================
        // server error route
        //==========================
        Router::get('/page/500', 'PageController@serverError')->name(SERVER_ERROR);

        Router::group(['exceptionHandler' => CustomExceptionHandler::class], function () {
            //==========================
            // admin routes
            //==========================
            // admin login page
            Router::match(['get', 'post'], '/admin/login', 'Admin\HomeController@login')->name('admin.login');

            // other pages that need authentication
            Router::group(['prefix' => '/admin/', 'middleware' => AdminAuthMiddleware::class], function () {
                Router::get('/', 'Admin\HomeController@index')->name('admin.index');

                /**
                 * User Route
                 */
                Router::get('/user/view/{id?}', 'Admin\UserController@view')
                    ->where([
                        'id' => '[0-9]+',
                    ])->name('admin.user.view');
                Router::get('/user/add', 'Admin\UserController@add')->name('admin.user.add');
                Router::get('/user/edit/{id}', 'Admin\UserController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.user.edit');

                /**
                 * Category Route
                 */
                Router::get('/category/add', 'Admin\CategoryController@add')->name('admin.category.add');
                Router::get('/category/edit/{id}', 'Admin\CategoryController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.category.edit');
                Router::get('/category/view', 'Admin\CategoryController@view')->name('admin.category.view');

                /**
                 * Coupon Route
                 */
                Router::get('/coupon/add', 'Admin\CouponController@add')->name('admin.coupon.add');
                Router::get('/coupon/edit/{id}', 'Admin\CouponController@edit')->where([
                    'id' => '[0-9]+',
                ])->name('admin.coupon.edit');
                Router::get('/coupon/view', 'Admin\CouponController@view')->name('admin.coupon.view');

                /**
                 * Color Rout
                 */
                Router::get('/color/add', 'Admin\ColorController@add')->name('admin.color.add');
                Router::get('/color/view', 'Admin\ColorController@view')->name('admin.color.view');


                /**
                 * File Manager Route
                 */
                Router::get('/file-manager', 'Admin\FileController@index')->name('admin.file-manager');
            });


            //==========================
            // other routes
            //==========================
            Router::get('/', 'HomeController@index')->name('home.index');
        });
    }

    /**
     * Routes of api part
     */
    protected function apiRoutes()
    {
        Router::group([
            'exceptionHandler' => CustomExceptionHandler::class,
            'middleware' => ApiVerificationMiddleware::class
        ], function () {
            //==========================
            // admin routes
            //==========================
            Router::group(['prefix' => '/api/', 'middleware' => AdminApiVerificationMiddleware::class], function () {
                /**
                 * File Manager Route
                 */
                Router::get('/file-manager/list', 'Admin\FileController@list')->name('api.file-manager.list');
                Router::post('/file-manager/rename', 'Admin\FileController@rename')->name('api.file-manager.rename');
                Router::post('/file-manager/delete', 'Admin\FileController@delete')->name('api.file-manager.delete');
                Router::post('/file-manager/mkdir', 'Admin\FileController@makeDir')->name('api.file-manager.mkdir');
                Router::post('/file-manager/mvdir', 'Admin\FileController@moveDir')->name('api.file-manager.mvdir');
                Router::post('/file-manager/upload', 'Admin\FileController@upload')->name('api.file-manager.upload');
                Router::post('/file-manager/download', 'Admin\FileController@download')->name('api.file-manager.download');
                Router::post('/file-manager/dir-tree', 'Admin\FileController@foldersTree')->name('api.file-manager.tree');
            });
        });
    }
}
