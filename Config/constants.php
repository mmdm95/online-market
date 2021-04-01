<?php

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
 *     1.2.5.0.1 instead of 1.2.5-a1 (I'm not sure)
 */
defined("APP_VERSION") OR define("APP_VERSION", "0.1.17");

/***************************************
 * You can add your constants here
 ***************************************/

// default language
defined("APP_LANGUAGE") OR define("APP_LANGUAGE", ISOLanguageCodes::LANGUAGE_PERSIAN_FARSI);

// some defaults for routes
defined("NOT_FOUND_ADMIN") OR define("NOT_FOUND_ADMIN", 'admin.page.notfound');
defined("NOT_FOUND") OR define("NOT_FOUND", 'page.notfound');
defined("SERVER_ERROR") OR define("SERVER_ERROR", 'page.servererror');

// response types
defined("RESPONSE_TYPE_SUCCESS") OR define("RESPONSE_TYPE_SUCCESS", 'success');
defined("RESPONSE_TYPE_WARNING") OR define("RESPONSE_TYPE_WARNING", 'warning');
defined("RESPONSE_TYPE_INFO") OR define("RESPONSE_TYPE_INFO", 'info');
defined("RESPONSE_TYPE_ERROR") OR define("RESPONSE_TYPE_ERROR", 'error');

// title delimiter
defined("TITLE_DELIMITER") OR define("TITLE_DELIMITER", ' | ');

// publish or true in database
defined("DB_YES") OR define("DB_YES", 1);
defined("DB_NO") OR define("DB_NO", 0);

// recover password type
defined("RECOVER_PASS_TYPE_SMS") OR define("RECOVER_PASS_TYPE_SMS", 1);
defined("RECOVER_PASS_TYPE_SECURITY_QUESTION") OR define("RECOVER_PASS_TYPE_SECURITY_QUESTION", 2);

// default placeholders
defined("PLACEHOLDER_USER_IMAGE") OR define("PLACEHOLDER_USER_IMAGE", 'avatars-default.png');
defined("PLACEHOLDER_IMAGE") OR define("PLACEHOLDER_IMAGE", __DIR__ . '/../public/be/images/file-icons/image-placeholder.png');
defined("PLACEHOLDER_VIDEO") OR define("PLACEHOLDER_VIDEO", __DIR__ . '/../public/be/images/file-icons/video-placeholder.png');

// public access directory name
defined("PUBLIC_ACCESS_DIR") OR define("PUBLIC_ACCESS_DIR", 'public-access');

// setting keys
defined("SETTING_LOGO") OR define("SETTING_LOGO", 'logo');
defined("SETTING_LOGO_LIGHT") OR define("SETTING_LOGO_LIGHT", 'logo_light');
defined("SETTING_FAVICON") OR define("SETTING_FAVICON", 'favicon');
defined("SETTING_TITLE") OR define("SETTING_TITLE", 'title');
defined("SETTING_DESCRIPTION") OR define("SETTING_DESCRIPTION", 'description');
defined("SETTING_KEYWORDS") OR define("SETTING_KEYWORDS", 'keywords');
defined("SETTING_SMS_ACTIVATION") OR define("SETTING_SMS_ACTIVATION", 'sms_activation');
defined("SETTING_SMS_RECOVER_PASS") OR define("SETTING_SMS_RECOVER_PASS", 'sms_recover_pass');
defined("SETTING_SMS_BUY") OR define("SETTING_SMS_BUY", 'sms_buy');
defined("SETTING_SMS_ORDER_STATUS") OR define("SETTING_SMS_ORDER_STATUS", 'sms_order_status');
defined("SETTING_SMS_WALLET_CHARGE") OR define("SETTING_SMS_WALLET_CHARGE", 'sms_wallet_charge');
defined("SETTING_FEATURES") OR define("SETTING_FEATURES", 'features');
defined("SETTING_ADDRESS") OR define("SETTING_ADDRESS", 'address');
defined("SETTING_PHONES") OR define("SETTING_PHONES", 'phones');
defined("SETTING_MAIN_PHONE") OR define("SETTING_MAIN_PHONE", 'main_phone');
defined("SETTING_EMAIL") OR define("SETTING_EMAIL", 'email');
defined("SETTING_LAT_LNG") OR define("SETTING_LAT_LNG", 'lat_lng');
defined("SETTING_OUR_TEAM") OR define("SETTING_OUR_TEAM", 'our_team');
defined("SETTING_SOCIAL_TELEGRAM") OR define("SETTING_SOCIAL_TELEGRAM", 'social_telegram');
defined("SETTING_SOCIAL_INSTAGRAM") OR define("SETTING_SOCIAL_INSTAGRAM", 'social_instagram');
defined("SETTING_SOCIAL_WHATSAPP") OR define("SETTING_SOCIAL_WHATSAPP", 'social_whatsapp');
defined("SETTING_FOOTER_TINY_DESC") OR define("SETTING_FOOTER_TINY_DESC", 'footer_tiny_desc');
defined("SETTING_FOOTER_SECTION_1") OR define("SETTING_FOOTER_SECTION_1", 'footer_section_1');
defined("SETTING_FOOTER_SECTION_2") OR define("SETTING_FOOTER_SECTION_2", 'footer_section_2');
defined("SETTING_FOOTER_NAMADS") OR define("SETTING_FOOTER_NAMADS", 'footer_namads');
defined("SETTING_BLOG_EACH_PAGE") OR define("SETTING_BLOG_EACH_PAGE", 'blog_each_page');
defined("SETTING_STORE_PROVINCE") OR define("SETTING_STORE_PROVINCE", 'store_province');
defined("SETTING_STORE_CITY") OR define("SETTING_STORE_CITY", 'store_city');
defined("SETTING_CURRENT_CITY_POST_PRICE") OR define("SETTING_CURRENT_CITY_POST_PRICE", 'current_city_post_price');
defined("SETTING_MIN_FREE_PRICE") OR define("SETTING_MIN_FREE_PRICE", 'min_free_price');
defined("SETTING_PRODUCT_EACH_PAGE") OR define("SETTING_PRODUCT_EACH_PAGE", 'product_each_page');
defined("SETTING_INDEX_3_IMAGES") OR define("SETTING_INDEX_3_IMAGES", 'index_3_images');
defined("SETTING_INDEX_TABBED_SLIDER") OR define("SETTING_INDEX_TABBED_SLIDER", 'index_tabbed_slider');
defined("SETTING_ABOUT_SECTION") OR define("SETTING_ABOUT_SECTION", 'about_section');
defined("SETTING_TOP_MENU") OR define("SETTING_TOP_MENU", 'top_menu');

// define all roles
defined("ROLE_DEVELOPER") OR define("ROLE_DEVELOPER", 'developer');
defined("ROLE_SUPER_USER") OR define("ROLE_SUPER_USER", 'super_user');
defined("ROLE_ADMIN") OR define("ROLE_ADMIN", 'admin');
defined("ROLE_COLLEAGUE") OR define("ROLE_COLLEAGUE", 'colleague');
defined("ROLE_USER") OR define("ROLE_USER", 'user');
defined("ROLE_WRITER") OR define("ROLE_WRITER", 'writer');
defined("ROLE_SHOP_ADMIN") OR define("ROLE_SHOP_ADMIN", 'shop_admin');
defined("ROLES_ARRAY_ACCEPTABLE") OR define("ROLES_ARRAY_ACCEPTABLE", [ROLE_ADMIN, ROLE_USER, ROLE_WRITER, ROLE_SHOP_ADMIN]);

// sms types
defined("SMS_TYPE_ACTIVATION") OR define("SMS_TYPE_ACTIVATION", 'sms_activation');
defined("SMS_TYPE_RECOVER_PASS") OR define("SMS_TYPE_RECOVER_PASS", 'sms_recover_pass');
defined("SMS_TYPE_BUY") OR define("SMS_TYPE_BUY", 'sms_buy');
defined("SMS_TYPE_ORDER_STATUS") OR define("SMS_TYPE_ORDER_STATUS", 'sms_order_status');
defined("SMS_TYPE_WALLET_CHARGE") OR define("SMS_TYPE_WALLET_CHARGE", 'sms_wallet_charge');

// sms replacements
defined('SMS_REPLACEMENTS') OR define('SMS_REPLACEMENTS', [
    'mobile' => '@mobile@',
    'code' => '@code@',
    'orderCode' => '@orderCode@',
    'status' => '@status@',
    'balance' => '@balance@',
]);

// order payment statuses
defined("PAYMENT_STATUS_SUCCESS") OR define("PAYMENT_STATUS_SUCCESS", 1);
defined("PAYMENT_STATUS_FAILED") OR define("PAYMENT_STATUS_FAILED", 0);
defined("PAYMENT_STATUS_WAIT_VERIFY") OR define("PAYMENT_STATUS_WAIT_VERIFY", -7);
defined("PAYMENT_STATUS_WAIT") OR define("PAYMENT_STATUS_WAIT", -8);
defined("PAYMENT_STATUS_NOT_PAYED") OR define("PAYMENT_STATUS_NOT_PAYED", -9);
defined('PAYMENT_STATUSES') OR define('PAYMENT_STATUSES', [
    PAYMENT_STATUS_SUCCESS => 'پرداخت شده',
    PAYMENT_STATUS_FAILED => 'پرداخت ناموفق',
    PAYMENT_STATUS_WAIT_VERIFY => 'در انتظار تایید',
    PAYMENT_STATUS_WAIT => 'در انتظار پرداخت',
    PAYMENT_STATUS_NOT_PAYED => 'پرداخت نشده',
]);

// needed payment method constants
defined("METHOD_TYPE_WALLET") OR define("METHOD_TYPE_WALLET", 5);
defined("METHOD_TYPE_IN_PLACE") OR define("METHOD_TYPE_IN_PLACE", 6);
defined("METHOD_TYPE_RECEIPT") OR define("METHOD_TYPE_RECEIPT", 7);
defined("METHOD_TYPE_GATEWAY_BEH_PARDAKHT") OR define("METHOD_TYPE_GATEWAY_BEH_PARDAKHT", 1);
defined("METHOD_TYPE_GATEWAY_IDPAY") OR define("METHOD_TYPE_GATEWAY_IDPAY", 2);
defined("METHOD_TYPE_GATEWAY_MABNA") OR define("METHOD_TYPE_GATEWAY_MABNA", 3);
defined("METHOD_TYPE_GATEWAY_ZARINPAL") OR define("METHOD_TYPE_GATEWAY_ZARINPAL", 4);
defined("METHOD_TYPES") OR define("METHOD_TYPES", [
    METHOD_TYPE_GATEWAY_BEH_PARDAKHT => 'درگاه بانک - به پرداخت',
    METHOD_TYPE_GATEWAY_IDPAY => 'درگاه بانک - آیدی پی',
    METHOD_TYPE_GATEWAY_MABNA => 'درگاه بانک - پرداخت الکترونیک سپهر (مبنا)',
    METHOD_TYPE_GATEWAY_ZARINPAL => 'درگاه بانک - زرین پال',
]);

// deposit type codes
defined("DEPOSIT_TYPE_PAYED") OR define("DEPOSIT_TYPE_PAYED", "a1b2c3d4e5f6");

// return order statuses
defined("RETURN_ORDER_STATUS_CHECKING") OR define("RETURN_ORDER_STATUS_CHECKING", 1);
defined("RETURN_ORDER_STATUS_DENIED_BY_USER") OR define("RETURN_ORDER_STATUS_DENIED_BY_USER", 2);
defined("RETURN_ORDER_STATUS_ACCEPT") OR define("RETURN_ORDER_STATUS_ACCEPT", 3);
defined("RETURN_ORDER_STATUS_DENIED") OR define("RETURN_ORDER_STATUS_DENIED", 4);
defined("RETURN_ORDER_STATUS_SENDING") OR define("RETURN_ORDER_STATUS_SENDING", 5);
defined("RETURN_ORDER_STATUS_RECEIVED") OR define("RETURN_ORDER_STATUS_RECEIVED", 6);
defined("RETURN_ORDER_STATUS_MONEY_RETURNED") OR define("RETURN_ORDER_STATUS_MONEY_RETURNED", 7);

// comment statuses
defined("COMMENT_STATUS_NOT_READ") OR define("COMMENT_STATUS_NOT_READ", 0);
defined("COMMENT_STATUS_READ") OR define("COMMENT_STATUS_READ", 1);
defined("COMMENT_STATUS_REPLIED") OR define("COMMENT_STATUS_REPLIED", 2);
// comment conditions
defined("COMMENT_CONDITION_NOT_SET") OR define("COMMENT_CONDITION_NOT_SET", -1);
defined("COMMENT_CONDITION_REJECT") OR define("COMMENT_CONDITION_REJECT", 0);
defined("COMMENT_CONDITION_ACCEPT") OR define("COMMENT_CONDITION_ACCEPT", 1);

// contact us statuses
defined("CONTACT_STATUS_UNREAD") OR define("CONTACT_STATUS_UNREAD", 1);
defined("CONTACT_STATUS_READ") OR define("CONTACT_STATUS_READ", 2);

// complaint statuses
defined("COMPLAINT_STATUS_UNREAD") OR define("COMPLAINT_STATUS_UNREAD", 1);
defined("COMPLAINT_STATUS_READ") OR define("COMPLAINT_STATUS_READ", 2);

// time constants
defined("TIME_ACTIVATE_CODE") OR define("TIME_ACTIVATE_CODE", 180);

// tabbed slider predefined items
defined("SLIDER_TABBED_NEWEST") OR define("SLIDER_TABBED_NEWEST", 1);
defined("SLIDER_TABBED_MOST_SELLER") OR define("SLIDER_TABBED_MOST_SELLER", 2);
defined("SLIDER_TABBED_MOST_DISCOUNT") OR define("SLIDER_TABBED_MOST_DISCOUNT", 3);
// featured = اختصاصی - is_special in products
defined("SLIDER_TABBED_FEATURED") OR define("SLIDER_TABBED_FEATURED", 4);
// special = ویژه - from festivals
defined("SLIDER_TABBED_SPECIAL") OR define("SLIDER_TABBED_SPECIAL", 5);

// product orderings
defined("PRODUCT_ORDERINGS") OR define("PRODUCT_ORDERINGS", [
    1 => 'جدیدترین',
    12 => 'پربازدیدترین',
    16 => 'پرتخفیف ترین',
    4 => 'ارزان ترین',
    7 => 'گران ترین',
]);

// maximum store for addresses
defined("ADDRESS_MAX_COUNT") OR define("ADDRESS_MAX_COUNT", 10);

// default time format
defined("DEFAULT_TIME_FORMAT") OR define("DEFAULT_TIME_FORMAT", 'j F Y');

// archive tags time format
defined("ARCHIVE_TAGS_TIME_FORMAT") OR define("ARCHIVE_TAGS_TIME_FORMAT", 'F Y');

// default select option value
defined("DEFAULT_OPTION_VALUE") OR define("DEFAULT_OPTION_VALUE", -1);

// min warning stock value
defined("MINIMUM_WARNING_STOCK_VALUE") OR define("MINIMUM_WARNING_STOCK_VALUE", 4);

// max category level number
defined("MAX_CATEGORY_LEVEL") OR define("MAX_CATEGORY_LEVEL", 3);

// coupon code session key constant
defined("SESSION_APPLIED_COUPON_CODE") OR define("SESSION_APPLIED_COUPON_CODE", 'session_applied_coupon_code');
// post price session key constant
defined("SESSION_APPLIED_POST_PRICE") OR define("SESSION_APPLIED_POST_PRICE", 'session_applied_post_price');