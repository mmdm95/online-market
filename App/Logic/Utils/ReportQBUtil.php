<?php

namespace App\Logic\Utils;

use App\Logic\Models\BrandModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\ProductModel;

class ReportQBUtil
{
    /*-----------------------------------------------
    |------ Use this as query builder template ------
        [
            'id' => '',
            'label' => '',
            'type' => '',
            'input' => '',
            'values' => [
            ],
            'operators' => [
            ],
        ],
    -------------------------------------------------
    -----------------------------------------------*/

    /**
     * @var array
     */
    private static $stringOperation = [
        'equal',
        'not_equal',
        'begins_with',
        'not_begins_with',
        'contains',
        'not_contains',
        'ends_with',
        'not_ends_with',
        'is_empty',
        'is_not_empty',
        'is_null',
        'is_not_null',
    ];

    /**
     * @var array
     */
    private static $boolOperation = [
        'equal',
        'not_equal',
    ];

    /**
     * @var array
     */
    private static $multiselectOperation = [
        'equal',
        'not_equal',
        'contains',
        'not_contains',
        'begins_with',
        'not_begins_with',
        'ends_with',
        'not_ends_with',
        'is_empty',
        'is_not_empty',
        'is_null',
        'is_not_null',
    ];

    /**
     * @var array
     */
    private static $integerSemiPriceOperation = [
        'less',
        'less_or_equal',
        'greater',
        'greater_or_equal',
        'between',
        'not_between',
    ];

    /**
     * @var array
     */
    private static $dateOperation = [
        'less',
        'less_or_equal',
        'greater',
        'greater_or_equal',
        'between',
        'not_between',
        'is_empty',
        'is_not_empty',
        'is_null',
        'is_not_null',
    ];

    /**
     * @var array
     */
    private static $datePickerConfig = [
        "inline" => false,
        "format" => "L",
        "viewMode" => "day",
        "initialValue" => true,
        "minDate" => 0,
        "maxDate" => 0,
        "autoClose" => false,
        "position" => "auto",
        "onlyTimePicker" => false,
        "onlySelectOnDate" => false,
        "calendarType" => "persian",
        "altFormat" => "X",
        "altField" => "",
        "inputDelay" => 800,
        "observer" => true,
        "calendar" => [
            "persian" => [
                "locale" => "fa",
                "showHint" => true,
                "leapYearMode" => "algorithmic"
            ],
            "gregorian" => [
                "locale" => "en",
                "showHint" => true
            ]
        ],
        "navigator" => [
            "enabled" => true,
            "scroll" => [
                "enabled" => true
            ],
            "text" => [
                "btnNextText" => "<",
                "btnPrevText" => ">"
            ]
        ],
        "toolbox" => [
            "enabled" => true,
            "calendarSwitch" => [
                "enabled" => true,
                "format" => "MMMM"
            ],
            "todayButton" => [
                "enabled" => true,
                "text" => [
                    "fa" => "امروز",
                    "en" => "Today"
                ]
            ],
            "submitButton" => [
                "enabled" => true,
                "text" => [
                    "fa" => "تایید",
                    "en" => "Submit"
                ]
            ],
            "text" => [
                "btnToday" => "امروز"
            ]
        ],
        "timePicker" => [
            "enabled" => false,
        ],
        "dayPicker" => [
            "enabled" => true,
            "titleFormat" => "YYYY MMMM"
        ],
        "monthPicker" => [
            "enabled" => true,
            "titleFormat" => "YYYY"
        ],
        "yearPicker" => [
            "enabled" => true,
            "titleFormat" => "YYYY"
        ],
        "responsive" => true
    ];

