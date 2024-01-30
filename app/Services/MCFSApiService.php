<?php

namespace App\Services;

use App\Models\TransferRequest;
use Illuminate\Support\Facades\Http;

class MCFSApiService
{
    public static function getTransferRequestStatus(TransferRequest $transferRequest)
    {
        $resp = Http::get(self::ApiUrl("/transfer/{$transferRequest->uuid}/status"));
        if (!$resp->ok()) {
            return null;
        }

        return $resp->json();
    }

    private static function ApiUrl(string $path): string
    {
        $apiurlBase = config('globus.mcfs.url');
        return "{$apiurlBase}${path}";
    }
}