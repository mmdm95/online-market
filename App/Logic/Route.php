<?php

namespace App\Logic;

use App\Logic\Controllers\Admin\{BlogController as AdminBlogController,
    BlogCategoryController as AdminBlogCategoryController,
    BrandController as AdminBrandController,
    CategoryController as AdminCategoryController,
    CategoryImageController as AdminCategoryImageController,
    ColorController as AdminColorController,
    CommentController as AdminCommentController,
    ComplaintsController as AdminComplaintsController,
    ContactUsController as AdminContactUsController,
    CouponController as AdminCouponController,
    DepositTypeController as AdminDepositTypeController,
    FaqController as AdminFaqController,
    FestivalController as AdminFestivalController,
    FileController as AdminFileController,
    GuideController as AdminGuideController,
    HomeController as AdminHomeController,
    InstagramController as AdminInstagramController,
    NewsletterController as AdminNewsletterController,
    OrderBadgeController as AdminOrderBadgeController,
    OrderController as AdminOrderController,
    PaymentMethodController as AdminPaymentMethodController,
    ProductFestivalController as AdminProductFestivalController,
    ReportUserController as AdminReportUserController,
    ReturnOrderController as AdminReturnOrderController,
    SecurityQuestionController as AdminSecurityQuestionController,
    SendMethodController as AdminSendMethodController,
    SettingController as AdminSettingController,
    SliderController as AdminSliderController,
    StaticPageController as AdminStaticPageController,
    SteppedPriceController as AdminSteppedPriceController,
    UnitController as AdminUnitController,
    UserController as AdminUserController,
    WalletController as AdminWalletController,
    ProductController as AdminProductController,
    AddressController as AdminAddressController};
use App\Logic\Controllers\CheckoutController;
use App\Logic\Controllers\OrderResult;
use App\Logic\Controllers\User\{AddressController as UserAddressController,
    CommentController as UserCommentController,
    HomeController as UserHomeController,
    OrderController as UserOrderController,
    ReturnOrderController as UserReturnOrderController,
    WalletController as UserWalletController};
