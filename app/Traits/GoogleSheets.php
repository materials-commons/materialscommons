<?php

namespace App\Traits;

use Illuminate\Support\Str;
use function blank;
use function dirname;
use function parse_url;
use const PHP_URL_HOST;
use const PHP_URL_PATH;

trait GoogleSheets
{
    private function cleanupGoogleSheetUrl($url): ?string
    {
        if (blank($url)) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);

        if (Str::endsWith($path, "/edit")) {
            // Remove /edit from the end of the url
            $path = dirname($path);
        }

        $host = parse_url($url, PHP_URL_HOST);

        // $path starts with a slash (/), so we don't add one to separate host and path
        // when constructing the url.
        return "https://{$host}{$path}";
    }
}