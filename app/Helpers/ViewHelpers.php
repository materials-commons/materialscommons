<?php

use Illuminate\Support\Facades\Request;

function setActiveNav($path)
{
    return Request::is('app/'.$path.'*') ? 'active' : '';
}

function setActiveNavByName($name)
{
    return Request::routeIs($name.'*') ? 'active' : '';
}

function setActiveNavByExactName($name)
{
    return Request::routeIs($name) ? 'active' : '';
}

function setActiveNavByOneOf($names)
{
    foreach ($names as $name) {
        if (Request::routeIs($name.'*')) {
            return 'active';
        }
    }

    return '';
}

function getPreviousRouteName()
{
    return app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
}

function getPreviousRoute()
{
    return url()->previous();
}
