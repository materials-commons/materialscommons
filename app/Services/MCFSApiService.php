<?php

namespace App\Services;

use App\DTO\MCFSTransferRequestStatus;
use App\Models\TransferRequest;
use Illuminate\Support\Facades\Http;

class MCFSApiService
{
    public static function getTransferRequestStatus(TransferRequest $transferRequest)
    {
        $resp = Http::get(self::ApiUrl("/transfers/{$transferRequest->uuid}/status"));
        if (!$resp->ok()) {
            return null;
        }

        return new MCFSTransferRequestStatus($resp->json());
    }

    public static function getStatusAllTransferRequests()
    {
        $resp = Http::get(self::ApiUrl("/transfers"));
        if (!$resp->ok()) {
            return collect();
        }

        return MCFSTransferRequestStatus::fromArray($resp->json());
    }

    private static function ApiUrl(string $path): string
    {
        $apiurlBase = config('mcfs.url');
        return "{$apiurlBase}${path}";
    }
}