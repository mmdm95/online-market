<?php

use Sim\Auth\Interfaces\IAuth;
use Sim\I18n\ISOLanguageCodes;

/**
 * App version
 * Use semantic-versioning (AKA SemVer) here
 * Numbers are Major.Minor.Patch[-a or -b]
 * -a stands for alpha
 * -b stands for beta
 * -rc stands for release candidate
 *
 * Note:
 *   Please use below options instead of -b and -a and -rc
 *     0 for alpha (status)
 *     1 for beta (status)
 *     2 for release candidate
 *     3 for (final) release
 *   ie.
 *     1.2.5.0 instead of 1.2.5-a
 *     1.2.5.0.1 instead of 1.2.5-a.1 (I'm not sure)
 */
defined("APP_VERSION") or define("APP_VERSION", "0.16.1");

/***************************************
 * You can add your constants here
 ***************************************/

// default language
defined("APP_LANGUAGE") or define("APP_LANGUAGE", ISOLanguageCodes::LANGUAGE_PERSIAN_FARSI);

// some defaults for routes
defined("NOT_FOUND_ADMIN") or define("NOT_FOUND_ADMIN", 'admin.page.notfound');
defined("NOT_FOUND") or define("NOT_FOUND", 'page.notfound');
defined("SERVER_ERROR") or define("SERVER_ERROR", 'page.servererror');

// response types
defined("RESPONSE_TYPE_SUCCESS") or define("RESPONSE_TYPE_SUCCESS", 'success');
defined("RESPONSE_TYPE_WARNING") or define("RESPONSE_TYPE_WARNING", 'warning');
defined("RESPONSE_TYPE_INFO") or define("RESPONSE_TYPE_INFO", 'info');
defined("RESPONSE_TYPE_ERROR") or define("RESPONSE_TYPE_ERROR", 'error');

// title delimiter
defined("TITLE_DELIMITER") or define("TITLE_DELIMITER", ' | ');

// publish or true in database
defined("DB_YES") or define("DB_YES", 1);
defined("DB_NO") or define("DB_NO", 0);

defined("TIME_ONE_WEEK_IN_SECONDS") or define("TIME_ONE_WEEK_IN_SECONDS", 604800);
defined("TIME_THREE_DAYS_IN_SECONDS") or define("TIME_THREE_DAYS_IN_SECONDS", 259200);

// recover password type
defined("RECOVER_PASS_TYPE_SMS") or define("RECOVER_PASS_TYPE_SMS", 1);
defined("RECOVER_PASS_TYPE_SECURITY_QUESTION") or define("RECOVER_PASS_TYPE_SECURITY_QUESTION", 2);

// default placeholders
defined("PLACEHOLDER_USER_IMAGE") or define("PLACEHOLDER_USER_IMAGE", 'avatars-default.png');
defined("PLACEHOLDER_IMAGE") or define("PLACEHOLDER_IMAGE", __DIR__ . '/../public/be/images/file-icons/image-placeholder.png');
defined("PLACEHOLDER_VIDEO") or define("PLACEHOLDER_VIDEO", __DIR__ . '/../public/be/images/file-icons/video-placeholder.png');

// public access directory name
defined("PUBLIC_ACCESS_DIR") or define("PUBLIC_ACCESS_DIR", 'public-access');

