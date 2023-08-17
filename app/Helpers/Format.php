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
}
