<?php

namespace App\Services;

use App\Helpers\PathHelpers;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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
        // $doc = simplexml_load_file('/home/gtarcea/visus/datasets/datasets.config');
        // $datasets = $doc->datasets;
        // $newData = $datasets->addChild('dataset');
        // $newData->addAttribute('name', 'test');
        // $newData->addAttribute('url', '/datasets/test/visus.idx');

        // $newData2 = $datasets->addChild('dataset');
        // $newData2->addAttribute('name', 'test2');
        // $newData2->addAttribute('url', '/datasets/test2/visus.idx');

        // $dom = new DOMDocument;
        // $dom->preserveWhiteSpace = FALSE;
        // $dom->loadXML($doc->saveXML());
        // $dom->formatOutput = true;
        // // $doc->preserveWhiteSpace = false;
        // // $doc->formatOutput = true;
        // $dom->save('/home/gtarcea/datasets-new.config');
    }

    private static function idxPathForDataset(Dataset $dataset): string
    {
        return PathHelpers::joinPaths(config('visus.idx_path'), "/datasets/{$dataset->uuid}");
    }

    public static function addProjectToOpenVisus(Project $project)
    {

    }

    private static function idxPathForProject(Project $project): string
    {
        return PathHelpers::joinPaths(config('visus.idx_path'), "/projects/{$project->uuid}");
    }
}