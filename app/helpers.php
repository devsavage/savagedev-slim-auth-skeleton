<?php

if(!function_exists("env"))
{
    function env($key, $default = null) {
        $value = getenv($key);

        if($value === false) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('full_uri')) {
    function full_uri($path) {
        return env("APP_URL") . $path;
    }
}