// resources constants
defined("RESOURCE_USER") or define("RESOURCE_USER", 'user');
defined("RESOURCE_PAY_METHOD") or define("RESOURCE_PAY_METHOD", 'pay_method');
defined("RESOURCE_COLOR") or define("RESOURCE_COLOR", 'color');
defined("RESOURCE_BRAND") or define("RESOURCE_BRAND", 'brand');
defined("RESOURCE_CATEGORY") or define("RESOURCE_CATEGORY", 'category');
defined("RESOURCE_FESTIVAL") or define("RESOURCE_FESTIVAL", 'festival');
defined("RESOURCE_UNIT") or define("RESOURCE_UNIT", 'unit');
defined("RESOURCE_COUPON") or define("RESOURCE_COUPON", 'coupon');
defined("RESOURCE_PRODUCT") or define("RESOURCE_PRODUCT", 'product');
defined("RESOURCE_WALLET") or define("RESOURCE_WALLET", 'wallet');
defined("RESOURCE_ORDER") or define("RESOURCE_ORDER", 'order');
defined("RESOURCE_BLOG") or define("RESOURCE_BLOG", 'blog');
defined("RESOURCE_BLOG_CATEGORY") or define("RESOURCE_BLOG_CATEGORY", 'blog_category');
defined("RESOURCE_STATIC_PAGE") or define("RESOURCE_STATIC_PAGE", 'static_page');
defined("RESOURCE_CONTACT_US") or define("RESOURCE_CONTACT_US", 'contact_us');
defined("RESOURCE_COMPLAINT") or define("RESOURCE_COMPLAINT", 'complaint');
defined("RESOURCE_FAQ") or define("RESOURCE_FAQ", 'faq');
defined("RESOURCE_NEWSLETTER") or define("RESOURCE_NEWSLETTER", 'newsletter');
defined("RESOURCE_SLIDESHOW") or define("RESOURCE_SLIDESHOW", 'slideshow');
defined("RESOURCE_INSTAGRAM") or define("RESOURCE_INSTAGRAM", 'instagram');
defined("RESOURCE_SEC_QUESTION") or define("RESOURCE_SEC_QUESTION", 'sec_question');
defined("RESOURCE_FILEMANAGER") or define("RESOURCE_FILEMANAGER", 'filemanager');
defined("RESOURCE_SETTING") or define("RESOURCE_SETTING", 'setting');
defined("RESOURCE_REPORT_USER") or define("RESOURCE_REPORT_USER", 'report_user');
defined("RESOURCE_REPORT_PRODUCT") or define("RESOURCE_REPORT_PRODUCT", 'report_product');
defined("RESOURCE_REPORT_WALLET") or define("RESOURCE_REPORT_WALLET", 'report_wallet');
defined("RESOURCE_REPORT_ORDER") or define("RESOURCE_REPORT_ORDER", 'report_order');

// permissions array constant
defined("OWN_PERMISSION_CREATE") or define("OWN_PERMISSION_CREATE", IAuth::PERMISSION_CREATE);
defined("OWN_PERMISSION_READ") or define("OWN_PERMISSION_READ", IAuth::PERMISSION_READ);
defined("OWN_PERMISSION_UPDATE") or define("OWN_PERMISSION_UPDATE", IAuth::PERMISSION_UPDATE);
defined("OWN_PERMISSION_DELETE") or define("OWN_PERMISSION_DELETE", IAuth::PERMISSION_DELETE);
defined("OWN_PERMISSIONS") or define("OWN_PERMISSIONS", IAuth::PERMISSIONS);

