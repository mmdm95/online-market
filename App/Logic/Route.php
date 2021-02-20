<?php

namespace App\Logic;

use App\Logic\Controllers\Admin\BlogCategoryController;
use App\Logic\Controllers\Admin\BrandController;
use App\Logic\Controllers\Admin\CategoryController;
use App\Logic\Controllers\Admin\CategoryImageController;
use App\Logic\Controllers\Admin\ColorController;
use App\Logic\Controllers\Admin\ComplaintsController;
use App\Logic\Controllers\Admin\ContactUsController;
use App\Logic\Controllers\Admin\CouponController;
use App\Logic\Controllers\Admin\DepositTypeController;
use App\Logic\Controllers\Admin\FaqController;
use App\Logic\Controllers\Admin\FestivalController;
use App\Logic\Controllers\Admin\FileController;
use App\Logic\Controllers\Admin\HomeController as AdminHomeController;
use App\Logic\Controllers\Admin\InstagramController;
use App\Logic\Controllers\Admin\NewsletterController;
use App\Logic\Controllers\Admin\OrderBadgeController;
use App\Logic\Controllers\Admin\OrderController;
use App\Logic\Controllers\Admin\PaymentMethodController;
use App\Logic\Controllers\Admin\ProductFestivalController;
use App\Logic\Controllers\Admin\ReturnOrderController;
use App\Logic\Controllers\Admin\SecurityQuestionController;
use App\Logic\Controllers\Admin\SendMethodController;
use App\Logic\Controllers\Admin\SettingController;
use App\Logic\Controllers\Admin\SliderController;
use App\Logic\Controllers\Admin\StaticPageController;
use App\Logic\Controllers\Admin\SteppedPriceController;
use App\Logic\Controllers\Admin\UnitController;
use App\Logic\Controllers\Admin\UserController as AdminUserController;
use App\Logic\Controllers\Admin\WalletController;
use App\Logic\Controllers\BlogController;
use App\Logic\Controllers\CaptchaController;
use App\Logic\Controllers\CartController;
use App\Logic\Controllers\Colleague\HomeController as ColleagueHomeController;
use App\Logic\Controllers\CommentController;
use App\Logic\Controllers\CompareController;
use App\Logic\Controllers\HomeController;
use App\Logic\Controllers\LoginController;
use App\Logic\Controllers\PageController;
use App\Logic\Controllers\Admin\ProductController as AdminProductController;
use App\Logic\Controllers\ProductController;
use App\Logic\Controllers\RegisterController;
use App\Logic\Controllers\User\AddressController;
use App\Logic\Controllers\User\HomeController as UserHomeController;
use App\Logic\Handlers\CustomExceptionHandler;
use App\Logic\Middlewares\AdminAuthMiddleware;
use App\Logic\Middlewares\ApiVerifierMiddleware;
use App\Logic\Middlewares\AuthMiddleware;
use App\Logic\Utils\ConfigUtil;
use Pecee\SimpleRouter\Event\EventArgument;
use Pecee\SimpleRouter\Handlers\EventHandler;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInitialize;
use Sim\Interfaces\IInvalidVariableNameException;

class Route implements IInitialize
{
    /**
     * Route definitions
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function init()
    {
        $this->setDependencyInjection();
        $this->setEventHandlers();
        $this->setDefaultNamespace();
        //-----
        $this->webRoutes();
        $this->ajaxRoutes();
        $this->apiRoutes();
    }

    /**
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \Exception
     */
    protected function setDependencyInjection()
    {
        $mode = config()->get('main.mode') ?? MODE_PRODUCTION;
        if (MODE_PRODUCTION == $mode) {
            // Production code for container
            // Cache directory
            $cacheDir = cache_path('simple-router');

            // Create our new php-di container
            $container = (new \DI\ContainerBuilder())
                ->enableCompilation($cacheDir)
                ->writeProxiesToFile(true, $cacheDir . '/proxies')
                ->useAutowiring(true)
                ->build();
        } else {
            // Development code for container
            // Create our new php-di container
            $container = (new \DI\ContainerBuilder())
                ->useAutowiring(true)
                ->build();
        }

        // Add our container to simple-router and enable dependency injection
        Router::enableDependencyInjection($container);
    }

