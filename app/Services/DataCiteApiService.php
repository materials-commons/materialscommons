<?php

namespace App\Services;

use App\Models\Dataset;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use function config;

class DataCiteApiService
{
    public static function getDatasetCitationsCount(Dataset $dataset): int
    {
        if (is_null($dataset->published_at)) {
            return 0;
        }

        if (is_null($dataset->doi)) {
            return 0;
        }

        $doi = Str::replace("doi:", "", $dataset->doi);
        $response = Http::get(config('doi.service_url')."/{$doi}",);
        if (!$response->ok()) {
            return 0;
        }

        return $response->json()["data"]["attributes"]["citationCount"];
    }
}