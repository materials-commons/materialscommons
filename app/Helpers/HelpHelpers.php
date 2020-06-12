<?php

use Illuminate\Support\Facades\Request;

if (!function_exists("helpRoutes")) {
    function helpRoutes()
    {
        return [
            ['route' => 'projects.activities.*', 'help' => 'reference/processes', 'title' => 'Processes'],
            ['route' => 'projects.experiments.activities.*', 'help' => 'reference/processes', 'title' => 'Processes'],
            ['route' => 'projects.entities.*', 'help' => 'reference/samples', 'title' => 'Samples'],
            ['route' => 'projects.experiments.*', 'help' => 'reference/experiments', 'title' => 'Experiments'],
            ['route' => 'projects.datasets.*', 'help' => 'reference/publishing', 'title' => 'Publishing'],
            ['route' => 'projects.globus.*', 'help' => 'reference/globus', 'title' => 'Globus'],
            ['route' => 'projects.files.*', 'help' => 'reference/files', 'title' => 'Files'],
            ['route' => 'projects.folders.*', 'help' => 'reference/files', 'title' => 'Files'],
            ['route' => 'projects.*', 'help' => 'reference/projects', 'title' => 'Projects'],
            ['route' => 'accounts.*', 'help' => 'reference/account', 'title' => 'Account'],
        ];
    }
}

if (!function_exists("helpUrl")) {
    function helpUrl()
    {
        foreach (helpRoutes() as $helpRoute) {
            if (Request::routeIs($helpRoute['route'])) {
                return makeHelpUrl($helpRoute['help']);
            }
        }
        return makeHelpUrl("getting-started");
    }
}

if (!function_exists("helpGettingStarted")) {
    function helpGettingStarted()
    {
        return makeHelpUrl("getting-started");
    }
}

if (!function_exists("makeHelpUrl")) {
    function makeHelpUrl($doc)
    {
        $base = config('help.site');

        return "{$base}/{$doc}/";
    }
}

if (!function_exists("helpTitle")) {
    function helpTitle()
    {
        foreach (helpRoutes() as $helpRoute) {
            if (Request::routeIs($helpRoute['route'])) {
                return $helpRoute['title'];
            }
        }
        return "Getting Started";
    }
}