    /**
     * [u] and [r] are aliases that used in [ReportUserController]
     * in fetching datatable data
     *
     * @return array
     */
    public static function getUserQB(): array
    {
        return [
            [
                'id' => 'u.username',
                'label' => 'نام کاربری',
                'type' => 'integer',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'u.first_name',
                'label' => 'نام',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'u.last_name',
                'label' => 'نام خانوادگی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'r.name',
                'label' => 'نقش',
                'type' => 'string',
                'input' => 'select',
                'operators' => self::$boolOperation,
                'values' => ROLES_ARRAY_ACCEPTABLE,
            ],
            [
                'id' => 'u.is_activated',
                'label' => 'وضعیت فعال بودن',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'غیر فعال',
                    1 => 'فعال',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'u.is_login_locked',
                'label' => 'عملیات ورود فقل شده',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'خیر',
                    1 => 'بله',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'u.ban',
                'label' => 'بن شده',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'خیر',
                    1 => 'بله',
                ],
                'operators' => self::$boolOperation,
            ],
        ];
    }

    /**
     * [pa] is alias that used in [ReportProductController]
     * in fetching datatable data
     *
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getProductQB(): array
    {
        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);

        $products = $productModel->getLimitedProduct(
            null,
            [],
            ['pa.stock_count DESC', 'pa.product_availability DESC', 'pa.is_available DESC', 'pa.product_id DESC'],
            null,
            0,
            ['pa.product_id'],
            ['pa.product_id']
        );
        $newProducts = array_column($products, 'product_id');
        //-----
        $categories = $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]);
        $newCategories = array_column($categories, 'name', 'id');
        //-----
        $brands = $brandModel->get(['id', 'name']);
        $newBrands = array_column($brands, 'name', 'id');
        //-----

        return [
            [
                'id' => 'pa.title',
                'label' => 'عنوان',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.product_id',
                'label' => 'شناسه محصول',
                'type' => 'integer',
                'input' => 'select',
                'values' => $newProducts,
                'operators' => self::$multiselectOperation,
                'multiple' => true,
            ],
            [
                'id' => 'pa.color_name',
                'label' => 'رنگ محصول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.size',
                'label' => 'سایز محصول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.guarantee',
                'label' => 'گارانتی محصول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.price',
                'label' => 'قیمت محصول',
                'type' => 'integer',
                'operators' => self::$integerSemiPriceOperation,
            ],
            [
                'id' => 'pa.discounted_price',
                'label' => 'قیمت محصول با تخفیف',
                'type' => 'integer',
                'operators' => self::$integerSemiPriceOperation,
            ],
            [
                'id' => 'pa.category_name',
                'label' => 'نام دسته‌بندی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.category_id',
                'label' => 'دسته‌بندی',
                'type' => 'integer',
                'input' => 'select',
                'values' => $newCategories,
                'operators' => self::$multiselectOperation,
                'multiple' => true,
            ],
            [
                'id' => 'pa.category_parent_id',
                'label' => 'دسته‌بندی والد',
                'type' => 'integer',
                'input' => 'select',
                'values' => $newCategories,
                'operators' => self::$multiselectOperation,
                'multiple' => true,
            ],
            [
                'id' => 'pa.brand_name',
                'label' => 'نام برند',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.brand_latin_name',
                'label' => 'نام لاتین برند',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.brand_id',
                'label' => 'دسته‌بندی',
                'type' => 'integer',
                'input' => 'select',
                'values' => $newBrands,
                'operators' => self::$multiselectOperation,
                'multiple' => true,
            ],
            [
                'id' => 'pa.stock_count',
                'label' => 'تعداد در انبار',
                'type' => 'integer',
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'pa.is_special',
                'label' => 'محصول ویژه',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'خیر',
                    1 => 'بله',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'pa.product_availability',
                'label' => 'وضعیت موجودی',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'ناموجود',
                    1 => 'موجود',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'pa.publish',
                'label' => 'وضعیت نمایش',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'عدم نمایش',
                    1 => 'نمایش',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'pa.keywords',
                'label' => 'کلمات کلیدی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.unit_title',
                'label' => 'عنوان واحد',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'pa.created_at',
                'label' => 'تاریخ افزودن',
                'type' => 'date',
                'operators' => self::$dateOperation,
                'validation' => [
                    'format' => 'X'
                ],
                'plugin' => 'persianDatepicker',
                'plugin_config' => self::$datePickerConfig,
            ],
        ];
    }

    /**
     * [oa] is alias that used in [ReportOrderController]
     * in fetching datatable data
     *
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getOrderQB(): array
    {
        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);

        $categories = $categoryModel->get(['id', 'name'], 'publish=:pub', ['pub' => DB_YES]);
        $newCategories = array_column($categories, 'name', 'id');
        //-----
        $brands = $brandModel->get(['id', 'name']);
        $newBrands = array_column($brands, 'name', 'id');

        return [
            [
                'id' => 'oa.title',
                'label' => 'عنوان',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.color_name',
                'label' => 'رنگ محصول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.size',
                'label' => 'سایز محصول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.guarantee',
                'label' => 'گارانتی محصول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.price',
                'label' => 'قیمت محصول',
                'type' => 'integer',
                'operators' => self::$integerSemiPriceOperation,
            ],
            [
                'id' => 'oa.discounted_price',
                'label' => 'قیمت محصول با تخفیف',
                'type' => 'integer',
                'operators' => self::$integerSemiPriceOperation,
            ],
            [
                'id' => 'oa.category_name',
                'label' => 'نام دسته‌بندی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.category_id',
                'label' => 'دسته‌بندی',
                'type' => 'integer',
                'input' => 'select',
                'values' => $newCategories,
                'operators' => self::$multiselectOperation,
                'multiple' => true,
            ],
            [
                'id' => 'oa.category_parent_id',
                'label' => 'دسته‌بندی والد',
                'type' => 'integer',
                'input' => 'select',
                'values' => $newCategories,
                'operators' => self::$multiselectOperation,
                'multiple' => true,
            ],
            [
                'id' => 'oa.brand_name',
                'label' => 'نام برند',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.brand_latin_name',
                'label' => 'نام لاتین برند',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.brand_id',
                'label' => 'دسته‌بندی',
                'type' => 'integer',
                'input' => 'select',
                'values' => $newBrands,
                'operators' => self::$multiselectOperation,
                'multiple' => true,
            ],
            [
                'id' => 'oa.stock_count',
                'label' => 'تعداد در انبار',
                'type' => 'integer',
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'oa.is_special',
                'label' => 'محصول ویژه',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'خیر',
                    1 => 'بله',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'oa.product_availability',
                'label' => 'وضعیت موجودی',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'ناموجود',
                    1 => 'موجود',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'oa.publish',
                'label' => 'وضعیت نمایش',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'عدم نمایش',
                    1 => 'نمایش',
                ],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'oa.keywords',
                'label' => 'کلمات کلیدی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.unit_title',
                'label' => 'عنوان واحد',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.receiver_name',
                'label' => 'نام گیرنده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.receiver_mobile',
                'label' => 'موبایل گیرنده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.first_name',
                'label' => 'نام ثبت کننده سفارش',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.last_name',
                'label' => 'نام خانوادگی ثبت کننده سفارش',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.mobile',
                'label' => 'موبایل ثبت کننده سفارش',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.city',
                'label' => 'شهر گیرنده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.province',
                'label' => 'استان گیرنده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.address',
                'label' => 'آدرس گیرنده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.postal_code',
                'label' => 'کدپستی گیرنده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.method_type',
                'label' => 'نوع پرداخت',
                'type' => 'integer',
                'input' => 'select',
                'values' => METHOD_TYPES + [METHOD_TYPE_WALLET => 'کیف پول'],
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'oa.payment_status',
                'label' => 'وضعیت پرداخت',
                'type' => 'integer',
                'input' => 'select',
                'values' => PAYMENT_STATUSES,
                'operators' => self::$boolOperation,
            ],
            [
                'id' => 'oa.total_price',
                'label' => 'جمع مبالغ',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.discount_price',
                'label' => 'مقدار تخفیف کل',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.shipping_price',
                'label' => 'هزینه ارسال',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.final_price',
                'label' => 'قیمت نهایی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.send_status_title',
                'label' => 'عنوان وضیعیت ارسال',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.payed_at',
                'label' => 'تاریخ پرداخت',
                'type' => 'date',
                'operators' => self::$dateOperation,
                'validation' => [
                    'format' => 'X'
                ],
                'plugin' => 'persianDatepicker',
                'plugin_config' => self::$datePickerConfig,
            ],
            [
                'id' => 'oa.ordered_at',
                'label' => 'تاریخ سفارش',
                'type' => 'date',
                'operators' => self::$dateOperation,
                'validation' => [
                    'format' => 'X'
                ],
                'plugin' => 'persianDatepicker',
                'plugin_config' => self::$datePickerConfig,
            ],
            [
                'id' => 'oa.order_item_product_title',
                'label' => 'عنوان محصول خریداری شده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.order_item_color_name',
                'label' => 'رنگ محصول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.order_item_guarantee',
                'label' => 'گارانتی محصول خریداری شده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.order_item_discounted_price',
                'label' => 'قیمت تخفیف محصول خریداری شده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.order_item_total_price',
                'label' => 'قیمت کل محصول خریداری شده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.order_item_product_count',
                'label' => 'تعداد کل محصول خریداری شده',
                'type' => 'integer',
                'operators' => self::$integerSemiPriceOperation,
            ],
            [
                'id' => 'oa.order_item_size',
                'label' => 'سایز محصول خریداری شده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.order_item_unit_price',
                'label' => 'قیمت واحد محصول خریداری شده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'oa.order_item_unit_title',
                'label' => 'عنوان واحد محصول خریداری شده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
        ];
    }

    /**
     * [] are aliases that used in [ReportWalletController]
     * in fetching datatable data
     *
     * @return array
     */
    public static function getWalletQB(): array
    {
        return [
            [
                'id' => 'w.username',
                'label' => 'نام کاربری',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'u.first_name',
                'label' => 'نام',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'u.last_name',
                'label' => 'نام خانوادگی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'w.balance',
                'label' => 'مبلغ کیف پول',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'w.is_available',
                'label' => 'وضعیت دسترسی',
                'type' => 'integer',
                'input' => 'select',
                'values' => [
                    0 => 'غیر فعال',
                    1 => 'فعال',
                ],
                'operators' => self::$boolOperation,
            ],
        ];
    }

    /**
     * [] are aliases that used in [ReportWalletDepositController]
     * in fetching datatable data
     *
     * @return array
     */
    public static function getWalletDepositQB(): array
    {
        return [
            [
                'id' => 'wf.username',
                'label' => 'نام کاربری',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'mu.first_name',
                'label' => 'نام',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'mu.last_name',
                'label' => 'نام خانوادگی',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'u.first_name',
                'label' => 'نام تراکنش کننده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'u.last_name',
                'label' => 'نام خانوادگی تراکنش کننده',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'wf.deposit_type_title',
                'label' => 'علت تراکنش',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'wf.deposit_price',
                'label' => 'مبلغ تراکنش',
                'type' => 'string',
                'operators' => self::$stringOperation,
            ],
            [
                'id' => 'wf.deposit_at',
                'label' => 'تاریخ تراکنش',
                'type' => 'date',
                'operators' => self::$dateOperation,
                'validation' => [
                    'format' => 'X'
                ],
                'plugin' => 'persianDatepicker',
                'plugin_config' => self::$datePickerConfig,
            ],
        ];
    }

    /**
     * Return an array of [where, bindValues]
     *
     * @param $qb
     * @return array
     */
    public static function getNormalizedQBStatement($qb): array
    {
        $newWhere = '';
        $newBind = [];
        if (
            (isset($qb['sql']) && !empty($qb['sql'])) &&
            (isset($qb['params']) && !empty($qb['params']) && !is_null($params = json_decode($qb['params'], true)))
        ) {
            foreach ($params as $k => $p) {
                $newK = str_replace('.', '_', $k);
                $qb['sql'] = str_replace($k, $newK, $qb['sql']);
                $newBind[$newK] = $p;
            }

            $newWhere = $qb['sql'];
        }

        return [$newWhere, $newBind];
    }
}
