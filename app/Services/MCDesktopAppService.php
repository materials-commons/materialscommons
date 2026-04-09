<?php

namespace App\Services;

use App\DTO\MCDesktopClientConnection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MCDesktopAppService
{
    public static function submitTestUpload($desktopId)
    {
        $resp = Http::get(self::ApiUrl("/submit-test-upload/{$desktopId}"));
        return $resp->ok();
    }

    public static function getDesktopProjectDirListing($desktopId, $projectId, $userId, $projectPath)
    {
        try {
            $resp = Http::asForm()->post(self::ApiUrl("/list-client-project-dir/{$desktopId}/{$projectId}"), [
                'project_path' => $projectPath,
                'user_id' => $userId,
            ]);
            if (!$resp->ok()) {
                return collect();
            }
            return collect($resp->json()["files"])->map(fn($item) => (object) $item);
        } catch (\Exception $e) {
            return collect();
        }
    }

    public static function getActiveDesktopClientsForUser($userId): Collection
    {
        try {
            $resp = Http::get(self::ApiUrl("/list-clients-for-user/{$userId}"));
            if (!$resp->ok()) {
                return collect();
            }
        } catch (\Exception $e) {
            return collect();
        }

        return MCDesktopClientConnection::fromArray($resp->json());
    }

    public static function getActiveDesktopClientsForUserProject($userId, $projectId): Collection
    {
        return self::getActiveDesktopClientsForUser($userId)->filter(
            fn($client) => MCDesktopAppService::clientIsConnectedToProject($client, $projectId)
        );
    }

    public static function clientIsConnectedToProject($client, $projectId): bool
    {
        return $client->projectIds->contains(function ($id) use ($projectId) {
            return $id == $projectId;
        });
    }

    private static function ApiUrl(string $path): string
    {

        $apiurlBase = config('mcdesktop.server.url');
        return "{$apiurlBase}{$path}";
    }
}