// setting keys
defined("SETTING_LOGO") or define("SETTING_LOGO", 'logo');
defined("SETTING_LOGO_LIGHT") or define("SETTING_LOGO_LIGHT", 'logo_light');
defined("SETTING_FAVICON") or define("SETTING_FAVICON", 'favicon');
defined("SETTING_LOGO_FOOTER") or define("SETTING_LOGO_FOOTER", 'logo_footer');
defined("SETTING_LOGO_LIGHT_FOOTER") or define("SETTING_LOGO_LIGHT_FOOTER", 'logo_light_footer');
defined("SETTING_TITLE") or define("SETTING_TITLE", 'title');
defined("SETTING_DESCRIPTION") or define("SETTING_DESCRIPTION", 'description');
defined("SETTING_KEYWORDS") or define("SETTING_KEYWORDS", 'keywords');
defined("SETTING_SMS_ACTIVATION") or define("SETTING_SMS_ACTIVATION", 'sms_activation');
defined("SETTING_SMS_RECOVER_PASS") or define("SETTING_SMS_RECOVER_PASS", 'sms_recover_pass');
defined("SETTING_SMS_BUY") or define("SETTING_SMS_BUY", 'sms_buy');
defined("SETTING_SMS_ORDER_STATUS") or define("SETTING_SMS_ORDER_STATUS", 'sms_order_status');
defined("SETTING_SMS_WALLET_CHARGE") or define("SETTING_SMS_WALLET_CHARGE", 'sms_wallet_charge');
defined("SETTING_SMS_RETURN_ORDER_STATUS") or define("SETTING_SMS_RETURN_ORDER_STATUS", 'sms_return_order');
defined("SETTING_FEATURES") or define("SETTING_FEATURES", 'features');
defined("SETTING_ADDRESS") or define("SETTING_ADDRESS", 'address');
defined("SETTING_PHONES") or define("SETTING_PHONES", 'phones');
defined("SETTING_MAIN_PHONE") or define("SETTING_MAIN_PHONE", 'main_phone');
defined("SETTING_EMAIL") or define("SETTING_EMAIL", 'email');
defined("SETTING_LAT_LNG") or define("SETTING_LAT_LNG", 'lat_lng');
defined("SETTING_OUR_TEAM") or define("SETTING_OUR_TEAM", 'our_team');
defined("SETTING_SOCIAL_TELEGRAM") or define("SETTING_SOCIAL_TELEGRAM", 'social_telegram');
defined("SETTING_SOCIAL_INSTAGRAM") or define("SETTING_SOCIAL_INSTAGRAM", 'social_instagram');
defined("SETTING_SOCIAL_WHATSAPP") or define("SETTING_SOCIAL_WHATSAPP", 'social_whatsapp');
defined("SETTING_FOOTER_TINY_DESC") or define("SETTING_FOOTER_TINY_DESC", 'footer_tiny_desc');
defined("SETTING_FOOTER_SECTION_1") or define("SETTING_FOOTER_SECTION_1", 'footer_section_1');
defined("SETTING_FOOTER_SECTION_2") or define("SETTING_FOOTER_SECTION_2", 'footer_section_2');
defined("SETTING_FOOTER_COPYRIGHT") or define("SETTING_FOOTER_COPYRIGHT", 'footer_copyright');
defined("SETTING_FOOTER_NAMADS") or define("SETTING_FOOTER_NAMADS", 'footer_namads');
defined("SETTING_BLOG_EACH_PAGE") or define("SETTING_BLOG_EACH_PAGE", 'blog_each_page');
defined("SETTING_STORE_PROVINCE") or define("SETTING_STORE_PROVINCE", 'store_province');
defined("SETTING_STORE_CITY") or define("SETTING_STORE_CITY", 'store_city');
defined("SETTING_CURRENT_CITY_POST_PRICE") or define("SETTING_CURRENT_CITY_POST_PRICE", 'current_city_post_price');
defined("SETTING_POST_PRICE_CALCULATION_MODE") or define("SETTING_POST_PRICE_CALCULATION_MODE", 'post_price_calculation_mode');
defined("SETTING_STATIC_POST_PRICE") or define("SETTING_STATIC_POST_PRICE", 'static_post_price');
defined("SETTING_MIN_FREE_PRICE") or define("SETTING_MIN_FREE_PRICE", 'min_free_price');
defined("SETTING_PRODUCT_EACH_PAGE") or define("SETTING_PRODUCT_EACH_PAGE", 'product_each_page');
defined("SETTING_INDEX_MAIN_SLIDER_SIDE_IMAGES") or define("SETTING_INDEX_MAIN_SLIDER_SIDE_IMAGES", 'index_main_slider_side_images');
defined("SETTING_INDEX_3_IMAGES") or define("SETTING_INDEX_3_IMAGES", 'index_3_images');
defined("SETTING_INDEX_TABBED_SLIDER") or define("SETTING_INDEX_TABBED_SLIDER", 'index_tabbed_slider');
defined("SETTING_INDEX_TABBED_SLIDER_SIDE_IMAGE") or define("SETTING_INDEX_TABBED_SLIDER_SIDE_IMAGE", 'index_tabbed_slider_side_image');
defined("SETTING_INDEX_GENERAL_SLIDERS") or define("SETTING_INDEX_GENERAL_SLIDERS", 'index_general_sliders');
defined("SETTING_ABOUT_SECTION") or define("SETTING_ABOUT_SECTION", 'about_section');
defined("SETTING_TOP_MENU") or define("SETTING_TOP_MENU", 'top_menu');
defined("SETTING_DEFAULT_IMAGE_PLACEHOLDER") or define("SETTING_DEFAULT_IMAGE_PLACEHOLDER", 'default_image_placeholder');

// send price calculations
defined("SEND_PRICE_CALCULATION_AUTO") or define("SEND_PRICE_CALCULATION_AUTO", 'auto');
defined("SEND_PRICE_CALCULATION_STATIC") or define("SEND_PRICE_CALCULATION_STATIC", 'static');

