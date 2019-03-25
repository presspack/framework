<?php


use Presspack\Framework\Support\Facades\Strings;

if (! function_exists('_s')) {
    function _s($string)
    {
        return Strings::get($string);
    }
}
