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
    } elseif (Request::routeIs('projects.datasets.*')) {
        return makeHelpUrl("publishing");
    } elseif (Request::routeIs('projects.globus.*')) {
        return makeHelpUrl("globus");
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

function helpTitle()
{
    if (Request::routeIs('projects.activities.*')) {
        return "Processes";
    } elseif (Request::routeIs('projects.experiments.activities.*')) {
        return "Processes";
    } elseif (Request::routeIs('projects.entities.*')) {
        return "Samples";
    } elseif (Request::routeIs('projects.experiments.*')) {
        return "Experiments";
    } elseif (Request::routeIs('projects.datasets.*')) {
        return "Publishing";
    } elseif (Request::routeIs('projects.globus.*')) {
        return "Globus";
    } elseif (Request::routeIs('projects.*')) {
        return "Projects";
    } else {
        return "Getting Started";
    }
}