// define all roles
defined("ROLE_DEVELOPER") or define("ROLE_DEVELOPER", 'developer');
defined("ROLE_SUPER_USER") or define("ROLE_SUPER_USER", 'super_user');
defined("ROLE_ADMIN") or define("ROLE_ADMIN", 'admin');
defined("ROLE_COLLEAGUE") or define("ROLE_COLLEAGUE", 'colleague');
defined("ROLE_USER") or define("ROLE_USER", 'user');
defined("ROLE_WRITER") or define("ROLE_WRITER", 'writer');
defined("ROLE_SHOP_ADMIN") or define("ROLE_SHOP_ADMIN", 'shop_admin');
defined("ROLES_ARRAY_ACCEPTABLE") or define("ROLES_ARRAY_ACCEPTABLE", [ROLE_ADMIN, ROLE_USER, ROLE_WRITER, ROLE_SHOP_ADMIN]);
defined("ROLES_ARRAY_ACCEPTABLE_ALL_SUPER_USER")
or define("ROLES_ARRAY_ACCEPTABLE_ALL_SUPER_USER", [ROLE_ADMIN, ROLE_USER, ROLE_WRITER, ROLE_SHOP_ADMIN, ROLE_SUPER_USER]);
defined("ROLES_ARRAY_ACCEPTABLE_ALL")
or define("ROLES_ARRAY_ACCEPTABLE_ALL", [ROLE_ADMIN, ROLE_USER, ROLE_WRITER, ROLE_SHOP_ADMIN, ROLE_SUPER_USER, ROLE_DEVELOPER]);

// sms types
defined("SMS_TYPE_ACTIVATION") or define("SMS_TYPE_ACTIVATION", 'sms_activation');
defined("SMS_TYPE_RECOVER_PASS") or define("SMS_TYPE_RECOVER_PASS", 'sms_recover_pass');
defined("SMS_TYPE_BUY") or define("SMS_TYPE_BUY", 'sms_buy');
defined("SMS_TYPE_ORDER_STATUS") or define("SMS_TYPE_ORDER_STATUS", 'sms_order_status');
defined("SMS_TYPE_WALLET_CHARGE") or define("SMS_TYPE_WALLET_CHARGE", 'sms_wallet_charge');
defined("SMS_TYPE_RETURN_ORDER_STATUS") or define("SMS_TYPE_RETURN_ORDER_STATUS", 'sms_return_order');

// sms log types
defined("SMS_LOG_TYPE_REGISTER") or define("SMS_LOG_TYPE_REGISTER", 1);
defined("SMS_LOG_TYPE_RECOVER_PASS") or define("SMS_LOG_TYPE_RECOVER_PASS", 2);
defined("SMS_LOG_TYPE_BUY") or define("SMS_LOG_TYPE_BUY", 3);
defined("SMS_LOG_TYPE_ORDER_STATUS") or define("SMS_LOG_TYPE_ORDER_STATUS", 4);
defined("SMS_LOG_TYPE_WALLET_CHARGE") or define("SMS_LOG_TYPE_WALLET_CHARGE", 5);
defined("SMS_LOG_TYPE_ORDER_SUCCESS") or define("SMS_LOG_TYPE_ORDER_SUCCESS", 6);
defined("SMS_LOG_TYPE_ORDER_NOTIFY") or define("SMS_LOG_TYPE_ORDER_NOTIFY", 7);
defined("SMS_LOG_TYPE_RETURN_ORDER_STATUS") or define("SMS_LOG_TYPE_RETURN_ORDER_STATUS", 8);
defined("SMS_LOG_TYPE_OTHERS") or define("SMS_LOG_TYPE_OTHERS", 100);

// sms log sender
defined("SMS_LOG_SENDER_SYSTEM") or define("SMS_LOG_SENDER_SYSTEM", 1);
defined("SMS_LOG_SENDER_USER") or define("SMS_LOG_SENDER_USER", 2);

// sms replacements
defined('SMS_REPLACEMENTS') or define('SMS_REPLACEMENTS', [
    'mobile' => '@mobile@',
    'code' => '@code@',
    'orderCode' => '@orderCode@',
    'status' => '@status@',
    'balance' => '@balance@',
]);

// gateway error message
defined("GATEWAY_SUCCESS_MESSAGE") or define("GATEWAY_SUCCESS_MESSAGE", 'تراکنش با موفقیت انجام شد.');
defined("GATEWAY_ERROR_MESSAGE") or define("GATEWAY_ERROR_MESSAGE", 'تراکنش نا موفق بود، در صورت کسر مبلغ از حساب شما حداکثر پس از 72 ساعت مبلغ به حسابتان برگشت داده می‌شود.');
defined("GATEWAY_INVALID_PARAMETERS_MESSAGE") or define("GATEWAY_INVALID_PARAMETERS_MESSAGE", 'پارامترهای ارسالی از درگاه نامعتبر است.');

