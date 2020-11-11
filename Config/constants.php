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
defined("APP_VERSION") OR define("APP_VERSION", "0.1.0");

/***************************************
 * You can add your constants here
 ***************************************/

defined("APP_LANGUAGE") OR define("APP_LANGUAGE", ISOLanguageCodes::LANGUAGE_ENGLISH);

defined("NOT_FOUND_ADMIN") OR define("NOT_FOUND_ADMIN", 'admin.page.notfound');
defined("NOT_FOUND") OR define("NOT_FOUND", 'page.notfound');

defined("SERVER_ERROR") OR define("SERVER_ERROR", 'page.servererror');

