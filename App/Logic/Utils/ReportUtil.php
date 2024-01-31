<?php

namespace App\Logic\Utils;

use App\Logic\Models\OrderModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UserModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Models\WalletModel;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Sim\File\Download;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use Sim\Utils\StringUtil;
use function Symfony\Component\String\b;

class ReportUtil
{
    /**
     * @param $where
     * @param $bindValues
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\File\Interfaces\IFileException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function exportUsersExcel($where, $bindValues)
    {
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        $spreadsheetArray = [
            0 => [
                '#',
                'نام کاربری',
                'نام و نام خانوادگی',
                'نقش های کاربر',
                'شماره شبا',
                'وضعیت کاربر',
                'وضعیت ورود',
                'وضعیت فعالیت',
                'فعال شده در تاریخ',
                'ثبت نام در تاریخ',
            ]
        ];
        //-----
        $total = 0;
        $k = 0;
        $limit = 100;
        $offset = 0;
        // make memory management better in this way by fetching chunk of users each time
        do {
            $items = $userModel->getUsers(['u.*'], $where, $bindValues, $limit, $offset * $limit);
            $total += count($items);
            foreach ($items as $item) {
                $spreadsheetArray[($k + 1)][] = $k + 1;
                $spreadsheetArray[($k + 1)][] = $item['username'];
                $spreadsheetArray[($k + 1)][] = $item['first_name'] . ' ' . $item['last_name'];
                // get user roles and put it to user object
                $roleNames = $userModel->getUserRoles($item['id'], 'r.show_to_user=:stu', [
                    'stu' => DB_YES,
                ], ['r.description']);
                $roleNames = implode(', ', array_column($roleNames, 'description'));
                $spreadsheetArray[($k + 1)][] = trim($roleNames);
                //
                $spreadsheetArray[($k + 1)][] = $item['shaba_number'];
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_activated']) ? 'فعال' : 'غیر فعال';
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_hidden']) ? 'باز' : 'بسته';
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_login_locked']) ? 'اجازه دارد' : 'منع فعالیت';
                $spreadsheetArray[($k + 1)][] = !empty($item['activated_at'])
                    ? Jdf::jdate('j F Y در ساعت H:i', $item['activated_at'])
                    : '-';
                $spreadsheetArray[($k + 1)][] = Jdf::jdate(REPORT_TIME_FORMAT, $item['created_at']);
                //
                $k++;
            }
            $offset++;
        } while (count($items));

        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = 'تعداد کل کاربران';
        $spreadsheetArray[$total + 1][] = local_number(number_format(StringUtil::toEnglish($total)));
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';

        self::exportExcel(
            $spreadsheetArray,
            'report-users',
            'گزارش کاربران ' . Jdf::jdate(REPORT_TIME_FORMAT, time())
        );
    }

    /**
     * @param $where
     * @param $bindValues
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\File\Interfaces\IFileException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function exportProductsExcel($where, $bindValues)
    {
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        $spreadsheetArray = [
            0 => [
                '#',
                'عنوان',
                'دسته‌بندی',
                'برند',
                'عنوان لاتین برند',
                'عنوان واحد',
                'وضعیت موجودی محصول',
                'وضعیت نمایش',
                'محصول ویژه',
                //
                'وضعیت موجودی',
                'قیمت',
                'قیمت با تخفیف',
                'تخفیف تا تاریخ',
                'رنگ',
                'سایز',
                'گارانتی',
                'وزن (بر حسب گرم)',
                'تعداد موجود',
                //
                'کلمات کلیدی',
                'اضافه شده در تاریخ',
            ]
        ];
        //-----
        $total = 0;
        $k = 0;
        $limit = 100;
        $offset = 0;
        // make memory management better in this way by fetching chunk of users each time
        do {
            $items = $productModel->getLimitedProduct(
                $where,
                $bindValues,
                ['pa.product_availability DESC', 'pa.is_available DESC', 'pa.stock_count DESC', 'pa.product_id DESC'],
                $limit,
                $offset * $limit,
                ['pa.product_id'],
                [
                    'pa.product_id',
                    'pa.title',
                    'pa.category_name',
                    'pa.brand_name',
                    'pa.brand_latin_name',
                    'pa.publish',
                    'pa.product_availability',
                    'pa.is_special',
                    'pa.unit_title',
                    'pa.keywords',
                    'pa.created_at',
                ]
            );
            $total += count($items);
            foreach ($items as $item) {
                $productProperties = $productModel->getProductProperty($item['product_id'], [
                    'stock_count',
                    'color_name',
                    'size',
                    'guarantee',
                    'weight',
                    'price',
                    'discounted_price',
                    'discount_from',
                    'discount_until',
                    'is_available',
                ]);

                $c = 1;
                $avlStr = '';
                $priceStr = '';
                $disPriceStr = '';
                $disDateStr = '';
                $colorStr = '';
                $sizeStr = '';
                $guaranteeStr = '';
                $weightStr = '';
                $stockCountStr = '';
                if (!count($productProperties)) {
                    $avlStr = '-';
                    $priceStr = '-';
                    $disPriceStr = '-';
                    $disDateStr = '-';
                    $colorStr = '-';
                    $sizeStr = '-';
                    $guaranteeStr = '-';
                    $weightStr = '-';
                    $stockCountStr = '-';
                }
                foreach ($productProperties as $product) {
                    $sizeStr = "$c) -";
                    $guaranteeStr = "$c) -";
                    //
                    $avlStr .= "$c) " . is_value_checked($product['is_available']) ? 'موجود' : 'ناموجود';
                    $priceStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['price'])));
                    $disPriceStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['discounted_price'])));
                    $disDateFromStr .= "$c) " . !empty($product['discount_from']) ? Jdf::jdate(DEFAULT_TIME_FORMAT) : '-';
                    $disDateStr .= "$c) " . !empty($product['discount_until']) ? Jdf::jdate(DEFAULT_TIME_FORMAT) : '-';
                    $colorStr .= "$c) {$product['color_name']}";
                    if (trim($product['size']) != '') {
                        $sizeStr .= "$c) {$product['size']}";
                        $sizeStr .= "\n";
                    }
                    if (trim($product['guarantee']) != '') {
                        $guaranteeStr .= "$c) {$product['guarantee']}";
                        $guaranteeStr .= "\n";
                    }
                    $weightStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['weight']))) . ' گرم';
                    $stockCountStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['stock_count'])));
                    //
                    $avlStr .= "\n";
                    $priceStr .= "\n";
                    $disPriceStr .= "\n";
                    $disDateFromStr .= "\n";
                    $disDateStr .= "\n";
                    $colorStr .= "\n";
                    $weightStr .= "\n";
                    $stockCountStr .= "\n";
                    //
                    $c++;
                }

                $spreadsheetArray[($k + 1)][] = $k + 1;
                $spreadsheetArray[($k + 1)][] = $item['title'];
                $spreadsheetArray[($k + 1)][] = $item['category_name'];
                $spreadsheetArray[($k + 1)][] = $item['brand_name'];
                $spreadsheetArray[($k + 1)][] = $item['brand_latin_name'];
                $spreadsheetArray[($k + 1)][] = $item['unit_title'];
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['product_availability']) ? 'موجود' : 'ناموجود';
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['publish']) ? 'نمایش' : 'عدم نمایش';
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_special']) ? 'ویژه' : '-';
                // product properties
                $spreadsheetArray[($k + 1)][] = $avlStr;
                $spreadsheetArray[($k + 1)][] = $priceStr;
                $spreadsheetArray[($k + 1)][] = $disPriceStr;
                $spreadsheetArray[($k + 1)][] = $disDateFromStr;
                $spreadsheetArray[($k + 1)][] = $disDateStr;
                $spreadsheetArray[($k + 1)][] = $colorStr;
                $spreadsheetArray[($k + 1)][] = $sizeStr;
                $spreadsheetArray[($k + 1)][] = $guaranteeStr;
                $spreadsheetArray[($k + 1)][] = $weightStr;
                $spreadsheetArray[($k + 1)][] = $stockCountStr;
                //
                $spreadsheetArray[($k + 1)][] = $item['keywords'];
                $spreadsheetArray[($k + 1)][] = Jdf::jdate(REPORT_TIME_FORMAT, $item['created_at']);
                //
                $k++;
            }
            $offset++;
        } while (count($items));

        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = 'تعداد کل';
        $spreadsheetArray[$total + 1][] = local_number(number_format(StringUtil::toEnglish($total)));
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';

        self::exportExcel(
            $spreadsheetArray,
            'report-products',
            'گزارش محصولات ' . Jdf::jdate(REPORT_TIME_FORMAT, time())
        );
    }

    /**
     * @param $where
     * @param $bindValues
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\File\Interfaces\IFileException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function exportOrdersExcel($where, $bindValues)
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        $spreadsheetArray = [
            0 => [
                '#',
                'کد سفارش',
                'نام خریدار',
                'موبایل خریدار',
                'نام گیرنده',
                'موبایل گیرنده',
                //
                'قیمت واحد',
                'قیمت',
                'قیمت با تخفیف',
                'رنگ',
                'سایز',
                'گارانتی',
                'وزن (بر حسب گرم)',
                'تعداد خریداری شده',
                //
                'شهر',
                'استان',
                'آدرس',
                'کد پستی',
                'وضعیت پرداخت',
                'شیوه پرداخت',
                'عنوان شیوه پرداخت',
                'کد کوپن',
                'عنوان کوپن',
                'قیمت کوپن',
                'قیمت کل',
                'مقدار تخفیف',
                'هزینه ارسال',
                'قیمت نهایی',
                'وضعیت ارسال',
                'تاریخ پرداخت',
                'تاریخ ثبت سفارش',
                'تاریخ تغییر وضعیت پرداخت',
                'تغییر وضعیت پرداخت توسط',
                'تاریخ تغییر وضعیت ارسال',
                'تغییر وضعیت ارسال توسط',
                'نام فعلی خریدار',
                'موبایل فعلی خریدار',
            ]
        ];
        //-----
        $total = 0;
        $k = 0;
        $limit = 100;
        $offset = 0;
        // make memory management better in this way by fetching chunk of users each time
        do {
            $items = $orderModel->getOrdersWithAllInfo(
                $where,
                $bindValues,
                ['o.ordered_at DESC'],
                $limit,
                $offset * $limit
            );
            $total += count($items);
            foreach ($items as $item) {
                $spreadsheetArray[($k + 1)][] = $k + 1;
                $spreadsheetArray[($k + 1)][] = $item['code'];
                $spreadsheetArray[($k + 1)][] = trim(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? ''));
                $spreadsheetArray[($k + 1)][] = $item['mobile'];
                $spreadsheetArray[($k + 1)][] = $item['receiver_name'];
                $spreadsheetArray[($k + 1)][] = $item['receiver_mobile'];
                //-----
                $orderItems = $orderModel->getOrderItems(['*'], 'order_code=:code', ['code' => $item['code']]);

                $c = 1;
                $unitPriceStr = '';
                $priceStr = '';
                $disPriceStr = '';
                $colorStr = '';
                $sizeStr = '';
                $guaranteeStr = '';
                $weightStr = '';
                $productCountStr = '';
                $isSeparateCStr = '';
                if (!count($orderItems)) {
                    $unitPriceStr = '-';
                    $priceStr = '-';
                    $disPriceStr = '-';
                    $colorStr = '-';
                    $sizeStr = '-';
                    $guaranteeStr = '-';
                    $weightStr = '-';
                    $productCountStr = '-';
                    $isSeparateCStr = '-';
                }
                foreach ($orderItems as $product) {
                    $unitPriceStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['unit_price'])));
                    $priceStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['price'])));
                    $disPriceStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['discounted_price'])));
                    $colorStr .= "$c) {$product['color_name']}";
                    $sizeStr .= "$c) {$product['size']}";
                    $guaranteeStr .= "$c) {$product['guarantee']}";
                    $weightStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['weight']))) . ' گرم';
                    $productCountStr .= "$c) " . local_number(number_format(StringUtil::toEnglish($product['product_count'])));
                    $isSeparateCStr .= "$c) " . (is_value_checked($product['separate_consignment']) ? 'مرسوله مجزا' : '-');
                    //
                    $priceStr .= "\n";
                    $disPriceStr .= "\n";
                    $colorStr .= "\n";
                    $sizeStr .= "\n";
                    $guaranteeStr .= "\n";
                    $weightStr .= "\n";
                    $productCountStr .= "\n";
                    $isSeparateCStr .= "\n";
                    //
                    $c++;
                }
                //-----

                // order items info
                $spreadsheetArray[($k + 1)][] = $unitPriceStr;
                $spreadsheetArray[($k + 1)][] = $priceStr;
                $spreadsheetArray[($k + 1)][] = $disPriceStr;
                $spreadsheetArray[($k + 1)][] = $colorStr;
                $spreadsheetArray[($k + 1)][] = $sizeStr;
                $spreadsheetArray[($k + 1)][] = $guaranteeStr;
                $spreadsheetArray[($k + 1)][] = $weightStr;
                $spreadsheetArray[($k + 1)][] = $productCountStr;
                $spreadsheetArray[($k + 1)][] = $isSeparateCStr;
                //-----
                $spreadsheetArray[($k + 1)][] = $item['city'];
                $spreadsheetArray[($k + 1)][] = $item['province'];
                $spreadsheetArray[($k + 1)][] = $item['address'];
                $spreadsheetArray[($k + 1)][] = $item['postal_code'];
                $spreadsheetArray[($k + 1)][] = PAYMENT_STATUSES[$item['payment_status']] ?? 'نامشخص';
                $spreadsheetArray[($k + 1)][] = METHOD_TYPES_ALL[$item['method_type']] ?? 'نامشخص';
                $spreadsheetArray[($k + 1)][] = $item['method_title'];
                $spreadsheetArray[($k + 1)][] = $item['coupon_code'] ?? '-';
                $spreadsheetArray[($k + 1)][] = $item['coupon_title'] ?? '-';
                $spreadsheetArray[($k + 1)][] = number_format(StringUtil::toEnglish($item['coupon_price']));
                $spreadsheetArray[($k + 1)][] = number_format(StringUtil::toEnglish($item['total_price']));
                $spreadsheetArray[($k + 1)][] = number_format(StringUtil::toEnglish($item['discount_price']));
                $spreadsheetArray[($k + 1)][] = number_format(StringUtil::toEnglish($item['shipping_price']));
                $spreadsheetArray[($k + 1)][] = number_format(StringUtil::toEnglish($item['final_price']));
                $spreadsheetArray[($k + 1)][] = StringUtil::toEnglish($item['send_status_title']);
                $spreadsheetArray[($k + 1)][] = !empty($item['payed_at'])
                    ? Jdf::jdate(REPORT_TIME_FORMAT, $item['payed_at'])
                    : '-';
                $spreadsheetArray[($k + 1)][] = Jdf::jdate(REPORT_TIME_FORMAT, $item['ordered_at']);
                $spreadsheetArray[($k + 1)][] = !empty($item['invoice_status_changed_at'])
                    ? Jdf::jdate(REPORT_TIME_FORMAT, $item['invoice_status_changed_at'])
                    : '-';
                $spreadsheetArray[($k + 1)][] = !empty($item['invoice_status_changed_by'])
                    ? ((trim($item['invoice_user_first_name'] . ' ' . $item['invoice_user_last_name']) ?: $item['invoice_username']) ?? '-')
                    : 'سیستمی';
                $spreadsheetArray[($k + 1)][] = !empty($item['send_status_changed_at'])
                    ? Jdf::jdate(REPORT_TIME_FORMAT, $item['send_status_changed_at'])
                    : '-';
                $spreadsheetArray[($k + 1)][] = !empty($item['send_status_changed_by'])
                    ? ((trim($item['sender_user_first_name'] . ' ' . $item['sender_user_last_name']) ?: $item['sender_username']) ?? '-')
                    : 'سیستمی';
                $spreadsheetArray[($k + 1)][] = trim($item['user_first_name'] . ' ' . $item['user_last_name']);
                $spreadsheetArray[($k + 1)][] = $item['username'];

                $k++;
            }
            $offset++;
        } while (count($items));

        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = 'تعداد کل سفارشات';
        $spreadsheetArray[$total + 1][] = local_number(number_format(StringUtil::toEnglish($total)));
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';

        self::exportExcel(
            $spreadsheetArray,
            'report-orders',
            'گزارش سفارشات ' . Jdf::jdate(REPORT_TIME_FORMAT, time())
        );
    }

    /**
     * @param $where
     * @param $bindValues
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\File\Interfaces\IFileException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function exportWalletExcel($where, $bindValues)
    {
        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);
        $spreadsheetArray = [
            0 => [
                '#',
                'نام گاربری',
                'نام و نام خانوادگی',
                'مبلغ کیف پول',
                'وضعیت دسترسی',
            ]
        ];
        //-----
        $total = 0;
        $k = 0;
        $limit = 100;
        $offset = 0;
        // make memory management better in this way by fetching chunk of users each time
        do {
            $items = $walletModel->getWalletInfo(
                $where,
                $bindValues,
                ['w.id DESC'],
                $limit,
                $offset * $limit,
                [
                    'w.*',
                    'u.first_name AS user_first_name',
                    'u.last_name AS user_last_name'
                ]
            );
            $total += count($items);
            foreach ($items as $item) {
                $spreadsheetArray[($k + 1)][] = $k + 1;
                $spreadsheetArray[($k + 1)][] = $item['username'];
                $spreadsheetArray[($k + 1)][] = trim(($item['user_first_name'] ?? '') . ' ' . ($item['user_last_name'] ?? '')) ?? '-';
                $spreadsheetArray[($k + 1)][] = number_format(StringUtil::toEnglish($item['balance']));
                $spreadsheetArray[($k + 1)][] = is_value_checked($item['is_available']) ? 'فعال' : 'غیر فعال';

                $k++;
            }
            $offset++;
        } while (count($items));

        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = 'تعداد کل اطلاعات کیف پول';
        $spreadsheetArray[$total + 1][] = local_number(number_format(StringUtil::toEnglish($total)));
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';

        self::exportExcel(
            $spreadsheetArray,
            'report-wallet',
            'گزارش کیف پول ' . Jdf::jdate(REPORT_TIME_FORMAT, time())
        );
    }

    /**
     * @param $where
     * @param $bindValues
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\File\Interfaces\IFileException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function exportWalletDepositExcel($where, $bindValues)
    {
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        $spreadsheetArray = [
            0 => [
                '#',
                'نام کاربری',
                'نام و نام خانوادگی',
                'مبلغ تراکنش',
                'علت تراکنش',
                'تراکنش توسط',
                'تاریخ تراکنش',
            ]
        ];
        //-----
        $total = 0;
        $k = 0;
        $limit = 100;
        $offset = 0;
        // make memory management better in this way by fetching chunk of users each time
        do {
            $items = $walletFlowModel->getWalletFlowInfo(
                $where,
                $bindValues,
                ['wf.id DESC'],
                $limit,
                $offset * $limit,
                [
                    'wf.username',
                    'mu.first_name',
                    'mu.last_name',
                    'u.first_name AS payer_first_name',
                    'u.last_name AS payer_last_name',
                    'wf.deposit_type_title',
                    'wf.deposit_price',
                    'wf.deposit_at',
                ]
            );
            $total += count($items);
            foreach ($items as $item) {
                $spreadsheetArray[($k + 1)][] = $k + 1;
                $spreadsheetArray[($k + 1)][] = $item['username'];
                $spreadsheetArray[($k + 1)][] = trim(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? '')) ?? '-';
                $spreadsheetArray[($k + 1)][] = number_format(StringUtil::toEnglish($item['deposit_price']));
                $spreadsheetArray[($k + 1)][] = trim($item['deposit_type_title'] ?? '-');
                $spreadsheetArray[($k + 1)][] = trim(($item['payer_first_name'] ?? '') . ' ' . ($item['payer_last_name'] ?? '')) ?? '-';
                $spreadsheetArray[($k + 1)][] = Jdf::jdate(REPORT_TIME_FORMAT, $item['deposit_at']);

                $k++;
            }
            $offset++;
        } while (count($items));

        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = 'تعداد کل تراکنش‌های کیف پول';
        $spreadsheetArray[$total + 1][] = local_number(number_format(StringUtil::toEnglish($total)));
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';
        $spreadsheetArray[$total + 1][] = '';

        self::exportExcel(
            $spreadsheetArray,
            'report-wallet-deposit',
            'گزارش تراکنش‌های کیف پول ' . Jdf::jdate(REPORT_TIME_FORMAT, time())
        );
    }

    /**
     * @param $filename
     * @param $html
     * @param string|null $watermark
     * @throws \Mpdf\MpdfException
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    public static function exportPdf($filename, $html, ?string $watermark = null)
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [292.1, 215.9], // 11.5x8.5 - inches
            'fontDir' => array_merge($fontDirs, [
                path()->get('fonts'),
            ]),
            'fontdata' => $fontData + [
                    'irs' => [
                        'R' => 'IRANSansWeb.ttf',
                        'B' => 'IRANSansWeb_Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ],
            'default_font' => 'irs',
        ]);
        $mpdf->SetFont('IRS');
        $mpdf->SetDirectionality('rtl');
        $mpdf->CSSselectMedia = 'mpdf';

        if (!empty(trim((string)$watermark))) {
            $mpdf->SetWatermarkText($watermark);
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.1;
        }

        $mpdf->WriteHTML($html);

        $mpdf->Output($filename . '.pdf', Destination::DOWNLOAD);
    }

    /**
     * @param $array
     * @param $filename
     * @param $downloadFilename
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Sim\Exceptions\FileNotExistsException
     * @throws \Sim\Exceptions\PathManager\PathNotRegisteredException
     * @throws \Sim\File\Interfaces\IFileException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    private static function exportExcel(
        $array,
        $filename,
        $downloadFilename
    )
    {
        // Spreadsheet name
        $name = md5($filename . StringUtil::randomString(3, StringUtil::RS_NUMBER));
        $reportPath = path()->get('upload-report');
        // remove previous excel files
        ReportUtil::removeAllExcelReports($reportPath, $filename . '*');
        // Create IO for file
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Add whole array to spreadsheet
        $spreadsheet
            ->getActiveSheet()
            ->setRightToLeft(true)
            ->fromArray($array);
        // Create writer
        $writer = new WriterXlsx($spreadsheet);
        $writer->save($reportPath . $name . ".xlsx");
        //
        ReportUtil::downloadExport($reportPath . $name . '.xlsx', $downloadFilename);
    }

    /**
     * @param $path
     * @param string|null $rule
     */
    private static function removeAllExcelReports($path, ?string $rule = null)
    {
        if (!is_null($rule)) {
            $mask = rtrim($path, '\\/') . '/' . $rule . '.xlsx';
            array_map('unlink', glob($mask));
        }
        $mask = $path . '*.xlsx';
        array_map('unlink', glob($mask));
    }

    /**
     * @param $path
     * @param $name
     * @throws \Sim\File\Interfaces\IFileException
     */
    private static function downloadExport($path, $name)
    {
        $download = Download::makeDownloadFromPath($path);
        $download->download($name);
    }
}
