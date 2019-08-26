<?php

namespace App\Helpers;

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
        $patterns     = ['~/{2,}~', '~/(\./)+~', '~([^/\.]+/(?R)*\.{2,}/)~', '~\.\./~'];
        $replacements = ['/', '/', '', ''];

        return preg_replace($patterns, $replacements, $path);
    }
}