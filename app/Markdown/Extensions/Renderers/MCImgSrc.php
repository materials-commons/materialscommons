<?php

namespace App\Markdown\Extensions\Renderers;

use App\Models\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function basename;
use function dirname;
use function is_null;
use function request;
use function route;

trait MCImgSrc
{
    private function fixSrcTagIfNeeded(string $imgTag): bool|string
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($imgTag);
        $img = $dom->getElementsByTagName('img')->item(0);
        $src = $img->getAttribute('src');
        if (!Str::startsWith($src, '/')) {
            return $imgTag;
        }

        $newSrcPath = $this->lookupPathByRouteContext($src);
        if (!is_null($newSrcPath)) {
            $img->setAttribute('src', $newSrcPath);
            return $dom->saveHTML();
        }

        return $imgTag;
    }

    private function lookupPathByRouteContext(string $src): ?string
    {
        $params = request()->route()->parameters();
        if (Arr::has($params, 'project')) {
            $f = $this->getFileFor('project', $params['project'], $src);
            if (!is_null($f)) {
                return route('projects.files.display', ['project' => $params['project'], 'file' => $f->id]);
            }
        }

        if (Arr::has($params, 'dataset')) {
            $f = $this->getFileFor('dataset', $params['dataset'], $src);
            if (!is_null($f)) {
                return route('public.datasets.files.display', ['dataset' => $params['dataset'], 'file' => $f->id]);
            }
        }

        return null;
    }

    private function getFileFor($modelType, $id, $path)
    {
        $column = 'project_id';
        if ($modelType === 'dataset') {
            $column = 'dataset_id';
        }

        $dirPath = dirname($path);
        $fileName = basename($path);

        $q = File::where($column, $id)
                 ->where('path', $dirPath)
                 ->whereNull('deleted_at')
                 ->where('current', true);
        if ($modelType === 'project') {
            $q->whereNull('dataset_id');
        }

        $dir = $q->first();
        if (is_null($dir)) {
            return null;
        }

        return File::with('directory')
                   ->where('directory_id', $dir->id)
                   ->where('name', $fileName)
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();
    }
}