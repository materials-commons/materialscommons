<?php

use Illuminate\Support\Facades\Request;

function helpUrl()
{
    if (Request::routeIs('projects.activities.*')) {
        return makeHelpUrl("processes");
    } elseif (Request::routeIs('projects.experiments.activities.*')) {
        return makeHelpUrl("processes");
    } elseif (Request::routeIs('projects.entities.*')) {
        return makeHelpUrl("samples");
    } elseif (Request::routeIs('projects.experiments.*')) {
        return makeHelpUrl("experiments");
    } elseif (Request::routeIs('projects.*')) {
        return makeHelpUrl("projects");
    } else {
        return makeHelpUrl("getting-started");
    }
}

function makeHelpUrl($doc)
{
    $base = config('help.site');
    return "{$base}/{$doc}";
}