    protected function setEventHandlers()
    {
        $eventHandler = new EventHandler();

        // Add event that fires when a route is rendered
        $eventHandler->register(EventHandler::EVENT_RENDER_ROUTE, function (EventArgument $argument) {
            // read config from database and store in config manager
            /**
             * @var ConfigUtil $configUtil
             */
            $configUtil = \container()->get(ConfigUtil::class);
            $configUtil->pullConfig();
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
        Router::get('/admin/page/404', [PageController::class, 'adminNotFound'])->name(NOT_FOUND_ADMIN);

        //==========================
        // not found route
        //==========================
        Router::get('/page/404', [PageController::class, 'notFound'])->name(NOT_FOUND);

        //==========================
        // server error route
        //==========================
        Router::get('/page/500', [PageController::class, 'serverError'])->name(SERVER_ERROR);

        Router::group(['exceptionHandler' => CustomExceptionHandler::class], function () {
            //==========================
            // admin routes
            //==========================
            // admin login page
            Router::form('/admin/login', [AdminHomeController::class, 'login'])->name('admin.login');
            Router::get('/admin/logout', [AdminHomeController::class, 'logout'])->name('admin.logout');

            // other pages that need authentication
            Router::group(['prefix' => '/admin/', 'middleware' => AdminAuthMiddleware::class], function () {
                Router::get('/', [AdminHomeController::class, 'index'])->name('admin.index');

                /**
                 * User Route
                 */
                Router::get('/user/view/{id?}', [AdminUserController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.user.view');
                Router::form('/user/add', [AdminUserController::class, 'add'])->name('admin.user.add');
                Router::form('/user/edit/{id}', [AdminUserController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.user.edit');
                Router::post('/user/view/dt', [AdminUserController::class, 'getPaginatedDatatable'])->name('admin.user.dt.view');

                /**
                 * Address Route
                 */
                Router::post('/address/view/dt/{user_id}', [AddressController::class, 'getPaginatedDatatable'])->where([
                    'user_id' => '[0-9]+',
                ])->name('admin.addr.dt.view');

                /**
                 * User Order Route
                 */
                Router::post('/address/view/dt/{user_id}', [AdminUserController::class, 'getOrderPaginatedDatatable'])->where([
                    'user_id' => '[0-9]+',
                ])->name('admin.user.order.dt.view');

                /**
                 * Category Route
                 */
                Router::form('/category/add', [CategoryController::class, 'add'])->name('admin.category.add');
                Router::form('/category/edit/{id}', [CategoryController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.category.edit');
                Router::get('/category/view', [CategoryController::class, 'view'])->name('admin.category.view');
                Router::post('/category/view/dt', [CategoryController::class, 'getPaginatedDatatable'])->name('admin.category.dt.view');

                /**
                 * Category Image Route
                 */
                Router::get('/category/image/view', [CategoryImageController::class, 'view'])->name('admin.category.image.view');
                Router::post('/category/image/view/dt', [CategoryImageController::class, 'getPaginatedDatatable'])
                    ->name('admin.category.image.dt.view');

                /**
                 * Coupon Route
                 */
                Router::form('/coupon/add', [CouponController::class, 'add'])->name('admin.coupon.add');
                Router::form('/coupon/edit/{id}', [CouponController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.coupon.edit');
                Router::get('/coupon/view', [CouponController::class, 'view'])->name('admin.coupon.view');
                Router::post('/coupon/view/dt', [CouponController::class, 'getPaginatedDatatable'])->name('admin.coupon.dt.view');

                /**
                 * Payment Methods Route
                 */
                Router::form('/pay-method/add', [PaymentMethodController::class, 'add'])->name('admin.pay_method.add');
                Router::form('/pay-method/edit/{id}', [PaymentMethodController::class, 'edit'])->where([
                    'id' => '[0-9]',
                ])->name('admin.pay_method.edit');
                Router::get('/pay-method/view', [PaymentMethodController::class, 'view'])->name('admin.pay_method.view');
                Router::post('/pay-method/view/dt', [PaymentMethodController::class, 'getPaginatedDatatable'])->name('admin.pay_method.dt.view');

                /**
                 * Send Methods Route
                 */
                Router::form('/send-method/add', [SendMethodController::class, 'add'])->name('admin.send_method.add');
                Router::form('/send-method/edit/{id}', [SendMethodController::class, 'edit'])->where([
                    'id' => '[0-9]',
                ])->name('admin.send_method.edit');
                Router::get('/send-method/view', [SendMethodController::class, 'view'])->name('admin.send_method.view');
                Router::post('/send-method/view/dt', [SendMethodController::class, 'getPaginatedDatatable'])->name('admin.send_method.dt.view');

                /**
                 * Color Route
                 */
                Router::form('/color/add', [ColorController::class, 'add'])->name('admin.color.add');
                Router::form('/color/edit/{id}', [ColorController::class, 'edit'])->where([
                    'id' => '[0-9]',
                ])->name('admin.color.edit');
                Router::get('/color/view', [ColorController::class, 'view'])->name('admin.color.view');
                Router::post('/color/view/dt', [ColorController::class, 'getPaginatedDatatable'])->name('admin.color.dt.view');

                /**
                 * Festival Route
                 */
                Router::form('/festival/add', [FestivalController::class, 'add'])->name('admin.festival.add');
                Router::form('/festival/edit/{id}', [FestivalController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.festival.edit');
                Router::get('/festival/view', [FestivalController::class, 'view'])->name('admin.festival.view');
                Router::post('/festival/view/dt', [FestivalController::class, 'getPaginatedDatatable'])->name('admin.festival.dt.view');

                /**
                 * Product Festival Route
                 */
                Router::form('/festival/detail/{id}', [ProductFestivalController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.festival.detail');
                Router::post('/product/festival/view/dt/{f_id}', [ProductFestivalController::class, 'getPaginatedDatatable'])->where([
                    'f_id' => '[0-9]+',
                ])->name('admin.product.festival.dt.view');

                /**
                 * Brand Route
                 */
                Router::form('/brand/add', [BrandController::class, 'add'])->name('admin.brand.add');
                Router::form('/brand/edit/{id}', [BrandController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.brand.edit');
                Router::get('/brand/view', [BrandController::class, 'view'])->name('admin.brand.view');
                Router::post('/brand/view/dt', [BrandController::class, 'getPaginatedDatatable'])
                    ->name('admin.brand.dt.view');

                /**
                 * Product Route
                 */
                Router::form('/product/add', [AdminProductController::class, 'add'])->name('admin.product.add');
                Router::form('/product/edit/{id}', [AdminProductController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.edit');
                Router::get('/product/view', [AdminProductController::class, 'view'])->name('admin.product.view');
                Router::get('/product/detail/{id}', [AdminProductController::class, 'detail'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.detail');
                Router::post('/product/view/dt', [AdminProductController::class, 'getPaginatedDatatable'])->name('admin.product.dt.view');
                Router::get('/product/view/s2', [AdminProductController::class, 'getPaginatedSelect2'])->name('admin.product.s2.view');
                Router::form('/product/buyer/{id}', [AdminProductController::class, 'buyer'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.buyer');
                Router::post('/product/buyer/users/dt/{id}', [AdminProductController::class, 'getBuyerUsersPaginatedDatatable'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.dt.buyer.users');
                Router::post('/product/buyer/orders/dt/{id}', [AdminProductController::class, 'getBuyerOrdersPaginatedDatatable'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.dt.buyer.orders');

                /**
                 * Stepped Price Route
                 */
                Router::form('/product/stepped-price/add/{code}', [SteppedPriceController::class, 'add'])->where([
                    'code' => '[a-zA-Z0-9]+',
                ])->name('admin.stepped-price.add');
                Router::form('/product/stepped-price/edit/{code}/{id}', [SteppedPriceController::class, 'edit'])->where([
                    'code' => '[a-zA-Z0-9]+',
                    'id' => '[0-9]+',
                ])->name('admin.stepped-price.edit');
                Router::get('/product/stepped-price/view/{p_id}', [SteppedPriceController::class, 'view'])->where([
                    'p_id' => '[0-9]+',
                ])->name('admin.stepped-price.view');
                Router::get('/product/stepped-price/view-all/{code}', [SteppedPriceController::class, 'viewStepped'])->where([
                    'code' => '[a-zA-Z0-9]+',
                ])->name('admin.stepped-price.view');

                /**
                 * Comment Route
                 */
                Router::get('/comment/view/{p_id}', [CommentController::class, 'view'])->where([
                    'p_id' => '[0-9]+',
                ])->name('admin.comment.view');
                Router::form('/comment/detail/{p_id}/{id}', [CommentController::class, 'detail'])->where([
                    'p_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('admin.comment.detail');
                Router::post('/comment/view/dt/{p_id}', [CommentController::class, 'getPaginatedDatatable'])->where([
                    'p_id' => '[0-9]+',
                ])->name('admin.comment.dt.view');

                /**
                 * Blog Route
                 */
                Router::form('/blog/add', [BlogController::class, 'add'])->name('admin.blog.add');
                Router::form('/blog/edit/{id}', [BlogController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.blog.edit');
                Router::get('/blog/view', [BlogController::class, 'view'])->name('admin.blog.view');
                Router::post('/blog/view/dt', [BlogController::class, 'getPaginatedDatatable'])->name('admin.blog.dt.view');

                /**
                 * Blog Category Route
                 */
                Router::form('/blog/category/add', [BlogCategoryController::class, 'add'])->name('admin.blog.category.add');
                Router::form('/blog/category/edit/{id}', [BlogCategoryController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.blog.category.edit');
                Router::get('/blog/category/view', [BlogCategoryController::class, 'view'])->name('admin.blog.category.view');
                Router::post('/blog/category/view/dt', [BlogCategoryController::class, 'getPaginatedDatatable'])
                    ->name('admin.blog.category.dt.view');

                /**
                 * Order Route
                 */
                Router::form('/order/detail/{id}', [OrderController::class, 'detail'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.order.detail');
                Router::get('/order/view', [OrderController::class, 'view'])->name('admin.order.view');
                Router::post('/order/view/dt', [OrderController::class, 'getPaginatedDatatable'])->name('admin.order.dt.view');

                /**
                 * Return Order Route
                 */
                Router::get('/return-order/detail/{id}', [ReturnOrderController::class, 'detail'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.return.order.detail');
                Router::get('/return-order/view', [ReturnOrderController::class, 'view'])->name('admin.return.order.view');
                Router::post('/return-order/view/dt', [ReturnOrderController::class, 'getPaginatedDatatable'])->name('admin.return.order.dt.view');

                /**
                 * Order Badges Route
                 */
                Router::get('/order/badges', [OrderBadgeController::class, 'view'])->name('admin.badge.view');
                Router::post('/order/badges/dt', [OrderBadgeController::class, 'getPaginatedDatatable'])->name('admin.badge.dt.view');

                /**
                 * Wallet Route
                 */
                Router::form('/wallet/charge/{id}', [WalletController::class, 'charge'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.wallet.charge');
                Router::get('/wallet/view', [WalletController::class, 'view'])->name('admin.wallet.view');
                Router::post('/wallet/view/dt', [WalletController::class, 'getPaginatedDatatable'])->name('admin.wallet.dt.view');
                Router::get('/wallet/detail/{id}', [WalletController::class, 'detail'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.wallet.detail');;
                Router::post('/wallet/detail/dt/{username}', [WalletController::class, 'getDetailPaginatedDatatable'])
                    ->name('admin.wallet.detail.dt');

                /**
                 * Deposit Type Route
                 */
                Router::get('/deposit-type/view', [DepositTypeController::class, 'view'])->name('admin.deposit-type.view');
                Router::post('/deposit-type/view/dt', [DepositTypeController::class, 'getPaginatedDatatable'])->name('admin.deposit-type.dt.view');

                /**
                 * Static Page Route
                 */
                Router::form('/static-page/add', [StaticPageController::class, 'add'])->name('admin.static.page.add');
                Router::form('/static-page/edit/{id}', [StaticPageController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.static.page.edit');
                Router::get('/static-page/view', [StaticPageController::class, 'view'])->name('admin.static.page.view');
                Router::post('/static-page/view/dt', [StaticPageController::class, 'getPaginatedDatatable'])->name('admin.static.page.dt.view');

                /**
                 * Unit Route
                 */
                Router::get('/unit/view', [UnitController::class, 'view'])->name('admin.unit.view');
                Router::post('/unit/view/dt', [UnitController::class, 'getPaginatedDatatable'])->name('admin.unit.dt.view');

                /**
                 * FAQ Route
                 */
                Router::get('/faq/view', [FaqController::class, 'view'])->name('admin.faq.view');
                Router::post('/faq/view/dt', [FaqController::class, 'getPaginatedDatatable'])->name('admin.faq.dt.view');

                /**
                 * Contact Us Route
                 */
                Router::get('/contact-us/view/{id?}', [ContactUsController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.contact-us.view');
                Router::post('/contact-us/view/dt', [ContactUsController::class, 'getPaginatedDatatable'])
                    ->name('admin.contact-us.dt.view');

                /**
                 * Complaints Route
                 */
                Router::get('/complaints/view/{id?}', [ComplaintsController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.complaints.view');
                Router::post('/complaints/view/dt', [ComplaintsController::class, 'getPaginatedDatatable'])
                    ->name('admin.complaints.dt.view');

                /**
                 * Newsletter Route
                 */
                Router::get('/newsletter/view/{id?}', [NewsletterController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.newsletter.view');

                /**
                 * Slider Route
                 */
                Router::get('/slider/view', [SliderController::class, 'view'])->name('admin.slider.view');
                Router::post('/slider/view/dt', [SliderController::class, 'getPaginatedDatatable'])->name('admin.slider.dt.view');

                /**
                 * Instagram Route
                 */
                Router::get('/instagram/view', [InstagramController::class, 'view'])->name('admin.instagram.view');
                Router::post('/instagram/view/dt', [InstagramController::class, 'getPaginatedDatatable'])->name('admin.instagram.dt.view');

                /**
                 * Security Question Route
                 */
                Router::get('/sec-question/view', [SecurityQuestionController::class, 'view'])->name('admin.sec_question.view');
                Router::post('/sec-question/view/dt', [SecurityQuestionController::class, 'getPaginatedDatatable'])
                    ->name('admin.sec_question.dt.view');

                /**
                 * Setting Route
                 */
                Router::form('/setting/main', [SettingController::class, 'main'])->name('admin.setting.main');
                Router::form('/setting/top-menu', [SettingController::class, 'topMenu'])->name('admin.setting.top-menu');
                Router::form('/setting/sms', [SettingController::class, 'sms'])->name('admin.setting.sms');
                Router::form('/setting/contact', [SettingController::class, 'contact'])->name('admin.setting.contact');
                Router::form('/setting/social', [SettingController::class, 'social'])->name('admin.setting.social');
                Router::form('/setting/footer', [SettingController::class, 'footer'])->name('admin.setting.footer');
                Router::form('/setting/pages/index', [SettingController::class, 'indexPage'])->name('admin.setting.pages.index');
                Router::form('/setting/pages/about', [SettingController::class, 'aboutPage'])->name('admin.setting.pages.about');
                Router::form('/setting/other', [SettingController::class, 'other'])->name('admin.setting.other');
                /**
                 * File Manager Route
                 */
                Router::get('/file-manager', [FileController::class, 'index'])->name('admin.file-manager');
            });

            // show images outside of public folder
            Router::get('/images/{filename}', [FileController::class, 'showImage'])->where([
                'filename' => '.+',
            ])->name('image.show');

            //==========================
            // user routes
            //==========================
            Router::group(['prefix' => '/user/', 'middleware' => AuthMiddleware::class], function () {
                /**
                 * dashboard
                 */
                Router::get('/', [UserHomeController::class, 'index'])->name('user.index');
            });

            //==========================
            // colleague routes
            //==========================
            Router::group(['prefix' => '/colleague/', 'middleware' => AuthMiddleware::class], function () {
                /**
                 * dashboard
                 */
                Router::get('/', [ColleagueHomeController::class, 'index'])->name('colleague.index');
            });

            //==========================
            // other routes
            //==========================
            /**
             * index routes
             */
            Router::get('/', [HomeController::class, 'index'])->name('home.index');

            /**
             * common routes
             */
            Router::get('/about', [PageController::class, 'about'])->name('home.about');
            Router::get('/faq', [PageController::class, 'faq'])->name('home.faq');
            Router::form('/contact', [PageController::class, 'contact'])->name('home.contact');
            Router::form('/complaint', [PageController::class, 'complaint'])->name('home.complaint');
            Router::get('/pages/{url}', [PageController::class, 'pages'])->where([
                'url' => '.+',
            ])->name('home.pages');

            /**
             * login & signup routes
             */
            Router::form('/login', [LoginController::class, 'index'])->name('home.login');
            Router::form('/forget-password/{step?}', [LoginController::class, 'forgetPassword'])->where([
                'step' => 'step[1|2|3|4]',
            ])->name('home.forget-password');
            Router::form('/signup', [RegisterController::class, 'index'])->name('home.signup');
            Router::form('/signup/code', [RegisterController::class, 'enterCode'])->name('home.signup.code');
            Router::form('/signup/password', [RegisterController::class, 'enterPassword'])->name('home.signup.password');

            /**
             * cart routes
             */
            Router::get('/cart', [CartController::class, 'index'])->name('home.cart');

            /**
             * product routes
             */
            Router::get('/search/{category?}/{category_slug?}', [ProductController::class, 'index'])->where([
                'category' => '[0-9]+',
            ])->name('home.search');
            Router::get('/product/{id}/{slug?}', [ProductController::class, 'show'])->where([
                'id' => '[0-9]+',
            ])->name('home.product.show');
            Router::get('/compare', [CompareController::class, 'compare'])->name('home.compare');

            /**
             * brand routes
             */
            Router::get('/brands/{id}/{slug?}', [BrandController::class, 'show'])->where([
                'id' => '[0-9]+'
            ])->name('home.brand.show');

            /**
             * blog routes
             */
            Router::get('/blog/', [BlogController::class, 'index'])->name('home.blog');
            Router::get('/blog/{id}/{slug?}', [BlogController::class, 'show'])->where([
                'id' => '[0-9]+',
            ])->name('home.blog.show');
        });
    }

    /**
     * Routes of ajax part
     */
    protected function ajaxRoutes()
    {
        Router::group([
            'prefix' => '/ajax/',
            'exceptionHandler' => CustomExceptionHandler::class,
        ], function () {
            /**
             * newsletter route
             */
            Router::post('/newsletter/add', [PageController::class, 'addNewsletter'])->name('ajax.newsletter.add');

            /**
             * cart routes
             */
            Router::post('/cart/add/{product_code}', [CartController::class, 'addToCart'])->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.add');
            Router::post('/cart/update/{product_code}', [CartController::class, 'updateCart'])->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.update');
            Router::post('/cart/remove/{product_code}', [CartController::class, 'removeFromCart'])->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.remove');

            /**
             * blog route
             */
            Router::get('/blog/search', [BlogController::class, 'search'])->name('ajax.blog.search');

            /**
             * product route
             */
            Router::get('/product/search', [ProductController::class, 'search'])->name('ajax.product.search');
            Router::get('/product/price/{product_code}', [ProductController::class, 'getPrice'])->where([
                'product_code' => '\w+',
            ])->name('ajax.product.price');
            Router::get('/product/comments/{product_id}', [CommentController::class, 'paginate'])->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.comments');
            Router::post('/product/comment/add/{product_id}', [CommentController::class, 'saveComment'])->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.comments');
            Router::post('/product/wishlist/toggle/{product_id}', [ProductController::class, 'wishlistToggle'])->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.wishlist.toggle');
            Router::post('/product/wishlist/remove/{product_id}', [ProductController::class, 'wishlistRemove'])->where([
                'product_id' => '[0-9]+',
            ])->name('ajax.product.wishlist.remove');

            /**
             * get provinces
             */
            Router::get('/provinces', [PageController::class, 'getProvinces'])->name('ajax.province.get');

            /**
             * get cities
             */
            Router::get('/cities/{province_id}', [PageController::class, 'getCities'])->where([
                'province_id' => '[0-9]+',
            ])->name('ajax.city.get');

            /**
             * get captcha image
             */
            Router::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('api.captcha');

            // other pages that need authentication
            Router::group(['middleware' => AdminAuthMiddleware::class], function () {
                /**
                 * editor browser route
                 */
                Router::get('/editor/browser', [AdminHomeController::class, 'browser'])->name('ajax.editor.browser');

                /**
                 * user route
                 */
                Router::delete('/user/remove/{id}', [AdminUserController::class, 'remoce'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.remove');

                /**
                 * address route
                 */
                Router::get('/address/get/{user_id}/{id}', [AddressController::class, 'get'])->where([
                    'user_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.get');
                Router::post('/address/add/{user_id}', [AddressController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.admin.addr.add');
                Router::post('/address/edit/{user_id}/{id}', [AddressController::class, 'edit'])->where([
                    'user_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.edit');
                Router::delete('/address/remove/{id}', [AddressController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.remove');

                /**
                 * user order route
                 */
                Router::delete('/user/order/remove/{id}', [AdminUserController::class, 'removeOrder'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.order.remove');

                /**
                 * unit route
                 */
                Router::get('/unit/get/{id}', [UnitController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.get');
                Router::post('/unit/add/{user_id}', [UnitController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.unit.add');
                Router::post('/unit/edit/{id}', [UnitController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.edit');
                Router::delete('/unit/remove/{id}', [UnitController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.remove');

                /**
                 * blog route
                 */
                Router::delete('/blog/remove/{id}', [BlogController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.remove');

                /**
                 * blog category route
                 */
                Router::delete('/blog/category/remove/{id}', [BlogCategoryController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.category.remove');
                Router::post('/blog/category/side-status/{id}', [BlogCategoryController::class, 'sideStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.category.side.status');

                /**
                 * static page route
                 */
                Router::delete('/static-page/remove/{id}', [StaticPageController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.static.page.remove');

                /**
                 * faq route
                 */
                Router::get('/faq/get/{id}', [FaqController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.get');
                Router::post('/faq/add/{user_id}', [FaqController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.faq.add');
                Router::post('/faq/edit/{id}', [FaqController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.edit');
                Router::delete('/faq/remove/{id}', [FaqController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.remove');

                /**
                 * payment method route
                 */
                Router::delete('/pay-method/remove/{id}', [PaymentMethodController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.pay_method.remove');

                /**
                 * send method route
                 */
                Router::delete('/send-method/remove/{id}', [SendMethodController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.send_method.remove');

                /**
                 * color route
                 */
                Router::delete('/color/remove/{id}', [ColorController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.color.remove');

                /**
                 * brand route
                 */
                Router::post('/brand/slider-status/{id}', [BrandController::class, 'sliderStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.brand.slider.status');

                /**
                 * festival route
                 */
                Router::delete('/festival/remove/{id}', [FestivalController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.remove');
                Router::post('/festival/pub-status/{id}', [FestivalController::class, 'pubStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.pub.status');
                Router::post('/festival/main-status/{id}', [FestivalController::class, 'mainStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.main.status');

                /**
                 * product festival route
                 */
                Router::post('/product/festival/add/{f_id}', [ProductFestivalController::class, 'add'])->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.add');
                Router::post('/product/festival/add-category/{f_id}', [ProductFestivalController::class, 'addCategory'])->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.category.add');
                Router::delete('/product/festival/category/remove/{f_id}', [ProductFestivalController::class, 'removeCategory'])->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.category.remove');
                Router::delete('/product/festival/remove/{id}', [ProductFestivalController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.product.festival.remove');

                /**
                 * product route
                 */
                Router::delete('/product/remove/{id}', [AdminProductController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.product.remove');
                Router::post('/product/pub-status/{id}', [AdminProductController::class, 'pubStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.product.status');
                Router::post('/product/av-status/{id}', [AdminProductController::class, 'availabilityStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.product.availability.status');

                /**
                 * stepped price route
                 */
                Router::delete('/product/stepped-price/remove/{id}', [SteppedPriceController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.product.stepped.remove');
                Router::delete('/product/stepped-price/remove-all/{code}', [SteppedPriceController::class, 'removeAll'])->where([
                    'code' => '[a-zA-Z0-9]+',
                ])->name('ajax.product.stepped.remove.all');

                /**
                 * comment route
                 */
                Router::delete('/comment/remove/{id}', [CommentController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.comment.remove');
                Router::post('/comment/condition/{p_id}/{id}', [CommentController::class, 'conditionChange'])->where([
                    'p_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.comment.condition');

                /**
                 * order route
                 */
                Router::get('/order/info/{id}', [OrderController::class, 'getInfo'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.order.info');

                /**
                 * wallet route
                 */
                Router::delete('/wallet/remove/{id}', [WalletController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.wallet.remove');

                /**
                 * deposit type route
                 */
                Router::get('/deposit-type/get/{id}', [DepositTypeController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.deposit-type.get');
                Router::post('/deposit-type/add/{user_id}', [DepositTypeController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.deposit-type.add');
                Router::post('/deposit-type/edit/{id}', [DepositTypeController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.deposit-type.edit');
                Router::delete('/deposit-type/remove/{id}', [DepositTypeController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.deposit-type.remove');


                /**
                 * slider route
                 */
                Router::get('/slider/get/{id}', [SliderController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.get');
                Router::post('/slider/add/{user_id}', [SliderController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.slider.add');
                Router::post('/slider/edit/{id}', [SliderController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.edit');
                Router::delete('/slider/remove/{id}', [SliderController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.remove');

                /**
                 * newsletter route
                 */
                Router::delete('/admin/newsletter/remove/{id}', [NewsletterController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.newsletter.remove');
                Router::post('/admin/newsletter/add/{id}', [NewsletterController::class, 'add'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.newsletter.add');

                /**
                 * instagram route
                 */
                Router::get('/instagram/get/{id}', [InstagramController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.get');
                Router::post('/instagram/add/{user_id}', [InstagramController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.instagram.add');
                Router::post('/instagram/edit/{id}', [InstagramController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.edit');
                Router::delete('/instagram/remove/{id}', [InstagramController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.remove');

                /**
                 * order badge route
                 */
                Router::get('/badge/get/{id}', [OrderBadgeController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.get');
                Router::post('/badge/add/{user_id}', [OrderBadgeController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.badge.add');
                Router::post('/badge/edit/{id}', [OrderBadgeController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.edit');
                Router::delete('/badge/remove/{id}', [OrderBadgeController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.remove');

                /**
                 * category route
                 */
                Router::post('/category/menu-status/{id}', [CategoryController::class, 'menuStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.category.menu.status');

                /**
                 * category images route
                 */
                Router::post('/category/image/add/{c_id}', [CategoryImageController::class, 'add'])->where([
                    'c_id' => '[0-9]+',
                ])->name('ajax.category.image.add');
                Router::post('/category/image/edit/{c_id}/{id}', [CategoryImageController::class, 'edit'])->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.edit');
                Router::get('/category/image/get/{c_id}/{id}', [CategoryImageController::class, 'get'])->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.get');
                Router::delete('/category/image/remove/{c_id}/{id}', [CategoryImageController::class, 'remove'])->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.remove');

                /**
                 * coupon route
                 */
                Router::delete('/coupon/remove/{id}', [CouponController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.coupon.remove');

                /**
                 * security question route
                 */
                Router::post('/sec-question/add', [SecurityQuestionController::class, 'add'])->where([
                    'c_id' => '[0-9]+',
                ])->name('ajax.sec_question.add');
                Router::post('/sec-question/edit/{id}', [SecurityQuestionController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.edit');
                Router::get('/sec-question/get/{id}', [SecurityQuestionController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.get');
                Router::delete('/sec-question/remove/{id}', [SecurityQuestionController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.remove');

                /**
                 * contact us route
                 */
                Router::delete('/contact-us/remove/{id}', [ContactUsController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.contact-us.remove');

                /**
                 * complaint route
                 */
                Router::delete('/complaints/remove/{id}', [ComplaintsController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.complaints.remove');

                /**
                 * File Manager Route
                 */
                Router::get('/file-manager/list', [FileController::class, 'list'])->name('api.file-manager.list');
                Router::post('/file-manager/rename', [FileController::class, 'rename'])->name('api.file-manager.rename');
                Router::post('/file-manager/delete', [FileController::class, 'delete'])->name('api.file-manager.delete');
                Router::post('/file-manager/mkdir', [FileController::class, 'makeDir'])->name('api.file-manager.mkdir');
                Router::post('/file-manager/mvdir', [FileController::class, 'moveDir'])->name('api.file-manager.mvdir');
                Router::post('/file-manager/upload', [FileController::class, 'upload'])->name('api.file-manager.upload');
                Router::get('/file-manager/download/{filename}', [FileController::class, 'download'])->where([
                    'filename' => '.+',
                ])->name('api.file-manager.download');
                Router::get('/file-manager/dir-tree', [FileController::class, 'foldersTree'])->name('api.file-manager.tree');
            });
        });
    }

    /**
     * Routes of api part
     */
    protected function apiRoutes()
    {
        Router::group([
            'prefix' => '/api/',
            'exceptionHandler' => CustomExceptionHandler::class,
            'middleware' => ApiVerifierMiddleware::class
        ], function () {

        });
    }
}
