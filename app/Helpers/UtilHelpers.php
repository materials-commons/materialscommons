<?php

use Illuminate\Pipeline\Pipeline;

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
