<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\RobotsMiddleware\RobotsMiddleware;
use Symfony\Component\HttpFoundation\Response;

class Robots extends RobotsMiddleware
{
    protected function shouldIndex(Request $request)
    {
        $routeName = $request->route()->getName();
        if (!Str::startsWith($routeName, "public")) {
            // All public pages start with route name "public". If this isn't public then
            // tell robot not to index.
            return "none";
        }

        if ($routeName == "public.datasets.show") {
            return "nofollow";
        }

        if ($routeName == "public.datasets.overview.show") {
            return "nofollow";
        }

        if ($routeName == "public.datasets.download_zipfile") {
            return "none";
        }

        if ($routeName == "public.datasets.download_file") {
            return "none";
        }

        if ($routeName == "public.datasets.download_globus") {
            return "none";
        }

        if ($routeName == "public.openvisus.index") {
            return "all";
        }

        if ($routeName == "public.datasets.communities.index") {
            return "nofollow";
        }

        if ($routeName == "public.datasets.index") {
            return "all";
        }

        return "none";
    }
}
