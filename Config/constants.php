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
defined("APP_VERSION") OR define("APP_VERSION", "0.1.4");

/***************************************
 * You can add your constants here
 ***************************************/

// default language
defined("APP_LANGUAGE") OR define("APP_LANGUAGE", ISOLanguageCodes::LANGUAGE_ENGLISH);

// some defaults for routes
defined("NOT_FOUND_ADMIN") OR define("NOT_FOUND_ADMIN", 'admin.page.notfound');
defined("NOT_FOUND") OR define("NOT_FOUND", 'page.notfound');
defined("SERVER_ERROR") OR define("SERVER_ERROR", 'page.servererror');

// title delimiter
defined("TITLE_DELIMITER") OR define("TITLE_DELIMITER", ' | ');

// publish or true in database
defined("DB_YES") OR define("DB_YES", 1);
defined("DB_NO") OR define("DB_NO", 0);

// default placeholders
defined("PLACEHOLDER_IMAGE") OR define("PLACEHOLDER_IMAGE", __DIR__ . '/../public/be/images/file-icons/image-placeholder.png');
defined("PLACEHOLDER_VIDEO") OR define("PLACEHOLDER_VIDEO", __DIR__ . '/../public/be/images/file-icons/video-placeholder.png');

// public access directory name
defined("PUBLIC_ACCESS_DIR") OR define("PUBLIC_ACCESS_DIR", 'public-access');

// define all roles
defined("ROLE_DEVELOPER") OR define("ROLE_DEVELOPER", 'developer');
defined("ROLE_SUPER_USER") OR define("ROLE_SUPER_USER", 'super_user');
defined("ROLE_ADMIN") OR define("ROLE_ADMIN", 'admin');
defined("ROLE_USER") OR define("ROLE_USER", 'user');

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

// contact us statuses
defined("CONTACT_STATUS_UNREAD") OR define("CONTACT_STATUS_UNREAD", 1);
defined("CONTACT_STATUS_READ") OR define("CONTACT_STATUS_READ", 2);

// tabbed slider predefined items
defined("SLIDER_TABBED_NEWEST") OR define("SLIDER_TABBED_NEWEST", 1);
defined("SLIDER_TABBED_MOST_SELLER") OR define("SLIDER_TABBED_MOST_SELLER", 2);
defined("SLIDER_TABBED_MOST_DISCOUNT") OR define("SLIDER_TABBED_MOST_DISCOUNT", 3);
// featured = اختصاصی - is_special in products
defined("SLIDER_TABBED_FEATURED") OR define("SLIDER_TABBED_FEATURED", 4);
// special = ویژه - from festivals
defined("SLIDER_TABBED_SPECIAL") OR define("SLIDER_TABBED_SPECIAL", 5);