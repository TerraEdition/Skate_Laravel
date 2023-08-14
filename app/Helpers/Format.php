<?php

namespace App\Helpers;

class Format
{
    # for description input before save / update database (WYSIWYG Text Area)
    public static function clean($string)
    {
        if (empty($string)) {
            return;
        }
        # sanitize text input
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    # for description input before send to frontend (WYSIWYG Text Area)
    public static function unclean($string)
    {
        if (empty($string)) {
            return;
        }
        # unsanitize text input
        return htmlspecialchars_decode($string, ENT_QUOTES);
    }

    # for key searching (like query)
    public static function clean_char_search($keyword = '')
    {
        if ($keyword == "'") {
            return '';
        } else {
            return addslashes(trim($keyword));
        }
    }

    # for nominal money
    public static function money($nominal, $is_decimal = false, $rounded = false)
    {
        $config = Config::get('all_config');

        $decimal_digit = $config->decimal_digit;
        if ($config->decimal_active == '1' && $decimal_digit > 0) {
            $nominal = (float)$nominal;
            $arr_nominal = explode('.', $nominal);
            if ($is_decimal) {
                if (isset($arr_nominal[1])) {
                    if ($arr_nominal[1] == 0) {
                        $decimal_digit = 0;
                    } else if ($rounded) {
                        $decimal_digit = strlen($arr_nominal[1]);
                    }
                } else {
                    $decimal_digit = 0;
                }
            }

            $res = number_format($nominal, $decimal_digit, $config->decimal_separator, $config->thousand_separator);
        } else {
            $res = number_format($nominal, 0, "", $config->thousand_separator);
        }

        return $res;
    }
}
