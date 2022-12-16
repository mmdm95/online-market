<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\ProductModel;
use App\Logic\Utils\CartUtil;
use App\Logic\Utils\CouponUtil;
use App\Logic\Utils\LogUtil;
use Jenssegers\Agent\Agent;
use Sim\Cart\Interfaces\IDBException as ICartDBException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class CartController extends AbstractHomeController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function index()
    {
        $this->setLayout($this->main_layout)->setTemplate('view/main/order/shop-cart');
        return $this->render();
    }

    /**
     * @throws IFileNotExistsException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function cartTopInfo()
    {
        $resourceHandler = new ResourceHandler();
        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var CartUtil $cartUtil
             */
            $cartUtil = \container()->get(CartUtil::class);

            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data($cartUtil->getCartSection());
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $product_code
     * @throws ICartDBException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function addToCart($product_code)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var ProductModel $productModel
             */
            $productModel = container()->get(ProductModel::class);
            $extraInfo = $productModel->getLimitedProduct(
                'pa.code=:code AND pa.is_deleted!=:del AND pa.publish=:pub AND pa.is_available=:avl ' .
                'AND pa.product_availability=:pAvl AND pa.stock_count>:sc AND pa.max_cart_count>:mcc', [
                'code' => $product_code,
                'del' => DB_YES,
                'pub' => DB_YES,
                'avl' => DB_YES,
                'pAvl' => DB_YES,
                'sc' => 0,
                'mcc' => 0
            ], [], 1, 0, [], [
                    'pa.image', 'pa.brand_fa_name', 'pa.brand_id', 'pa.festival_discount', 'pa.festival_expire',
                    'pa.festival_publish', 'pa.festival_start', 'pa.category_name', 'pa.title',
                    'pa.slug', 'pa.category_id', 'pa.unit_title', 'pa.unit_sign', 'pa.is_returnable',
                    'pa.stock_count', 'pa.max_cart_count', 'pa.color_hex', 'pa.color_name', 'pa.size',
                    'pa.guarantee', 'pa.weight', 'pa.show_coming_soon', 'pa.call_for_more',
                ]
            );

            if (count($extraInfo)) {
                $extraInfo = $extraInfo[0];

                if (DB_YES == $extraInfo['show_coming_soon']) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_INFO)
                        ->data('محصول بزودی به سایت اضافه خواهد شد.');
                } elseif (DB_YES == $extraInfo['call_for_more']) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_WARNING)
                        ->data('برای اطلاعات بیشتر با ما تماس بگیرید.');
                } else {
                    // apply stepped price
                    $stepped = get_stepped_price(1, $product_code);
                    if (!is_null($stepped)) {
                        $extraInfo['stepped_price'] = $stepped['price'];
                        $extraInfo['stepped_discounted_price'] = $stepped['discounted_price'];
                    }
                    //
                    cart()->add($product_code, $extraInfo)->store();
                    $resourceHandler
                        ->type(RESPONSE_TYPE_SUCCESS)
                        ->data('محصول به سبد اضافه شد.');
                    CouponUtil::checkCoupon(CouponUtil::getStoredCouponCode());
                }
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('محصول مورد نظر موجود نمی‌باشد!');
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
     * @param $product_code
     * @throws ICartDBException
     */
    public function updateCart($product_code)
    {
        $resourceHandler = new ResourceHandler();

        try {
            /**
             * @var Agent $agent
             */
            $agent = container()->get(Agent::class);
            if (!$agent->isRobot()) {
                /**
                 * @var ProductModel $productModel
                 */
                $productModel = container()->get(ProductModel::class);
                $qnt = (int)input()->post('qnt')->getValue();
                $extraInfo = $productModel->getLimitedProduct(
                    'pa.code=:code AND pa.is_deleted!=:del AND pa.publish=:pub AND pa.is_available=:avl ' .
                    'AND pa.product_availability=:pAvl AND pa.stock_count>:sc AND pa.max_cart_count>:mcc', [
                    'code' => $product_code,
                    'del' => DB_YES,
                    'pub' => DB_YES,
                    'avl' => DB_YES,
                    'pAvl' => DB_YES,
                    'sc' => 0,
                    'mcc' => 0
                ], [], 1, 0, [], [
                        'pa.image', 'pa.brand_fa_name', 'pa.brand_id', 'pa.festival_discount', 'pa.festival_expire',
                        'pa.festival_publish', 'pa.festival_start', 'pa.category_name', 'pa.title',
                        'pa.slug', 'pa.category_id', 'pa.unit_title', 'pa.unit_sign', 'pa.is_returnable',
                        'pa.stock_count', 'pa.max_cart_count', 'pa.color_hex', 'pa.color_name', 'pa.size',
                        'pa.guarantee', 'pa.weight', 'pa.show_coming_soon', 'pa.call_for_more',
                    ]
                );
                if (count($extraInfo)) {
                    $extraInfo = $extraInfo[0];

                    if (DB_YES == $extraInfo['show_coming_soon']) {
                        $resourceHandler
                            ->type(RESPONSE_TYPE_INFO)
                            ->data('محصول بزودی به سایت اضافه خواهد شد.');
                    } elseif (DB_YES == $extraInfo['call_for_more']) {
                        $resourceHandler
                            ->type(RESPONSE_TYPE_WARNING)
                            ->data('برای اطلاعات بیشتر با ما تماس بگیرید.');
                    } else {
                        if ($qnt > 0) {
                            // apply stepped price
                            $stepped = get_stepped_price($qnt, $product_code);
                            if (!is_null($stepped)) {
                                $extraInfo['stepped_price'] = $stepped['price'];
                                $extraInfo['stepped_discounted_price'] = $stepped['discounted_price'];
                            }
                            //
                            cart()->update($product_code, array_merge($extraInfo, [
                                'qnt' => $qnt,
                            ]))->store();
                            $resourceHandler
                                ->type(RESPONSE_TYPE_SUCCESS)
                                ->data('تعداد محصول در سبد، بروزرسانی شد.');
                            CouponUtil::checkCoupon(CouponUtil::getStoredCouponCode());
                        } else {
                            $resourceHandler
                                ->type(RESPONSE_TYPE_ERROR)
                                ->errorMessage('تعداد وارد شده نامعتبر است.');
                        }
                    }
                } else {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('محصول مورد نظر موجود نمی‌باشد!');
                }
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
     * @param $product_code
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function removeFromCart($product_code)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            if (cart()->remove($product_code)) {
                cart()->store();
                $resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->data('محصول از سبد خرید حذف شد.');
                CouponUtil::checkCoupon(CouponUtil::getStoredCouponCode());

                if (!count(cart()->getItems())) {
                    $resourceHandler->statusCode(301);
                }
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('خظا در حذف محصول از سبد خرید!');
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
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function getCartProducts()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data($this->setTemplate('partial/main/ajax/cart-items')->render());
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
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
    public function getCartProductsInfo()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $couponCode = CouponUtil::getStoredCouponCode();
            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data($this->setTemplate('partial/main/ajax/cart-items-info')->render([
                    'couponCode' => $couponCode,
                ]));
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
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
    public function getCartProductsTotalInfo()
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $couponCode = CouponUtil::getStoredCouponCode();
            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data($this->setTemplate('partial/main/ajax/cart-items-total-info')->render([
                    'couponCode' => $couponCode,
                ]));
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $coupon_code
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function checkCoupon($coupon_code)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $res = CouponUtil::checkCoupon($coupon_code);
            if ($res[0]) {
                $resourceHandler
                    ->type(RESPONSE_TYPE_SUCCESS)
                    ->errorMessage($res[1]);
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage($res[1]);
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function checkStoredCoupon()
    {
        $this->checkCoupon(CouponUtil::getStoredCouponCode());
    }
}
