<?php

namespace App\Http\Controllers\Auth;

use function parse_url;
use function session;
use function url;
use const PHP_URL_PATH;

trait SessionRoutes
{
    public function setPreviousRoutePathSession($previousPath = null): void
    {
        if (is_null($previousPath)) {
            $previous = url()->previous();
            $previousPath = parse_url($previous, PHP_URL_PATH);
        }

        session(['url.previous' => $previousPath]);
    }

    public function getPreviousRoutePathSession()
    {
        if (session()->exists('url.previous')) {
            return session()->get('url.previous');
        }

        return "/";
    }
}