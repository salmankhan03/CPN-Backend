<?php

if (!function_exists('uniqueProfileUrl')) {
    function uniqueProfileUrl($length_of_string)
    {
        $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $mix = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $code1 = substr(str_shuffle($alpha), 0, $length_of_string);
        $code2 = substr(str_shuffle($mix), 0, $length_of_string);
        return $code1 . $code2;
    }
}
