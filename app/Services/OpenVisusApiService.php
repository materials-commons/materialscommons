<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenVisusApiService
{
    public static function displayDataset($visusDataset)
    {
        $url = config('visus.url').$visusDataset;
        $response = Http::get($url);
        if ($response->failed()) {
            return null;
        }

        return $response->body();
    }

    public static function visusDatasetUrl($visusDataset)
    {
        return config('visus.url').$visusDataset;
    }
}