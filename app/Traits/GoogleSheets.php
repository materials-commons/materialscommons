<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
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

    private function getGoogleSheetsId($url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        $pathParts = explode("/", $path);

        if (count($pathParts) < 4) {
            return null;
        }

        return $pathParts[3];
    }

    private function getGoogleSheetsUrlFromId($id): ?string
    {
        if (blank($id)) {
            return null;
        }

        return "https://docs.google.com/spreadsheets/d/{$id}/edit?usp=sharing";
    }

    private function downloadGoogleSheet($sheetUrl): string
    {
        $filename = uniqid().'.xlsx';
        return $this->downloadGoogleSheetToNamedFile($sheetUrl, $filename);
    }

    private function downloadGoogleSheetToNamedFile($sheetUrl, $filename): string
    {
        if (Storage::disk('mcfs')->exists('__sheets/'.$filename)) {
            return Storage::disk('mcfs')->path('__sheets/'.$filename);
        }
        $sheetUrl = $this->cleanupGoogleSheetUrl($sheetUrl);
        @Storage::disk('mcfs')->makeDirectory('__sheets');
        @chmod(Storage::disk('mcfs')->path('__sheets'), 0777);
        $filePath = Storage::disk('mcfs')->path('__sheets/'.$filename);

        // Since this is an url we need to download it.
        $command = "curl -o \"{$filePath}\" -L {$sheetUrl}/export?format=xlsx";
        $process = Process::fromShellCommandline($command);
        $process->run();
        return $filePath;
    }

    private function deleteCachedGoogleSheet($id): void
    {
        Storage::disk('mcfs')->delete("__sheets/{$id}.xlsx");
    }
}
