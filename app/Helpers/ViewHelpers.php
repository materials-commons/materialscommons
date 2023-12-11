<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

if (!function_exists("setActiveNav")) {
    function setActiveNav($path)
    {
        return Request::is('app/'.$path.'*') ? 'active' : '';
    }
}

if (!function_exists("setActiveNavByName")) {
    function setActiveNavByName($name)
    {
        return Request::routeIs($name.'*') ? 'active' : '';
    }
}

if (!function_exists("setActiveNavByExactName")) {
    function setActiveNavByExactName($name)
    {
        return Request::routeIs($name) ? 'active' : '';
    }
}

if (!function_exists("setActiveNavByOneOf")) {
    function setActiveNavByOneOf($names)
    {
        foreach ($names as $name) {
            if (Request::routeIs($name.'*')) {
                return 'active';
            }
        }

        return '';
    }
}

if (!function_exists("getPreviousRouteName")) {
    function getPreviousRouteName()
    {
        return app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
    }
}

if (!function_exists("getPreviousRoute")) {
    function getPreviousRoute()
    {
        return url()->previous();
    }
}

if (!function_exists("slugify")) {
    function slugify($what): string
    {
        return Str::slug($what);
    }
}

if (!function_exists("line_count")) {
    function line_count($str, $min = 0)
    {
        $lines = preg_split('/\n|\r/', $str);
        $count = count($lines);
        if ($count < $min) {
            return $min;
        }

        return $count;
    }
}