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

if (!function_exists("setActiveNavByHasParam")) {
    function setActiveNavByHasParam($name): string
    {
        return Request::has($name) ? 'active' : '';
    }
}

if (!function_exists("setActiveNavByTabParam")) {
    function setActiveNavByTabParam($value): string
    {
        $tabValue = Request::input('tab', null);
        if (is_null($tabValue)) {
            return '';
        }

        if ($tabValue == $value) {
            return 'active';
        }

        return '';
    }
}

if (!function_exists("hasTabParam")) {
    function hasTabParam($value): bool
    {
        $tabValue = Request::input('tab', null);
        if (is_null($tabValue)) {
            return false;
        }

        if ($tabValue == $value) {
            return true;
        }

        return false;
    }
}

if (!function_exists("setActiveNavByParam")) {
    function setActiveNavByParam($param, $value): string
    {
        $paramValue = Request::input($param, null);
        ray("  setActiveNavByParam {$param} = {$paramValue}, looking for {$value}");
        if (is_null($paramValue)) {
            return '';
        }

        if ($paramValue == $value) {
            return 'active';
        }

        return '';
    }
}

if (!function_exists("hasParam")) {
    function hasParam($param, $value): bool
    {
        $paramValue = Request::input($param, null);
        if (is_null($paramValue)) {
            return false;
        }

        if ($paramValue == $value) {
            return true;
        }

        return false;
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