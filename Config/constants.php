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
defined("APP_VERSION") OR define("APP_VERSION", "0.1.1");

/***************************************
 * You can add your constants here
 ***************************************/

// default language
defined("APP_LANGUAGE") OR define("APP_LANGUAGE", ISOLanguageCodes::LANGUAGE_ENGLISH);

// some defaults for routes
defined("NOT_FOUND_ADMIN") OR define("NOT_FOUND_ADMIN", 'admin.page.notfound');
defined("NOT_FOUND") OR define("NOT_FOUND", 'page.notfound');
defined("SERVER_ERROR") OR define("SERVER_ERROR", 'page.servererror');

// publish or true in database
defined("DB_YES") OR define("DB_YES", 1);
defined("DB_NO") OR define("DB_NO", 0);

// default placeholders
defined("PLACEHOLDER_IMAGE") OR define("PLACEHOLDER_IMAGE", __DIR__ . '/../public/be/images/file-icons/image-placeholder.png');
defined("PLACEHOLDER_VIDEO") OR define("PLACEHOLDER_VIDEO", __DIR__ . '/../public/be/images/file-icons/video-placeholder.png');

// public access directory name
defined("PUBLIC_ACCESS_DIR") OR define("PUBLIC_ACCESS_DIR", 'public-access');