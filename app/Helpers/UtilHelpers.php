<?php

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

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

if (!function_exists("uuid")) {
    function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}

if (!function_exists("randomWords")) {
    function randomWords($words = 1, $length = 6)
    {
        $string = '';
        for ($o = 1; $o <= $words; $o++) {
            $vowels = array("a", "e", "i", "o", "u");
            $consonants = array(
                'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
                'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
            );

            $word = '';
            for ($i = 1; $i <= $length; $i++) {
                $word .= $consonants[rand(0, 19)];
                $word .= $vowels[rand(0, 4)];
            }
            $string .= mb_substr($word, 0, $length);
            $string .= "-";
        }
        return mb_substr($string, 0, -1);
    }
}