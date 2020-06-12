<?php

use Illuminate\Support\Facades\Request;

if (!function_exists("errorsBannersExist")) {
    function setActiveNav($path)
    {
        return Request::is('app/'.$path.'*') ? 'active' : '';
    }
}

if (!function_exists("errorsBannersExist")) {
    function setActiveNavByName($name)
    {
        return Request::routeIs($name.'*') ? 'active' : '';
    }
}

if (!function_exists("errorsBannersExist")) {
    function setActiveNavByExactName($name)
    {
        return Request::routeIs($name) ? 'active' : '';
    }
}

if (!function_exists("errorsBannersExist")) {
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

if (!function_exists("errorsBannersExist")) {
    function getPreviousRouteName()
    {
        return app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
    }
}

if (!function_exists("errorsBannersExist")) {
    function getPreviousRoute()
    {
        return url()->previous();
    }
}