// gateway flow status codes
defined("PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST") or define("PAYMENT_GATEWAY_FLOW_STATUS_CREATE_REQUEST", 1);
defined("PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT") or define("PAYMENT_GATEWAY_FLOW_STATUS_HANDLE_RESULT", 2);
defined("PAYMENT_GATEWAY_FLOW_STATUS_ADVICE") or define("PAYMENT_GATEWAY_FLOW_STATUS_ADVICE", 3);
defined("PAYMENT_GATEWAY_FLOW_STATUS_SETTLE") or define("PAYMENT_GATEWAY_FLOW_STATUS_SETTLE", 4);

// order payment statuses
defined("PAYMENT_STATUS_SUCCESS") or define("PAYMENT_STATUS_SUCCESS", 1);
defined("PAYMENT_STATUS_FAILED") or define("PAYMENT_STATUS_FAILED", 0);
defined("PAYMENT_STATUS_WAIT_VERIFY") or define("PAYMENT_STATUS_WAIT_VERIFY", -7);
defined("PAYMENT_STATUS_WAIT") or define("PAYMENT_STATUS_WAIT", -8);
defined("PAYMENT_STATUS_NOT_PAYED") or define("PAYMENT_STATUS_NOT_PAYED", -9);
defined('PAYMENT_STATUSES') or define('PAYMENT_STATUSES', [
    PAYMENT_STATUS_SUCCESS => 'پرداخت شده',
    PAYMENT_STATUS_FAILED => 'پرداخت ناموفق',
    PAYMENT_STATUS_WAIT_VERIFY => 'در انتظار تایید',
    PAYMENT_STATUS_WAIT => 'در انتظار پرداخت',
    PAYMENT_STATUS_NOT_PAYED => 'پرداخت نشده',
]);

// needed payment method constants
defined("METHOD_TYPE_WALLET") or define("METHOD_TYPE_WALLET", 5);
defined("METHOD_TYPE_IN_PLACE") or define("METHOD_TYPE_IN_PLACE", 6);
defined("METHOD_TYPE_RECEIPT") or define("METHOD_TYPE_RECEIPT", 7);
defined("METHOD_TYPE_GATEWAY_BEH_PARDAKHT") or define("METHOD_TYPE_GATEWAY_BEH_PARDAKHT", 1);
defined("METHOD_TYPE_GATEWAY_IDPAY") or define("METHOD_TYPE_GATEWAY_IDPAY", 2);
defined("METHOD_TYPE_GATEWAY_MABNA") or define("METHOD_TYPE_GATEWAY_MABNA", 3);
defined("METHOD_TYPE_GATEWAY_ZARINPAL") or define("METHOD_TYPE_GATEWAY_ZARINPAL", 4);
defined("METHOD_TYPE_GATEWAY_SADAD") or define("METHOD_TYPE_GATEWAY_SADAD", 5);
defined("METHOD_TYPE_GATEWAY_TAP") or define("METHOD_TYPE_GATEWAY_TAP", 6);
defined("METHOD_TYPE_GATEWAY_IRAN_KISH") or define("METHOD_TYPE_GATEWAY_IRAN_KISH", 7);
defined("METHOD_TYPES") or define("METHOD_TYPES", [
    METHOD_TYPE_GATEWAY_BEH_PARDAKHT => 'درگاه بانک - به پرداخت',
    METHOD_TYPE_GATEWAY_IDPAY => 'درگاه بانک - آیدی پی',
    METHOD_TYPE_GATEWAY_MABNA => 'درگاه بانک - پرداخت الکترونیک سپهر (مبنا)',
    METHOD_TYPE_GATEWAY_ZARINPAL => 'درگاه بانک - زرین پال',
    METHOD_TYPE_GATEWAY_SADAD => 'درگاه بانک - سداد',
    METHOD_TYPE_GATEWAY_TAP => 'درگاه بانک - تجارت الکترونیک پارسیان (تاپ)',
    METHOD_TYPE_GATEWAY_IRAN_KISH => 'درگاه بانک - ایران کیش',
]);

// needed payment method type for payment result
defined("METHOD_RESULT_TYPE_BEH_PARDAKHT") or define("METHOD_RESULT_TYPE_BEH_PARDAKHT", 'beh_pardakht');
defined("METHOD_RESULT_TYPE_IDPAY") or define("METHOD_RESULT_TYPE_IDPAY", 'idpay');
defined("METHOD_RESULT_TYPE_MABNA") or define("METHOD_RESULT_TYPE_MABNA", 'mabna');
defined("METHOD_RESULT_TYPE_ZARINPAL") or define("METHOD_RESULT_TYPE_ZARINPAL", 'zarinpal');
defined("METHOD_RESULT_TYPE_SADAD") or define("METHOD_RESULT_TYPE_SADAD", 'sadad');
defined("METHOD_RESULT_TYPE_TAP") or define("METHOD_RESULT_TYPE_TAP", 'tap');
defined("METHOD_RESULT_TYPE_IRAN_KISH") or define("METHOD_RESULT_TYPE_IRAN_KISH", 'irankish');

