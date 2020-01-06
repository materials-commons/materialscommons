<?php

use Illuminate\Support\Facades\Request;

function helpRoutes()
{
    return [
        ['route' => 'projects.activities.*', 'help' => 'processes', 'title' => 'Processes'],
        ['route' => 'projects.experiments.activities.*', 'help' => 'processes', 'title' => 'Processes'],
        ['route' => 'projects.entities.*', 'help' => 'samples', 'title' => 'Samples'],
        ['route' => 'projects.experiments.*', 'help' => 'experiments', 'title' => 'Experiments'],
        ['route' => 'projects.datasets.*', 'help' => 'publishing', 'title' => 'Publishing'],
        ['route' => 'projects.globus.*', 'help' => 'globus', 'title' => 'Globus'],
        ['route' => 'projects.files.*', 'help' => 'files', 'title' => 'Files'],
        ['route' => 'projects.folders.*', 'help' => 'files', 'title' => 'Files'],
        ['route' => 'projects.*', 'help' => 'projects', 'title' => 'Projects'],
        ['route' => 'accounts.*', 'help' => 'account', 'title' => 'Account'],
    ];
}

function helpUrl()
{
    foreach (helpRoutes() as $helpRoute) {
        if (Request::routeIs($helpRoute['route'])) {
            return makeHelpUrl($helpRoute['help']);
        }
    }
    return makeHelpUrl("getting-started");
}

function makeHelpUrl($doc)
{
    $base = config('help.site');

    return "{$base}/{$doc}";
}

function helpTitle()
{
    foreach (helpRoutes() as $helpRoute) {
        if (Request::routeIs($helpRoute['route'])) {
            return $helpRoute['title'];
        }
    }
    return "Getting Started";
}

