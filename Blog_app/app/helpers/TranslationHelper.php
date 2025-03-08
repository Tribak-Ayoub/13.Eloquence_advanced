<?php

if (!function_exists('translate')) {
    function translate($key, $locale = null)
    {
        return __($key, [], $locale ?? app()->getLocale());
    }
}