// deposit type codes
defined("DEPOSIT_TYPE_PAYED") or define("DEPOSIT_TYPE_PAYED", "a1b2c3d4e5f6");
defined("DEPOSIT_TYPE_CHARGE") or define("DEPOSIT_TYPE_CHARGE", "g7h8i9j1k2l3");

// return order statuses
defined("RETURN_ORDER_STATUS_CHECKING") or define("RETURN_ORDER_STATUS_CHECKING", 1);
defined("RETURN_ORDER_STATUS_DENIED_BY_USER") or define("RETURN_ORDER_STATUS_DENIED_BY_USER", 2);
defined("RETURN_ORDER_STATUS_ACCEPT") or define("RETURN_ORDER_STATUS_ACCEPT", 3);
defined("RETURN_ORDER_STATUS_DENIED") or define("RETURN_ORDER_STATUS_DENIED", 4);
defined("RETURN_ORDER_STATUS_SENDING") or define("RETURN_ORDER_STATUS_SENDING", 5);
defined("RETURN_ORDER_STATUS_RECEIVED") or define("RETURN_ORDER_STATUS_RECEIVED", 6);
defined("RETURN_ORDER_STATUS_MONEY_RETURNED") or define("RETURN_ORDER_STATUS_MONEY_RETURNED", 7);
defined("RETURN_ORDER_STATUSES") or define("RETURN_ORDER_STATUSES", [
    RETURN_ORDER_STATUS_CHECKING => 'در حال بررسی',
    RETURN_ORDER_STATUS_ACCEPT => 'تایید شده',
    RETURN_ORDER_STATUS_DENIED => 'تایید نشده',
    RETURN_ORDER_STATUS_RECEIVED => 'دریافت کالای مرجوعی',
    RETURN_ORDER_STATUS_MONEY_RETURNED => 'بازگشت مبلغ کالاها',
]);

// comment statuses
defined("COMMENT_STATUS_NOT_READ") or define("COMMENT_STATUS_NOT_READ", 0);
defined("COMMENT_STATUS_READ") or define("COMMENT_STATUS_READ", 1);
defined("COMMENT_STATUS_REPLIED") or define("COMMENT_STATUS_REPLIED", 2);
// comment conditions
defined("COMMENT_CONDITION_NOT_SET") or define("COMMENT_CONDITION_NOT_SET", -1);
defined("COMMENT_CONDITION_REJECT") or define("COMMENT_CONDITION_REJECT", 0);
defined("COMMENT_CONDITION_ACCEPT") or define("COMMENT_CONDITION_ACCEPT", 1);

// contact us statuses
defined("CONTACT_STATUS_UNREAD") or define("CONTACT_STATUS_UNREAD", 1);
defined("CONTACT_STATUS_READ") or define("CONTACT_STATUS_READ", 2);

// complaint statuses
defined("COMPLAINT_STATUS_UNREAD") or define("COMPLAINT_STATUS_UNREAD", 1);
defined("COMPLAINT_STATUS_READ") or define("COMPLAINT_STATUS_READ", 2);

// time constants
defined("TIME_ACTIVATE_CODE") or define("TIME_ACTIVATE_CODE", 180);

// tabbed slider predefined items
defined("SLIDER_TABBED_NEWEST") or define("SLIDER_TABBED_NEWEST", 1);
defined("SLIDER_TABBED_MOST_SELLER") or define("SLIDER_TABBED_MOST_SELLER", 2);
defined("SLIDER_TABBED_MOST_DISCOUNT") or define("SLIDER_TABBED_MOST_DISCOUNT", 3);
// featured = اختصاصی - is_special in products
defined("SLIDER_TABBED_FEATURED") or define("SLIDER_TABBED_FEATURED", 4);
// special = ویژه - from festivals
defined("SLIDER_TABBED_SPECIAL") or define("SLIDER_TABBED_SPECIAL", 5);
defined("SLIDER_TABBED_TYPES") or define("SLIDER_TABBED_TYPES", [
    SLIDER_TABBED_NEWEST => 'جدیدترین',
    SLIDER_TABBED_MOST_SELLER => 'پرفروش ترین',
    SLIDER_TABBED_MOST_DISCOUNT => 'پرتخفیف ترین',
    SLIDER_TABBED_FEATURED => 'ویژه',
    SLIDER_TABBED_SPECIAL => 'جشنواره',
]);

