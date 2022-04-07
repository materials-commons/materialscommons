<?php

namespace App\Services;

use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\Project;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use function config;
use function simplexml_load_file;

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
        $url = config('visus.url');
        if (Str::endsWith($url, "/")) {
            return $url.$visusDataset;
        }

        return $url."/{$visusDataset}";
    }

    public static function addDatasetToOpenVisus(Dataset $dataset)
    {
        OpenVisusApiService::addToOpenVisusConfigFile($dataset->uuid);
    }

    private static function addToOpenVisusConfigFile($uuid)
    {
        Cache::lock("visus")->get(function() use ($uuid) {
            $configPath = config('visus.idx_path')."/datasets.config";
            $doc = simplexml_load_file($configPath);
            $datasets = $doc->datasets;
            $newData = $datasets->addChild('dataset');
            $newData->addAttribute('name', $uuid);
            $newData->addAttribute('url', "/datasets/{$uuid}/visus.idx");

            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->loadXML($doc->saveXML());
            $dom->formatOutput = true;
            $dom->save($configPath);
        });
    }

    private static function idxPathForDataset(Dataset $dataset): string
    {
        return PathHelpers::joinPaths(config('visus.idx_path'), "/datasets/{$dataset->uuid}");
    }

    public static function addProjectToOpenVisus(Project $project)
    {
        OpenVisusApiService::addToOpenVisusConfigFile($project->uuid);
    }

    private static function idxPathForProject(Project $project): string
    {
        return PathHelpers::joinPaths(config('visus.idx_path'), "/datasets/{$project->uuid}");
    }
}