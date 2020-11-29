<?php

use Sim\I18n\ISOLanguageCodes;

function local_number(?string $str): string
{
    $lang = \config()->get('i18n.language');
    $str = (string)$str;
    if (
        !empty($lang) &&
        in_array(
            $lang,
            [
                ISOLanguageCodes::LANGUAGE_PERSIAN_FARSI,
                ISOLanguageCodes::LANGUAGE_ENGLISH,
                ISOLanguageCodes::LANGUAGE_ARABIC
            ]
        )
    ) {
        switch (strtolower($lang)) {
            case ISOLanguageCodes::LANGUAGE_PERSIAN_FARSI:
                $str = \Sim\Utils\StringUtil::toPersian($str);
                break;
            case ISOLanguageCodes::LANGUAGE_ARABIC:
                $str = \Sim\Utils\StringUtil::toArabic($str);
                break;
            default:
                $str = \Sim\Utils\StringUtil::toEnglish($str);
                break;
        }
    }

    return $str;
}