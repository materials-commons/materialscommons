<?php

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Carbon;

if (!function_exists("pipe")) {
    function pipe()
    {
        return app(Pipeline::class);
    }
}

if (!function_exists("formatBytes")) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}

if (!function_exists("trashExpiration")) {
    function trashExpiration()
    {
        return Carbon::now()->subDays(config('trash.expires_in_days'));
    }
}

if (!function_exists("trashExpirationInFuture")) {
    function trashExpirationInFuture($days = 1)
    {
        return Carbon::now()->subDays(config('trash.expires_in_days') + $days);
    }
}