use App\Logic\Controllers\Colleague\{HomeController as ColleagueHomeController};
use App\Logic\Controllers\BlogController;
use App\Logic\Controllers\BrandController;
use App\Logic\Controllers\CaptchaController;
use App\Logic\Controllers\CartController;
use App\Logic\Controllers\CommentController;
use App\Logic\Controllers\CompareController;
use App\Logic\Controllers\HomeController;
use App\Logic\Controllers\LoginController;
use App\Logic\Controllers\PageController;
use App\Logic\Controllers\ProductController;
use App\Logic\Controllers\RegisterController;
use App\Logic\Handlers\CustomExceptionHandler;
use App\Logic\Middlewares\AdminAuthMiddleware;
use App\Logic\Middlewares\ApiVerifierMiddleware;
use App\Logic\Middlewares\AuthMiddleware;
use App\Logic\Middlewares\CartMiddleware;
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
                Router::post('/address/view/dt/{user_id}', [AdminAddressController::class, 'getPaginatedDatatable'])->where([
                    'user_id' => '[0-9]+',
                ])->name('admin.addr.dt.view');

                /**
                 * User Order Route
                 */
                Router::post('/user/order/view/dt/{user_id}', [AdminUserController::class, 'getOrderPaginatedDatatable'])->where([
                    'user_id' => '[0-9]+',
                ])->name('admin.user.order.dt.view');

                /**
                 * Category Route
                 */
                Router::form('/category/add', [AdminCategoryController::class, 'add'])->name('admin.category.add');
                Router::form('/category/edit/{id}', [AdminCategoryController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.category.edit');
                Router::get('/category/view', [AdminCategoryController::class, 'view'])->name('admin.category.view');
                Router::post('/category/view/dt', [AdminCategoryController::class, 'getPaginatedDatatable'])->name('admin.category.dt.view');

                /**
                 * Category Image Route
                 */
                Router::get('/category/image/view', [AdminCategoryImageController::class, 'view'])->name('admin.category.image.view');
                Router::post('/category/image/view/dt', [AdminCategoryImageController::class, 'getPaginatedDatatable'])
                    ->name('admin.category.image.dt.view');

                /**
                 * Coupon Route
                 */
                Router::form('/coupon/add', [AdminCouponController::class, 'add'])->name('admin.coupon.add');
                Router::form('/coupon/edit/{id}', [AdminCouponController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.coupon.edit');
                Router::get('/coupon/view', [AdminCouponController::class, 'view'])->name('admin.coupon.view');
                Router::post('/coupon/view/dt', [AdminCouponController::class, 'getPaginatedDatatable'])->name('admin.coupon.dt.view');

                /**
                 * Payment Methods Route
                 */
                Router::form('/pay-method/add', [AdminPaymentMethodController::class, 'add'])->name('admin.pay_method.add');
                Router::form('/pay-method/edit/{id}', [AdminPaymentMethodController::class, 'edit'])->where([
                    'id' => '[0-9]',
                ])->name('admin.pay_method.edit');
                Router::get('/pay-method/view', [AdminPaymentMethodController::class, 'view'])->name('admin.pay_method.view');
                Router::post('/pay-method/view/dt', [AdminPaymentMethodController::class, 'getPaginatedDatatable'])->name('admin.pay_method.dt.view');

                /**
                 * Send Methods Route
                 */
//                Router::form('/send-method/add', [AdminSendMethodController::class, 'add'])->name('admin.send_method.add');
//                Router::form('/send-method/edit/{id}', [AdminSendMethodController::class, 'edit'])->where([
//                    'id' => '[0-9]',
//                ])->name('admin.send_method.edit');
//                Router::get('/send-method/view', [AdminSendMethodController::class, 'view'])->name('admin.send_method.view');
//                Router::post('/send-method/view/dt', [AdminSendMethodController::class, 'getPaginatedDatatable'])->name('admin.send_method.dt.view');

                /**
                 * Color Route
                 */
                Router::form('/color/add', [AdminColorController::class, 'add'])->name('admin.color.add');
                Router::form('/color/edit/{id}', [AdminColorController::class, 'edit'])->where([
                    'id' => '[0-9]',
                ])->name('admin.color.edit');
                Router::get('/color/view', [AdminColorController::class, 'view'])->name('admin.color.view');
                Router::post('/color/view/dt', [AdminColorController::class, 'getPaginatedDatatable'])->name('admin.color.dt.view');

                /**
                 * Festival Route
                 */
                Router::form('/festival/add', [AdminFestivalController::class, 'add'])->name('admin.festival.add');
                Router::form('/festival/edit/{id}', [AdminFestivalController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.festival.edit');
                Router::get('/festival/view', [AdminFestivalController::class, 'view'])->name('admin.festival.view');
                Router::post('/festival/view/dt', [AdminFestivalController::class, 'getPaginatedDatatable'])->name('admin.festival.dt.view');

                /**
                 * Product Festival Route
                 */
                Router::form('/festival/detail/{id}', [AdminProductFestivalController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.product.festival.detail');
                Router::post('/product/festival/view/dt/{f_id}', [AdminProductFestivalController::class, 'getPaginatedDatatable'])->where([
                    'f_id' => '[0-9]+',
                ])->name('admin.product.festival.dt.view');

                /**
                 * Brand Route
                 */
                Router::form('/brand/add', [AdminBrandController::class, 'add'])->name('admin.brand.add');
                Router::form('/brand/edit/{id}', [AdminBrandController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.brand.edit');
                Router::get('/brand/view', [AdminBrandController::class, 'view'])->name('admin.brand.view');
                Router::post('/brand/view/dt', [AdminBrandController::class, 'getPaginatedDatatable'])
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
                Router::form('/product/stepped-price/add/{code}', [AdminSteppedPriceController::class, 'add'])->where([
                    'code' => '[a-zA-Z0-9]+',
                ])->name('admin.stepped-price.add');
                Router::form('/product/stepped-price/edit/{code}/{id}', [AdminSteppedPriceController::class, 'edit'])->where([
                    'code' => '[a-zA-Z0-9]+',
                    'id' => '[0-9]+',
                ])->name('admin.stepped-price.edit');
                Router::get('/product/stepped-price/view/{p_id}', [AdminSteppedPriceController::class, 'view'])->where([
                    'p_id' => '[0-9]+',
                ])->name('admin.stepped-price.view');
                Router::get('/product/stepped-price/view-all/{code}', [AdminSteppedPriceController::class, 'viewStepped'])->where([
                    'code' => '[a-zA-Z0-9]+',
                ])->name('admin.stepped-price.view');

                /**
                 * Comment Route
                 */
                Router::get('/comment/view/{p_id}', [AdminCommentController::class, 'view'])->where([
                    'p_id' => '[0-9]+',
                ])->name('admin.comment.view');
                Router::form('/comment/detail/{p_id}/{id}', [AdminCommentController::class, 'detail'])->where([
                    'p_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('admin.comment.detail');
                Router::post('/comment/view/dt/{p_id}', [AdminCommentController::class, 'getPaginatedDatatable'])->where([
                    'p_id' => '[0-9]+',
                ])->name('admin.comment.dt.view');

                /**
                 * Blog Route
                 */
                Router::form('/blog/add', [AdminBlogController::class, 'add'])->name('admin.blog.add');
                Router::form('/blog/edit/{id}', [AdminBlogController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.blog.edit');
                Router::get('/blog/view', [AdminBlogController::class, 'view'])->name('admin.blog.view');
                Router::post('/blog/view/dt', [AdminBlogController::class, 'getPaginatedDatatable'])->name('admin.blog.dt.view');

                /**
                 * Blog Category Route
                 */
                Router::form('/blog/category/add', [AdminBlogCategoryController::class, 'add'])->name('admin.blog.category.add');
                Router::form('/blog/category/edit/{id}', [AdminBlogCategoryController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.blog.category.edit');
                Router::get('/blog/category/view', [AdminBlogCategoryController::class, 'view'])->name('admin.blog.category.view');
                Router::post('/blog/category/view/dt', [AdminBlogCategoryController::class, 'getPaginatedDatatable'])
                    ->name('admin.blog.category.dt.view');

                /**
                 * Order Route
                 */
                Router::form('/order/detail/{id}', [AdminOrderController::class, 'detail'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.order.detail');
                Router::get('/order/view', [AdminOrderController::class, 'view'])->name('admin.order.view');
                Router::post('/order/view/dt', [AdminOrderController::class, 'getPaginatedDatatable'])->name('admin.order.dt.view');

                /**
                 * Order Badges Route
                 */
                Router::get('/order/badges', [AdminOrderBadgeController::class, 'view'])->name('admin.badge.view');
                Router::post('/order/badges/dt', [AdminOrderBadgeController::class, 'getPaginatedDatatable'])->name('admin.badge.dt.view');

                /**
                 * Return Order Route
                 */
//                Router::get('/return-order/detail/{id}', [AdminReturnOrderController::class, 'detail'])->where([
//                    'id' => '[0-9]+',
//                ])->name('admin.return.order.detail');
//                Router::get('/return-order/view', [AdminReturnOrderController::class, 'view'])->name('admin.return.order.view');
//                Router::post('/return-order/view/dt', [AdminReturnOrderController::class, 'getPaginatedDatatable'])->name('admin.return.order.dt.view');

                /**
                 * Report User Route
                 */
                Router::get('/report/users', [AdminReportUserController::class, 'usersReport'])->name('admin.report.users');
                Router::get('/report/users/dt', [AdminReportUserController::class, 'userFilter'])->name('admin.report.users.dt');

                /**
                 * Wallet Route
                 */
                Router::form('/wallet/charge/{id}', [AdminWalletController::class, 'charge'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.wallet.charge');
                Router::get('/wallet/view', [AdminWalletController::class, 'view'])->name('admin.wallet.view');
                Router::post('/wallet/view/dt', [AdminWalletController::class, 'getPaginatedDatatable'])->name('admin.wallet.dt.view');
                Router::get('/wallet/detail/{id}', [AdminWalletController::class, 'detail'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.wallet.detail');;
                Router::post('/wallet/detail/dt/{username}', [AdminWalletController::class, 'getDetailPaginatedDatatable'])
                    ->name('admin.wallet.detail.dt');

                /**
                 * Deposit Type Route
                 */
                Router::get('/deposit-type/view', [AdminDepositTypeController::class, 'view'])->name('admin.deposit-type.view');
                Router::post('/deposit-type/view/dt', [AdminDepositTypeController::class, 'getPaginatedDatatable'])->name('admin.deposit-type.dt.view');

                /**
                 * Static Page Route
                 */
                Router::form('/static-page/add', [AdminStaticPageController::class, 'add'])->name('admin.static.page.add');
                Router::form('/static-page/edit/{id}', [AdminStaticPageController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.static.page.edit');
                Router::get('/static-page/view', [AdminStaticPageController::class, 'view'])->name('admin.static.page.view');
                Router::post('/static-page/view/dt', [AdminStaticPageController::class, 'getPaginatedDatatable'])->name('admin.static.page.dt.view');

                /**
                 * Unit Route
                 */
                Router::get('/unit/view', [AdminUnitController::class, 'view'])->name('admin.unit.view');
                Router::post('/unit/view/dt', [AdminUnitController::class, 'getPaginatedDatatable'])->name('admin.unit.dt.view');

                /**
                 * FAQ Route
                 */
                Router::get('/faq/view', [AdminFaqController::class, 'view'])->name('admin.faq.view');
                Router::post('/faq/view/dt', [AdminFaqController::class, 'getPaginatedDatatable'])->name('admin.faq.dt.view');

                /**
                 * Contact Us Route
                 */
                Router::get('/contact-us/view/{id?}', [AdminContactUsController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.contact-us.view');
                Router::post('/contact-us/view/dt', [AdminContactUsController::class, 'getPaginatedDatatable'])
                    ->name('admin.contact-us.dt.view');

                /**
                 * Complaints Route
                 */
                Router::get('/complaints/view/{id?}', [AdminComplaintsController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.complaints.view');
                Router::post('/complaints/view/dt', [AdminComplaintsController::class, 'getPaginatedDatatable'])
                    ->name('admin.complaints.dt.view');

                /**
                 * Newsletter Route
                 */
                Router::get('/newsletter/view/{id?}', [AdminNewsletterController::class, 'view'])->where([
                    'id' => '[0-9]+',
                ])->name('admin.newsletter.view');
                Router::post('/newsletter/view/dt', [AdminNewsletterController::class, 'getPaginatedDatatable'])
                    ->name('admin.newsletter.dt.view');

                /**
                 * Slider Route
                 */
                Router::get('/slider/view', [AdminSliderController::class, 'view'])->name('admin.slider.view');
                Router::post('/slider/view/dt', [AdminSliderController::class, 'getPaginatedDatatable'])->name('admin.slider.dt.view');

                /**
                 * Instagram Route
                 */
                Router::get('/instagram/view', [AdminInstagramController::class, 'view'])->name('admin.instagram.view');
                Router::post('/instagram/view/dt', [AdminInstagramController::class, 'getPaginatedDatatable'])->name('admin.instagram.dt.view');

                /**
                 * Security Question Route
                 */
                Router::get('/sec-question/view', [AdminSecurityQuestionController::class, 'view'])->name('admin.sec_question.view');
                Router::post('/sec-question/view/dt', [AdminSecurityQuestionController::class, 'getPaginatedDatatable'])
                    ->name('admin.sec_question.dt.view');

                /**
                 * Setting Route
                 */
                Router::form('/setting/main', [AdminSettingController::class, 'main'])->name('admin.setting.main');
                Router::form('/setting/top-menu', [AdminSettingController::class, 'topMenu'])->name('admin.setting.top-menu');
                Router::form('/setting/buy', [AdminSettingController::class, 'buy'])->name('admin.setting.buy');
                Router::form('/setting/sms', [AdminSettingController::class, 'sms'])->name('admin.setting.sms');
                Router::form('/setting/contact', [AdminSettingController::class, 'contact'])->name('admin.setting.contact');
                Router::form('/setting/social', [AdminSettingController::class, 'social'])->name('admin.setting.social');
                Router::form('/setting/footer', [AdminSettingController::class, 'footer'])->name('admin.setting.footer');
                Router::form('/setting/pages/index', [AdminSettingController::class, 'indexPage'])->name('admin.setting.pages.index');
                Router::form('/setting/pages/about', [AdminSettingController::class, 'aboutPage'])->name('admin.setting.pages.about');
                Router::form('/setting/other', [AdminSettingController::class, 'other'])->name('admin.setting.other');

                /**
                 * File Manager Route
                 */
                Router::get('/file-manager', [AdminFileController::class, 'index'])->name('admin.file-manager');

                /**
                 * Guide Route
                 */
                Router::get('/guide', [AdminGuideController::class, 'index'])->name('admin.guide');
            });

            // show images outside of public folder
            Router::get('/images/{filename}', [AdminFileController::class, 'showImage'])->where([
                'filename' => '.+',
            ])->name('image.show');

            //==========================
            // user routes
            //==========================
            Router::group(['prefix' => '/user/', 'middleware' => AuthMiddleware::class], function () {
                /**
                 * common routes
                 */
                Router::get('/', [UserHomeController::class, 'index'])->name('user.index');
                Router::form('/info', [UserHomeController::class, 'info'])->name('user.info');
                Router::get('/favorite', [UserHomeController::class, 'favorite'])->name('user.favorite');

                /**
                 * comment routes
                 */
                Router::get('/comments', [UserCommentController::class, 'index'])->name('user.comments');
                Router::post('/comment/decider/{id}', [UserCommentController::class, 'decider'])->where([
                    'id' => '[0-9]+',
                ])->name('user.comment.decider');
                Router::form('/comment/edit/{id}', [UserCommentController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('user.comment.edit');

                /**
                 * address routes
                 */
                Router::get('/addresses', [UserAddressController::class, 'index'])->name('user.addresses');
                Router::form('/address/add', [UserAddressController::class, 'add'])->name('user.address.add');
                Router::form('/address/edit/{id}', [UserAddressController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('user.address.edit');

                /**
                 * order routes
                 */
                Router::get('/orders', [UserOrderController::class, 'index'])->name('user.orders');
                Router::form('/order/detail/{id}', [UserOrderController::class, 'detail'])->where([
                    'id' => '[0-9]+',
                ])->name('user.order.detail');

                /**
                 * return order routes
                 */
//                Router::form('/return-order', [UserReturnOrderController::class, 'index'])->name('user.return-order');
//                Router::get('/return-order/detail/{id}', [UserReturnOrderController::class, 'detail'])->where([
//                    'id' => '[0-9]+',
//                ])->name('user.return-order.detail');
//                Router::get('/return-order/add', [UserReturnOrderController::class, 'add'])->name('user.return-order.add');

                /**
                 * wallet routes
                 */
//                Router::form('/wallet', [UserWalletController::class, 'index'])->name('user.wallet');
//                Router::form('/wallet/charge', [UserWalletController::class, 'charge'])->name('user.wallet.charge');
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
             * logout route
             */
            Router::form('/logout', [LoginController::class, 'logout'])->name('home.logout');

            /**
             * cart routes
             */
            Router::get('/cart', [CartController::class, 'index'])->name('home.cart');
            Router::group(['middleware' => AuthMiddleware::class], function () {
                Router::group(['middleware' => CartMiddleware::class], function () {
                    Router::get('/checkout', [CheckoutController::class, 'checkout'])->name('home.checkout');
                });
            });
            Router::get('/finish/{type}/{code}', [OrderResult::class, 'index'])->where([
                'type' => '[a-zA-Z0-9]+',
                'code' => '[a-zA-Z0-9]+',
            ])->name('home.finish');

            /**
             * product routes
             */
            Router::get('/search/{category?}/{category_slug?}', [ProductController::class, 'index'])->where([
                'category' => '[0-9]+',
                'category_slug' => '.*',
            ])->name('home.search');
            Router::get('/product/{id}/{slug?}', [ProductController::class, 'show'])->where([
                'id' => '[0-9]+',
                'slug' => '.*',
            ])->name('home.product.show');
//            Router::get('/compare', [CompareController::class, 'compare'])->name('home.compare');

            /**
             * brand routes
             */
//            Router::get('/brands', [BrandController::class, 'index'])->name('home.brands');
//            Router::get('/brand/{id}/{slug?}', [BrandController::class, 'show'])->where([
//                'id' => '[0-9]+'
//            ])->name('home.brand.show');

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
            Router::post('/admin/newsletter/add', [AdminNewsletterController::class, 'add'])->name('ajax.admin.newsletter.add');
            Router::post('/admin/newsletter/remove/{id}', [AdminNewsletterController::class, 'remove'])->where([
                'id' => '[0-9]+'
            ])->name('ajax.admin.newsletter.remove');

            /**
             * cart routes
             */
            Router::get('/cart/get', [CartController::class, 'cartTopInfo'])->name('ajax.cart.get');
            Router::post('/cart/add/{product_code}', [CartController::class, 'addToCart'])->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.add');
            Router::put('/cart/update/{product_code}', [CartController::class, 'updateCart'])->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.update');
            Router::delete('/cart/remove/{product_code}', [CartController::class, 'removeFromCart'])->where([
                'product_code' => '\w+',
            ])->name('ajax.cart.remove');
            Router::get('/cart/items-table', [CartController::class, 'getCartProducts'])->name('ajax.cart.items');
            Router::get('/cart/total-items-info', [CartController::class, 'getCartProductsInfo'])->name('ajax.cart.total.items.info');
            Router::get('/cart/total-info', [CartController::class, 'getCartProductsTotalInfo'])->name('ajax.cart.total.info');
            Router::post('/cart/check-coupon/{coupon_code}', [CartController::class, 'checkCoupon'])->where([
                'coupon_code' => '[\w-]+',
            ])->name('ajax.cart.check.coupon');
            Router::post('/cart/check-stored-coupon', [CartController::class, 'checkStoredCoupon'])->name('ajax.cart.check.stored.coupon');
            Router::post('/cart/check-post-price', [CheckoutController::class, 'calculateSendPrice'])->name('ajax.cart.check.post.price');

            /**
             * cart routes
             */
            Router::group(['middleware' => AuthMiddleware::class], function () {
                Router::post('/checkout/check', [CheckoutController::class, 'issuingFactorNConnectToGateway']);
            });

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
            ])->name('ajax.product.comments.add');
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
                'province_id' => '\-?[0-9]+',
            ])->name('ajax.city.get');

            /**
             * get captcha image
             */
            Router::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('api.captcha');

            // user pages that need authentication
            Router::group(['middleware' => AuthMiddleware::class], function () {
                /**
                 * favorite route
                 */
                Router::delete('/user/favorite/remove/{id}', [UserHomeController::class, 'removeFavorite'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.favorite.remove');

                /**
                 * comment route
                 */
                Router::delete('/user/comment/remove/{id}', [UserCommentController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.comment.remove');

                /**
                 * address route
                 */
                Router::get('/user/address/get/{id}', [UserAddressController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax..user.address.get');
                Router::delete('/user/address/remove/{id}', [UserAddressController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.address.remove');
            });

            // other pages that need authentication
            Router::group(['middleware' => AdminAuthMiddleware::class], function () {
                /**
                 * editor browser route
                 */
                Router::get('/editor/browser', [AdminHomeController::class, 'browser'])->name('ajax.editor.browser');

                /**
                 * user route
                 */
                Router::delete('/user/remove/{id}', [AdminUserController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.user.remove');

                /**
                 * address route
                 */
                Router::get('/address/get/{user_id}/{id}', [UserAddressController::class, 'get'])->where([
                    'user_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.get');
                Router::post('/address/add/{user_id}', [UserAddressController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.admin.addr.add');
                Router::post('/address/edit/{user_id}/{id}', [UserAddressController::class, 'edit'])->where([
                    'user_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.admin.addr.edit');
                Router::delete('/address/remove/{id}', [UserAddressController::class, 'remove'])->where([
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
                Router::get('/unit/get/{id}', [AdminUnitController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.get');
                Router::post('/unit/add', [AdminUnitController::class, 'add'])->name('ajax.unit.add');
                Router::post('/unit/edit/{id}', [AdminUnitController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.edit');
                Router::delete('/unit/remove/{id}', [AdminUnitController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.unit.remove');

                /**
                 * blog route
                 */
                Router::delete('/blog/remove/{id}', [AdminBlogController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.remove');

                /**
                 * blog category route
                 */
                Router::delete('/blog/category/remove/{id}', [AdminBlogCategoryController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.category.remove');
                Router::post('/blog/category/side-status/{id}', [AdminBlogCategoryController::class, 'sideStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.blog.category.side.status');

                /**
                 * static page route
                 */
                Router::delete('/static-page/remove/{id}', [AdminStaticPageController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.static.page.remove');

                /**
                 * faq route
                 */
                Router::get('/faq/get/{id}', [AdminFaqController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.get');
                Router::post('/faq/add', [AdminFaqController::class, 'add'])->name('ajax.faq.add');
                Router::post('/faq/edit/{id}', [AdminFaqController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.edit');
                Router::delete('/faq/remove/{id}', [AdminFaqController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.faq.remove');

                /**
                 * payment method route
                 */
                Router::delete('/pay-method/remove/{id}', [AdminPaymentMethodController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.pay_method.remove');

                /**
                 * send method route
                 */
//                Router::delete('/send-method/remove/{id}', [AdminSendMethodController::class, 'remove'])->where([
//                    'id' => '[0-9]+',
//                ])->name('ajax.send_method.remove');

                /**
                 * color route
                 */
                Router::delete('/color/remove/{id}', [AdminColorController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.color.remove');

                /**
                 * brand route
                 */
                Router::delete('/brand/remove/{id}', [AdminBrandController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.brand.remove');
                Router::post('/brand/slider-status/{id}', [AdminBrandController::class, 'sliderStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.brand.slider.status');

                /**
                 * festival route
                 */
                Router::delete('/festival/remove/{id}', [AdminFestivalController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.remove');
                Router::post('/festival/pub-status/{id}', [AdminFestivalController::class, 'pubStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.pub.status');
                Router::post('/festival/main-status/{id}', [AdminFestivalController::class, 'mainStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.festival.main.status');

                /**
                 * product festival route
                 */
                Router::post('/product/festival/add/{f_id}', [AdminProductFestivalController::class, 'add'])->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.add');
                Router::post('/product/festival/add-category/{f_id}', [AdminProductFestivalController::class, 'addCategory'])->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.category.add');
                Router::delete('/product/festival/category/remove/{f_id}', [AdminProductFestivalController::class, 'removeCategory'])->where([
                    'f_id' => '[0-9]+',
                ])->name('ajax.product.festival.category.remove');
                Router::delete('/product/festival/remove/{id}', [AdminProductFestivalController::class, 'remove'])->where([
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
                Router::delete('/product/stepped-price/remove/{id}', [AdminSteppedPriceController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.product.stepped.remove');
                Router::delete('/product/stepped-price/remove-all/{code}', [AdminSteppedPriceController::class, 'removeAll'])->where([
                    'code' => '[a-zA-Z0-9]+',
                ])->name('ajax.product.stepped.remove.all');

                /**
                 * comment route
                 */
                Router::delete('/comment/remove/{id}', [AdminCommentController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.comment.remove');
                Router::post('/comment/condition/{p_id}/{id}', [AdminCommentController::class, 'conditionChange'])->where([
                    'p_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.comment.condition');

                /**
                 * order route
                 */
                Router::get('/order/info/{id}', [AdminOrderController::class, 'getInfo'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.order.info');

                /**
                 * wallet route
                 */
                Router::delete('/wallet/remove/{id}', [AdminWalletController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.wallet.remove');

                /**
                 * deposit type route
                 */
                Router::get('/deposit-type/get/{id}', [AdminDepositTypeController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.deposit-type.get');
                Router::post('/deposit-type/add/{user_id}', [AdminDepositTypeController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.deposit-type.add');
                Router::post('/deposit-type/edit/{id}', [AdminDepositTypeController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.deposit-type.edit');
                Router::delete('/deposit-type/remove/{id}', [AdminDepositTypeController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.deposit-type.remove');


                /**
                 * slider route
                 */
                Router::get('/slider/get/{id}', [AdminSliderController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.get');
                Router::post('/slider/add', [AdminSliderController::class, 'add'])->name('ajax.slider.add');
                Router::post('/slider/edit/{id}', [AdminSliderController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.edit');
                Router::delete('/slider/remove/{id}', [AdminSliderController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.slider.remove');

                /**
                 * newsletter route
                 */
                Router::delete('/admin/newsletter/remove/{id}', [AdminNewsletterController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.newsletter.remove');
                Router::post('/admin/newsletter/add/{id}', [AdminNewsletterController::class, 'add'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.admin.newsletter.add');

                /**
                 * instagram route
                 */
                Router::get('/instagram/get/{id}', [AdminInstagramController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.get');
                Router::post('/instagram/add', [AdminInstagramController::class, 'add'])->name('ajax.instagram.add');
                Router::post('/instagram/edit/{id}', [AdminInstagramController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.edit');
                Router::delete('/instagram/remove/{id}', [AdminInstagramController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.instagram.remove');

                /**
                 * order badge route
                 */
                Router::get('/badge/get/{id}', [AdminOrderBadgeController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.get');
                Router::post('/badge/add/{user_id}', [AdminOrderBadgeController::class, 'add'])->where([
                    'user_id' => '[0-9]+',
                ])->name('ajax.badge.add');
                Router::post('/badge/edit/{id}', [AdminOrderBadgeController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.edit');
                Router::delete('/badge/remove/{id}', [AdminOrderBadgeController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.badge.remove');

                /**
                 * category route
                 */
                Router::delete('/category/remove/{id}', [AdminCategoryController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.category.remove');
                Router::post('/category/menu-status/{id}', [AdminCategoryController::class, 'menuStatusChange'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.category.menu.status');

                /**
                 * category images route
                 */
                Router::post('/category/image/add/{c_id}', [AdminCategoryImageController::class, 'add'])->where([
                    'c_id' => '[0-9]+',
                ])->name('ajax.category.image.add');
                Router::post('/category/image/edit/{c_id}/{id}', [AdminCategoryImageController::class, 'edit'])->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.edit');
                Router::get('/category/image/get/{c_id}/{id}', [AdminCategoryImageController::class, 'get'])->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.get');
                Router::delete('/category/image/remove/{c_id}/{id}', [AdminCategoryImageController::class, 'remove'])->where([
                    'c_id' => '[0-9]+',
                    'id' => '[0-9]+',
                ])->name('ajax.category.image.remove');

                /**
                 * coupon route
                 */
                Router::delete('/coupon/remove/{id}', [AdminCouponController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.coupon.remove');

                /**
                 * security question route
                 */
                Router::post('/sec-question/add', [AdminSecurityQuestionController::class, 'add'])->where([
                    'c_id' => '[0-9]+',
                ])->name('ajax.sec_question.add');
                Router::post('/sec-question/edit/{id}', [AdminSecurityQuestionController::class, 'edit'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.edit');
                Router::get('/sec-question/get/{id}', [AdminSecurityQuestionController::class, 'get'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.get');
                Router::delete('/sec-question/remove/{id}', [AdminSecurityQuestionController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.sec_question.remove');

                /**
                 * contact us route
                 */
                Router::delete('/contact-us/remove/{id}', [AdminContactUsController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.contact-us.remove');

                /**
                 * complaint route
                 */
                Router::delete('/complaints/remove/{id}', [AdminComplaintsController::class, 'remove'])->where([
                    'id' => '[0-9]+',
                ])->name('ajax.complaints.remove');

                /**
                 * File Manager Route
                 */
                Router::get('/file-manager/list', [AdminFileController::class, 'list'])->name('api.file-manager.list');
                Router::post('/file-manager/rename', [AdminFileController::class, 'rename'])->name('api.file-manager.rename');
                Router::post('/file-manager/delete', [AdminFileController::class, 'delete'])->name('api.file-manager.delete');
                Router::post('/file-manager/delete-all', [AdminFileController::class, 'deleteAll'])->name('api.file-manager.delete.all');
                Router::post('/file-manager/mkdir', [AdminFileController::class, 'makeDir'])->name('api.file-manager.mkdir');
                Router::post('/file-manager/mvdir', [AdminFileController::class, 'moveDir'])->name('api.file-manager.mvdir');
                Router::post('/file-manager/upload', [AdminFileController::class, 'upload'])->name('api.file-manager.upload');
                Router::get('/file-manager/download/{filename}', [AdminFileController::class, 'download'])->where([
                    'filename' => '.+',
                ])->name('api.file-manager.download');
                Router::get('/file-manager/dir-tree', [AdminFileController::class, 'foldersTree'])->name('api.file-manager.tree');
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
