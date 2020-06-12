<?php

use Illuminate\Support\Facades\Request;

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