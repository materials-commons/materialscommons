<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use function config;
use function urlencode;

class CrossrefApiService
{
    public static function getCitationForDOI($doi)
    {
        $doiEncoded = urlencode($doi);
        $mailto = config('doi.crossref.mailto');
        $resp = Http::get("https://api.crossref.org/works/{$doiEncoded}?mailto={$mailto}");
        if ($resp->successful()) {
            return $resp->json();
        }
        return null;
    }
}