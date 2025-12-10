<?php

namespace App\Services;

use App\DTO\MCDesktopClientConnection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MCDesktopAppService
{
    public static function getActiveDesktopClientsForUser($userId): Collection
    {
        $resp = Http::get(self::ApiUrl("/list-clients-for-user/{$userId}"));
        if (!$resp->ok()) {
            return collect();
        }

        return MCDesktopClientConnection::fromArray($resp->json());
    }

    public static function getActiveDesktopClientsForUserProject($userId, $projectId) {
        return collect(
            self::getActiveDesktopClientsForUser($userId)->filter(function ($client) use ($projectId) {
                return $client->projectIds->contains(function ($id) use ($projectId) {
                    return $id == $projectId;
                });
            })
        );
    }

    private static function ApiUrl(string $path): string
    {

        $apiurlBase = config('mcdesktop.server.url');
        return "{$apiurlBase}{$path}";
    }
}