// product orderings
defined("PRODUCT_ORDERING_NEWEST") or define("PRODUCT_ORDERING_NEWEST", 1);
defined("PRODUCT_ORDERING_MOST_VIEWED") or define("PRODUCT_ORDERING_MOST_VIEWED", 12);
defined("PRODUCT_ORDERING_MOST_DISCOUNT") or define("PRODUCT_ORDERING_MOST_DISCOUNT", 16);
defined("PRODUCT_ORDERING_CHEAPEST") or define("PRODUCT_ORDERING_CHEAPEST", 4);
defined("PRODUCT_ORDERING_MOST_EXPENSIVE") or define("PRODUCT_ORDERING_MOST_EXPENSIVE", 7);
defined("PRODUCT_ORDERINGS") or define("PRODUCT_ORDERINGS", [
    PRODUCT_ORDERING_NEWEST => 'جدیدترین',
    PRODUCT_ORDERING_MOST_VIEWED => 'پربازدیدترین',
    PRODUCT_ORDERING_MOST_DISCOUNT => 'پرتخفیف ترین',
    PRODUCT_ORDERING_CHEAPEST => 'ارزان ترین',
    PRODUCT_ORDERING_MOST_EXPENSIVE => 'گران ترین',
]);

// maximum order reserve time
defined("RESERVE_MAX_TIME") or define("RESERVE_MAX_TIME", 1200 /* 20min */);

// maximum store for addresses
defined("ADDRESS_MAX_COUNT") or define("ADDRESS_MAX_COUNT", 10);

// default time format
defined("DEFAULT_TIME_FORMAT") or define("DEFAULT_TIME_FORMAT", 'j F Y');
defined("DEFAULT_TIME_FORMAT_WITH_TIME") or define("DEFAULT_TIME_FORMAT_WITH_TIME", 'j F Y در ساعت H و i دقیقه');

// chart time format
defined("CHART_BOUGHT_STATUS_TIME_FORMAT") or define("CHART_BOUGHT_STATUS_TIME_FORMAT", 'j F');

// archive tags time format
defined("ARCHIVE_TAGS_TIME_FORMAT") or define("ARCHIVE_TAGS_TIME_FORMAT", 'F Y');

// report time format
defined("REPORT_TIME_FORMAT") or define("REPORT_TIME_FORMAT", 'j F Y در ساعت H و i دقیقه');

// default select option value
defined("DEFAULT_OPTION_VALUE") or define("DEFAULT_OPTION_VALUE", -1);

// min warning stock value
defined("MINIMUM_WARNING_STOCK_VALUE") or define("MINIMUM_WARNING_STOCK_VALUE", 4);

// max category level number
defined("MAX_CATEGORY_LEVEL") or define("MAX_CATEGORY_LEVEL", 3);

// query builder sessions
defined("SESSION_QUERY_BUILDER_USER") or define("SESSION_QUERY_BUILDER_USER", 'session_query_builder_user');
defined("SESSION_QUERY_BUILDER_PRODUCT") or define("SESSION_QUERY_BUILDER_PRODUCT", 'session_query_builder_product');
defined("SESSION_QUERY_BUILDER_ORDER") or define("SESSION_QUERY_BUILDER_ORDER", 'session_query_builder_order');
defined("SESSION_QUERY_BUILDER_WALLET") or define("SESSION_QUERY_BUILDER_WALLET", 'session_query_builder_wallet');
defined("SESSION_QUERY_BUILDER_WALLET_DEPOSIT") or define("SESSION_QUERY_BUILDER_WALLET_DEPOSIT", 'session_query_builder_wallet_deposit');

// report after datatable filter sessions
defined("SESSION_REPORT_FILTERED_USER") or define("SESSION_REPORT_FILTERED_USER", 'session_report_filtered_user');
defined("SESSION_REPORT_FILTERED_PRODUCT") or define("SESSION_REPORT_FILTERED_PRODUCT", 'session_report_filtered_product');
defined("SESSION_REPORT_FILTERED_ORDER") or define("SESSION_REPORT_FILTERED_ORDER", 'session_report_filtered_order');
defined("SESSION_REPORT_FILTERED_WALLET") or define("SESSION_REPORT_FILTERED_WALLET", 'session_report_filtered_wallet');
defined("SESSION_REPORT_FILTERED_WALLET_DEPOSIT") or define("SESSION_REPORT_FILTERED_WALLET_DEPOSIT", 'session_report_filtered_wallet_deposit');

