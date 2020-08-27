<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class PathHelpers
{
    /**
     * Normalize a directory path name
     *
     * @param $path
     *
     * @return string|string[]|null
     */
    static function normalizePath($path)
    {
        $patterns = ['~/{2,}~', '~/(\./)+~', '~([^/\.]+/(?R)*\.{2,}/)~', '~\.\./~'];
        $replacements = ['/', '/', '', ''];

        if ($path === "/") {
            return "/";
        }

        return Str::of(preg_replace($patterns, $replacements, $path))->rtrim('/')->__toString();
    }
}