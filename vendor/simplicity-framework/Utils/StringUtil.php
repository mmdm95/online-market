<?php

namespace Sim\Utils;


class StringUtil
{
    const RS_NUMBER = 0x1;
    const RS_LOWER_CHAR = 0x2;
    const RS_UPPER_CHAR = 0x4;
    const RS_ALL = StringUtil::RS_NUMBER | StringUtil::RS_LOWER_CHAR | StringUtil::RS_UPPER_CHAR;

    const CONVERT_NUMBERS = 0x1;
    const CONVERT_CHARACTERS = 0x2;
    const CONVERT_ALL = StringUtil::CONVERT_NUMBERS | StringUtil::CONVERT_CHARACTERS;

    /**
     * @var array
     */
    private static $persian_decimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');

    /**
     * @var array
     */
    private static $arabic_decimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');

    /**
     * @var array
     */
    private static $persian_numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /**
     * @var array
     */
    private static $arabic_numbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    /**
     * @var array
     */
    private static $english_numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    /**
     * @var array
     */
    private static $persian_special_characters = ['ا', 'گ', 'چ', 'پ', 'ژ', 'ه', 'ی'];

    /**
     * @var array
     */
    private static $arabic_special_characters = ['أ', 'ك', 'ج', 'ب', 'ز', 'ة', 'ي'];

    /**
     * generate_random_string constants
     *
     * @param $length
     * @param int $type
     * @return string
     */
    public static function randomString($length, $type = StringUtil::RS_ALL)
    {
        $charactersMap = [
            'number' => '0123456789',
            'lower' => 'abcdefghijklmnopqrstuvwxyz',
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ];

        $characters = '';
        $haveType = StringUtil::RS_ALL;

        if ($type & StringUtil::RS_NUMBER) {
            $characters .= $charactersMap['number'];
            $haveType = $haveType ^ StringUtil::RS_NUMBER;
        }
        if ($type & StringUtil::RS_LOWER_CHAR) {
            $characters .= $charactersMap['lower'];
            $haveType = $haveType ^ StringUtil::RS_LOWER_CHAR;
        }
        if ($type & StringUtil::RS_UPPER_CHAR) {
            $characters .= $charactersMap['upper'];
            $haveType = $haveType ^ StringUtil::RS_UPPER_CHAR;
        }
        if (StringUtil::RS_ALL ^ $haveType == 0) {
            $characters = $charactersMap['number'] . $charactersMap['lower'] . $charactersMap['upper'];
        }

        $charactersLength = \strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @see https://www.php.net/manual/en/function.uniqid.php#120123
     * @param int $lenght
     * @return bool|string
     * @throws \Exception
     */
    public static function uniqidReal($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (\function_exists("random_bytes")) {
            $bytes = \random_bytes(\ceil($lenght / 2));
        } elseif (\function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(\ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return \substr(\bin2hex($bytes), 0, $lenght);
    }

    /**
     * @param int $decimal
     * @param int $base - max value is 62
     * @return string
     */
    public static function baseNEncode(int $decimal, int $base): string
    {
        if (0 === $decimal) {
            return '0';
        }

        $s = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = \min(62, $base);
        $result = '';

        while ($decimal > 0) {
            $result = $s[$decimal % $len] . $result;
            $decimal = (int)($decimal / $len);
        }

        return $result;
    }

    /**
     * @param string $hashed_str
     * @param int $base - max value is 62
     * @return int|null
     */
    public static function baseNDecode(string $hashed_str, int $base)
    {
        $hashedStrLen = \strlen($hashed_str);
        if (empty($hashed_str) || 0 === $hashedStrLen) {
            return 0;
        }

        $s = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = \min(62, $base);
        $result = 0;

        for ($i = 0; $i < $hashedStrLen; $i++) {
            $p = \strpos($s, $hashed_str[$i]);
            if (false === $p || $p < 0 || $p >= $base) {
                return null;
            }
            $result += $p * \pow($len, $hashedStrLen - $i - 1);
        }

        return (int)$result;
    }

    /**
     * @param int $decimal
     * @return string
     */
    public static function base62Encode(int $decimal): string
    {
        return self::baseNEncode($decimal, 62);
    }

    /**
     * @param string $hashed_str
     * @return int|null
     */
    public static function base62Decode(string $hashed_str)
    {
        self::baseNDecode($hashed_str, 62);
    }

    /**
     * @param int $decimal
     * @return string
     */
    public static function base10Encode(int $decimal): string
    {
        return self::baseNEncode($decimal, 10);
    }

    /**
     * @param string $hashed_str
     * @return int|null
     */
    public static function base10Decode(string $hashed_str)
    {
        self::baseNDecode($hashed_str, 10);
    }

    /**
     * convert arabic and english numbers and arabic specific
     * characters to persian numbers and specific characters
     *
     * @param $str
     * @param int $convert_type
     * @return array|mixed
     */
    public static function toPersian($str, $convert_type = StringUtil::CONVERT_ALL)
    {
        if (StringUtil::CONVERT_NUMBERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (\is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toPersian($str[$k], $convert_type);
                }
                return $newArr;
            }

            if (\is_string($str) || \is_numeric($str)) {
                $str = \str_replace(self::$english_numbers, self::$persian_numbers, $str);
                $str = \str_replace(self::$arabic_numbers, self::$persian_numbers, $str);
                $str = \str_replace(self::$arabic_decimal, self::$persian_numbers, $str);
            }
        } elseif (StringUtil::CONVERT_CHARACTERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (\is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toPersian($v, $convert_type);
                }
                return $newArr;
            }

            if (\is_string($str) || \is_numeric($str)) {
                $str = \str_replace(self::$arabic_special_characters, self::$persian_special_characters, $str);
            }
        }

        return $str;
    }

    /**
     * convert persian and english numbers and persian specific
     * characters to arabic numbers and specific characters
     *
     * @param $str
     * @param int $convert_type
     * @return array|mixed
     */
    public static function toArabic($str, $convert_type = StringUtil::CONVERT_ALL)
    {
        if (StringUtil::CONVERT_NUMBERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (\is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toArabic($str[$k], $convert_type);
                }
                return $newArr;
            }

            if (\is_string($str) || \is_numeric($str)) {
                $str = \str_replace(self::$english_numbers, self::$arabic_numbers, $str);
                $str = \str_replace(self::$persian_numbers, self::$arabic_numbers, $str);
                $str = \str_replace(self::$persian_decimal, self::$arabic_numbers, $str);
            }
        } elseif (StringUtil::CONVERT_CHARACTERS & $convert_type || StringUtil::CONVERT_ALL & $convert_type) {
            if (\is_array($str)) {
                $newArr = [];
                foreach ($str as $k => $v) {
                    $newArr[$k] = self::toArabic($v, $convert_type);
                }
                return $newArr;
            }

            if (\is_string($str) || \is_numeric($str)) {
                $str = \str_replace(self::$persian_special_characters, self::$arabic_special_characters, $str);
            }
        }

        return $str;
    }

