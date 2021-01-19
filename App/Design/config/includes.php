<?php

/***********************************
 ************** Common *************
 ***********************************/
$commonDefault = config()->getDirectly(__DIR__ . '/common/common-default-inc.php');
$commonAdmin = config()->getDirectly(__DIR__ . '/common/common-admin-inc.php');

/***********************************
 ************** Home ***************
 ***********************************/
$home404 = config()->getDirectly(__DIR__ . '/home/main/404-inc.php');
$homeAbout = config()->getDirectly(__DIR__ . '/home/main/about-inc.php');
$homeBlog = config()->getDirectly(__DIR__ . '/home/main/blog-inc.php');
$homeCart = config()->getDirectly(__DIR__ . '/home/main/cart-inc.php');
$homeComplaint = config()->getDirectly(__DIR__ . '/home/main/complaint-inc.php');
$homeContactUs = config()->getDirectly(__DIR__ . '/home/main/contact-us-inc.php');
$homeFaq = config()->getDirectly(__DIR__ . '/home/main/faq-inc.php');
$homeForgetPassword = config()->getDirectly(__DIR__ . '/home/main/forget-password-inc.php');
$homeIndex = config()->getDirectly(__DIR__ . '/home/main/index-inc.php');
$homeLogin = config()->getDirectly(__DIR__ . '/home/main/login-inc.php');
$homeProduct = config()->getDirectly(__DIR__ . '/home/main/product-inc.php');
$homeSignup = config()->getDirectly(__DIR__ . '/home/main/signup-inc.php');
$homeStaticPage = config()->getDirectly(__DIR__ . '/home/main/static-page-inc.php');

/***********************************
 ************** User ***************
 ***********************************/
$userIndex = config()->getDirectly(__DIR__ . '/home/user/index-inc.php');

/***********************************
 ************ Colleague *************
 ***********************************/
$colleagueIndex = config()->getDirectly(__DIR__ . '/home/colleague/index-inc.php');

/***********************************
 ************** Admin **************
 ***********************************/
$adminBlogCategory = config()->getDirectly(__DIR__ . '/admin/blog-category-inc.php');
$adminBlog = config()->getDirectly(__DIR__ . '/admin/blog-inc.php');
$adminBrand = config()->getDirectly(__DIR__ . '/admin/brand-inc.php');
$adminCategoryImage = config()->getDirectly(__DIR__ . '/admin/category-image-inc.php');
$adminCategory = config()->getDirectly(__DIR__ . '/admin/category-inc.php');
$adminColor = config()->getDirectly(__DIR__ . '/admin/color-inc.php');
$adminComplaint = config()->getDirectly(__DIR__ . '/admin/complaint-inc.php');
$adminContactUs = config()->getDirectly(__DIR__ . '/admin/contact-us-inc.php');
$adminCoupon = config()->getDirectly(__DIR__ . '/admin/coupon-inc.php');
$adminEditor = config()->getDirectly(__DIR__ . '/admin/editor-inc.php');
$adminFaq = config()->getDirectly(__DIR__ . '/admin/faq-inc.php');
$adminFestival = config()->getDirectly(__DIR__ . '/admin/festival-inc.php');
$adminFileManager = config()->getDirectly(__DIR__ . '/admin/file-manager-inc.php');
$adminInstagramImage = config()->getDirectly(__DIR__ . '/admin/instagram-image-inc.php');
$adminLogin = config()->getDirectly(__DIR__ . '/admin/login-inc.php');
$adminMainSlider = config()->getDirectly(__DIR__ . '/admin/main-slider-inc.php');
$adminNewsletter = config()->getDirectly(__DIR__ . '/admin/newsletter-inc.php');
$adminOrder = config()->getDirectly(__DIR__ . '/admin/order-inc.php');
$adminOrderReturn = config()->getDirectly(__DIR__ . '/admin/order-return-inc.php');
$adminPaymentMethod = config()->getDirectly(__DIR__ . '/admin/payment-method-inc.php');
$adminProduct = config()->getDirectly(__DIR__ . '/admin/product-inc.php');
$adminSecurityQuestion = config()->getDirectly(__DIR__ . '/admin/security-question-inc.php');
$adminSetting = config()->getDirectly(__DIR__ . '/admin/setting-inc.php');
$adminStaticPage = config()->getDirectly(__DIR__ . '/admin/static-page-inc.php');
$adminUnit = config()->getDirectly(__DIR__ . '/admin/unit-inc.php');
$adminUser = config()->getDirectly(__DIR__ . '/admin/user-inc.php');
$adminWallet = config()->getDirectly(__DIR__ . '/admin/wallet-inc.php');

