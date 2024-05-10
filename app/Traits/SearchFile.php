<?php

namespace App\Traits;

use App\Models\EtlRun;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function blank;
use function explode;
use function fclose;
use function feof;
use function fgets;
use function fopen;

trait SearchFile
{
    private function search($disk, $path, $search): string
    {
        if (blank($search)) {
            return $this->wholeFile($disk, $path);
        }

        return $this->searchFile($disk, $path, $search);
    }

    private function wholeFile($disk, $path): string
    {
        $content = Storage::disk($disk)->get($path);
        return "<pre>{$content}</pre>";
    }

    private function searchFile($disk, $path, $search): string
    {
        $searchTerms = explode(' ', Str::of($search)->lower());

        $fd = fopen(Storage::disk($disk)->path($path), "r");
        if (!$fd) {
            return "<pre>{$path} doesn't exist</pre>";
        }

        $content = "";
        while (!feof($fd)) {
            $line = fgets($fd);
            if (Str::of($line)->lower()->containsAll($searchTerms)) {
                $content = $content.$line;
            }
        }

        fclose($fd);

        return "<pre style='white-space: pre-wrap'>{$content}</pre>";
    }
}