    /**
     * Convert numbers from arabic and persian to english numbers
     *
     * @param $str
     * @return array|mixed
     */
    public static function toEnglish($str)
    {
        if (\is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = self::toEnglish($v);
            }
            return $newArr;
        }

        if (\is_string($str) || \is_numeric($str)) {
            $str = \str_replace(self::$arabic_numbers, self::$english_numbers, $str);
            $str = \str_replace(self::$persian_numbers, self::$english_numbers, $str);
            $str = \str_replace(self::$persian_decimal, self::$english_numbers, $str);
            $str = \str_replace(self::$arabic_decimal, self::$english_numbers, $str);
        }

        return $str;
    }

    /**
     * @see https://stackoverflow.com/a/16239689/12154893
     * @param string $string
     * @param int $length
     * @param string $delimiter
     * @return string
     */
    public static function truncate(string $string, int $length, string $delimiter = '...'): string
    {
        return \mb_strimwidth($string, 0, $length, $delimiter);
    }

    /**
     * @see https://stackoverflow.com/a/16239689/12154893
     * @param string $string
     * @param int $length
     * @param string $delimiter
     * @return string
     */
    public static function truncate_word(string $string, int $length, string $delimiter = '…'): string
    {
        // we don't want new lines in our preview
        $text_only_spaces = \preg_replace('/\s+/', ' ', $string);

        // truncates the text
        $text_truncated = \mb_substr($text_only_spaces, 0, \mb_strpos($text_only_spaces, ' ', $length));

        // prevents last word truncation
        $preview = \trim(\mb_substr($text_truncated, 0, \mb_strrpos($text_truncated, ' '))) . $delimiter;

        return $preview;
    }

    /**
     * @param string $text
     * @return string
     */
    public static function slugify(string $text): string
    {
        $separator = '-';
        $text = \mb_strtolower($text);
        $text = \preg_replace('/[^a-z0-9۰-۹ا-ی\s]+/ui', '', $text);
        $text = \str_replace(' ', $separator, $text);
        return \trim($text, $separator);
    }

    /**
     * @param $text
     * @return array|string|string[]|null
     */
    public static function replaceBadUTF8Chars($text)
    {
        if (\is_array($text)) {
            $newArr = [];
            foreach ($text as $k => $v) {
                $newArr[$k] = replaceBadUTF8Chars($text[$k]);
            }
            return $newArr;
        }

        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]               # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]    # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2} # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3} # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                      # ...one or more times
  )
| ( [\x80-\xBF] )                 # invalid byte in range 10000000 - 10111111
| ( [\xC0-\xFF] )                 # invalid byte in range 11000000 - 11111111
/x
END;
        if (!function_exists('utf8replacer')) {
            function utf8replacer($captures)
            {
                if ($captures[1] != "") {
                    // Valid byte sequence. Return unmodified.
                    return $captures[1];
                } elseif ($captures[2] != "") {
                    // Invalid byte of the form 10xxxxxx.
                    // Encode as 11000010 10xxxxxx.
                    return "\xC2" . $captures[2];
                } else {
                    // Invalid byte of the form 11xxxxxx.
                    // Encode as 11000011 10xxxxxx.
                    return "\xC3" . \chr(\ord($captures[3]) - 64);
                }
            }
        }

        return \preg_replace_callback($regex, "utf8replacer", $text);
    }

    /**
     * @see https://github.com/sallaizalan/mb-substr-replace/blob/master/mbsubstrreplace.php
     *
     * @param string $string
     * @param string $replacement
     * @param int $start
     * @param int $length
     * @return string
     */
    public static function mbSubstrReplace($string, $replacement, $start, $length = 1)
    {
        $startString = mb_substr($string, 0, $start, "UTF-8");
        $endString = mb_substr($string, $start + $length, mb_strlen($string), "UTF-8");

        $out = $startString . $replacement . $endString;

        return $out;
    }
}