return [
    /**
     * Please Read This Before Use:
     * - We can have three types of platform here:
     *     1. Desktop
     *     2. Tablet
     *     3. Mobile
     * - In each platform we have the following structure
     *     1. The [common] key
     *       - In this section we put all files that are common to all pages
     * - Structure of all keys are like:
     *   EXP:
     *       [
     *           'js' => [
     *               'top' => [
     *                   ...
     *               ],
     *               'bottom' => [
     *                   ...
     *               ],
     *               'title' => 'The Title',
     *               'etc.' => ...
     *           ],
     *           'css' => [
     *               ...
     *           ],
     *           'etc.' => ...
     *       ]
     *
     * NOTE:
     *   [common] has a key that use inside your specific page
     *   as [common] key, OK that's confusing look at example below:
     *
     *   [
     *     'common' => [
     *        'default' => [
     *           'js' => [
     *                     'top' => [
     *                     ],
     *                     'bottom' => [
     *                     ],
     *                   ]
     *           'css' => [
     *                     'top' => [
     *                     ],
     *                     'bottom' => [
     *                     ],
     *                   ],
     *         ]
     *     ],
     *     'example-page' => [
     *       'common' => 'default' or array of common like ['default', 'admin'],
     *       ...
     *     ]
     *   ]
     *
     * See, you can have multiple common for each part of your application.
     * Isn't it cool :)
     *
     * NOTE:
     *   1. [common] key just have [js] and [css] keys
     *   2. You must use slash (/) to separate folder and files from each other.
     *   3. key of other items must be name of the file after [design] path
     *     [EXP:
     *       1. Assume that we have a file named [index] in [app/design/view],
     *          so the key name will be [view/index].
     *       2. Now Assume that we have another file named [index] in [app/design/partial/a-folder],
     *          so the key name will be [partial/a-folder/index].
     *   4. BE CAREFUL! If note number 3 is not observe, then the config will not be available.
     *   5. The config is according to the bigger platform (priority considered here)
     *     [EXP:
     *       If you don't specified mobile config, it'll get config according to upper device,
     *       that is tablet, and so on.]
     *   6. It detect which device is trying to get config, automatically for you
     *   7. BE CAREFUL! Please enter unique js and css file paths,
     *      but it try to detect redundant js and css files according to src and href values
     *   8. Add js and css file as following manner
     *     Exp.
     *      htmlspecialchars('<script type="js type like text/javascript" src="the src"></script>'),
     *      e('<script type="js type like text/javascript" src="the src"></script>'),
     */
    'desktop' => array_merge(
        [
            'common' => array_merge($commonDefault, $commonAdmin),
        ],

        // home
        $home404,
        $homeAbout,
        $homeBlog,
        $homeCart,
        $homeComplaint,
        $homeContactUs,
        $homeFaq,
        $homeForgetPassword,
        $homeIndex,
        $homeLogin,
        $homeProduct,
        $homeSignup,
        $homeStaticPage,

        // user
        $userIndex,

        // colleague
        $colleagueIndex,

        // admin
        $adminBlogCategory,
        $adminBlog,
        $adminBrand,
        $adminCategoryImage,
        $adminCategory,
        $adminColor,
        $adminComplaint,
        $adminContactUs,
        $adminCoupon,
        $adminEditor,
        $adminFaq,
        $adminFestival,
        $adminFileManager,
        $adminInstagramImage,
        $adminLogin,
        $adminMainSlider,
        $adminNewsletter,
        $adminOrder,
        $adminOrderReturn,
        $adminPaymentMethod,
        $adminProduct,
        $adminSecurityQuestion,
        $adminSetting,
        $adminStaticPage,
        $adminUnit,
        $adminUser,
        $adminWallet,
        ),
    'tablet' => [
        'default' => [
            'common' => [
                'js' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
                ],
            ],
        ],
    ],
    'mobile' => [
        'default' => [
            'common' => [
                'js' => [
                    'top' => [
                    ],
                    'bottom' => [
                    ],
                ],
                'css' => [
                ],
            ],
        ],
    ],
];