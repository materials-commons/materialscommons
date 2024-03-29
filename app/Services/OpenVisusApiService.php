<?php

namespace App\Services;

use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use function config;
use function simplexml_load_file;

class OpenVisusApiService
{
    //$doc = simplexml_load_file('/home/gtarcea/visus/datasets/datasets.config');
    //$datasets = $doc->datasets;
    //$ds = $datasets->dataset[0];
    //echo $ds->attributes()["mc-uuid"];

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

        return $url."{$visusDataset}";
    }

    public static function addDatasetToOpenVisus(Dataset $dataset)
    {
        OpenVisusApiService::addToOpenVisusConfigFile("dataset", $dataset->uuid, $dataset->name, $dataset->owner_id);
    }

    private static function addToOpenVisusConfigFile($type, $uuid, $name, $ownerId)
    {
        Cache::lock("visus")->get(function () use ($type, $uuid, $name, $ownerId) {
            $configPath = config('visus.idx_path')."/datasets.config";
            $doc = simplexml_load_file($configPath);
            $datasets = $doc->datasets;
            $newData = $datasets->addChild('dataset');
            $newData->addAttribute('name', "{$ownerId}:{$name}");
            $newData->addAttribute('mc-uuid', $uuid);
            $newData->addAttribute('mc-type', $type);
            $newData->addAttribute('url', "/datasets/{$uuid}/visus.idx");

            $dom = new DOMDocument();
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
        OpenVisusApiService::addToOpenVisusConfigFile("project", $project->uuid, $project->name, $project->owner_id);
    }

    public static function addIdxForProjectToOpenVisus(Project $project, File $dir, File $idxFile)
    {
        $configPath = config('visus.idx_path')."/datasets.config";
        $doc = simplexml_load_file($configPath);
        $datasets = $doc->datasets;
        $newData = $datasets->addChild('dataset');
        $newData->addAttribute('name', "{$idxFile->uuid}_{$idxFile->name}");
        $newData->addAttribute("mc-id", $idxFile->id);
        $newData->addAttribute('mc-type', 'project');
        $newData->addAttribute("mc-container-uuid", $project->uuid);
        $newData->addAttribute('url', "/datasets/{$project->uuid}/{$dir->uuid}/{$idxFile->name}");

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($doc->saveXML());
        $dom->formatOutput = true;
        $dom->save($configPath);
        $nameWithoutExtension = pathinfo($idxFile->name, PATHINFO_FILENAME);
        return config("visus.idx_path")."/{$project->uuid}/{$dir->uuid}/{$nameWithoutExtension}";
    }

    private static function idxPathForProject(Project $project): string
    {
        return PathHelpers::joinPaths(config('visus.idx_path'), "/datasets/{$project->uuid}");
    }
}