// order array info session key constant
defined("SESSION_ORDER_ARR_INFO") or define("SESSION_ORDER_ARR_INFO", 'session_order_arr_info_custom');
// coupon code session key constant
defined("SESSION_APPLIED_COUPON_CODE") or define("SESSION_APPLIED_COUPON_CODE", 'session_applied_coupon_code');
// post price session key constant
defined("SESSION_APPLIED_POST_PRICE") or define("SESSION_APPLIED_POST_PRICE", 'session_applied_post_price');
// the gateway session key constant
defined("SESSION_GATEWAY_RECORD") or define("SESSION_GATEWAY_RECORD", 'session_gateway_record_custom');
// wallet charge array info session key constant
defined("SESSION_WALLET_CHARGE_ARR_INFO") or define("SESSION_WALLET_CHARGE_ARR_INFO", 'session_wallet_charge_arr_info_custom');
// the gateway wallet charge session key constant
defined("SESSION_GATEWAY_CHARGE_RECORD") or define("SESSION_GATEWAY_CHARGE_RECORD", 'session_gateway_charge_record_custom');
// the wallet charge code session key constant
defined("SESSION_WALLET_CHARGE_CODE") or define("SESSION_WALLET_CHARGE_CODE", 'session_wallet_charge_code_custom');

//
// sitemap needed constants
//

defined("SITEMAP_DATETIME_FORMAT") or define("SITEMAP_DATETIME_FORMAT", 'Y-m-d\TH:i:s.uP');

// sitemap change frequencies
defined("SITEMAP_CHANGE_FREQ_ALWAYS") or define("SITEMAP_CHANGE_FREQ_ALWAYS", 'always');
defined("SITEMAP_CHANGE_FREQ_HOURLY") or define("SITEMAP_CHANGE_FREQ_HOURLY", 'hourly');
defined("SITEMAP_CHANGE_FREQ_DAILY") or define("SITEMAP_CHANGE_FREQ_DAILY", 'daily');
defined("SITEMAP_CHANGE_FREQ_WEEKLY") or define("SITEMAP_CHANGE_FREQ_WEEKLY", 'weekly');
defined("SITEMAP_CHANGE_FREQ_MONTHLY") or define("SITEMAP_CHANGE_FREQ_MONTHLY", 'monthly');
defined("SITEMAP_CHANGE_FREQ_YEARLY") or define("SITEMAP_CHANGE_FREQ_YEARLY", 'yearly');
defined("SITEMAP_CHANGE_FREQ_NEVER") or define("SITEMAP_CHANGE_FREQ_NEVER", 'never');

// sitemap valid priorities
defined("SITEMAP_PRIORITY_0_0") or define("SITEMAP_PRIORITY_0_0", 0.0);
defined("SITEMAP_PRIORITY_0_1") or define("SITEMAP_PRIORITY_0_1", 0.1);
defined("SITEMAP_PRIORITY_0_2") or define("SITEMAP_PRIORITY_0_2", 0.2);
defined("SITEMAP_PRIORITY_0_3") or define("SITEMAP_PRIORITY_0_3", 0.3);
defined("SITEMAP_PRIORITY_0_4") or define("SITEMAP_PRIORITY_0_4", 0.4);
defined("SITEMAP_PRIORITY_0_5") or define("SITEMAP_PRIORITY_0_5", 0.5);
defined("SITEMAP_PRIORITY_0_6") or define("SITEMAP_PRIORITY_0_6", 0.6);
defined("SITEMAP_PRIORITY_0_7") or define("SITEMAP_PRIORITY_0_7", 0.7);
defined("SITEMAP_PRIORITY_0_8") or define("SITEMAP_PRIORITY_0_8", 0.8);
defined("SITEMAP_PRIORITY_0_9") or define("SITEMAP_PRIORITY_0_9", 0.9);
defined("SITEMAP_PRIORITY_1_0") or define("SITEMAP_PRIORITY_1_0", 1.0);

//
// products side search needed constants
//

// attribute types
defined("PRODUCT_SIDE_SEARCH_ATTR_TYPE_SINGLE_SELECT") or define("PRODUCT_SIDE_SEARCH_ATTR_TYPE_SINGLE_SELECT", 'single-select');
defined("PRODUCT_SIDE_SEARCH_ATTR_TYPE_MULTI_SELECT") or define("PRODUCT_SIDE_SEARCH_ATTR_TYPE_MULTI_SELECT", 'multi-select');
