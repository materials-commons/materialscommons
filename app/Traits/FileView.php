<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileView
{
    use FileType;

    public function fileExists(File $file)
    {
        $filePath = Storage::disk('mcfs')->path($this->fileContentsPathPartial($file));
        return file_exists($filePath);
    }

    public function fileConversionExists(File $file)
    {
        $convertedFilePath = Storage::disk('mcfs')->path($file->convertedPathPartial());
        return file_exists($convertedFilePath);
    }

    public function fileContents(File $file)
    {
        $filePathPartial = $this->fileContentsPathPartial($file);
        try {
            return Storage::disk('mcfs')->get($filePathPartial);
        } catch (FileNotFoundException $e) {
            return 'No file';
        }
    }

    public function fileContentsBase64(File $file)
    {
        return base64_encode($this->fileContents($file));
    }

    private function fileContentsPathPartial(File $file)
    {
        $uuid = $file->uuid;
        if (!blank($file->uses_uuid)) {
            $uuid = $file->uses_uuid;
        }

        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        $dirPath = "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
        $fileName = $uuid;

        if ($this->inTable($file->mime_type, $this->convertibleImageTypes)) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".jpg";
        }

        if ($this->inTable($file->mime_type, $this->wordTypes)) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".pdf";
        }

        if ($this->inTable($file->mime_type, $this->powerpointTypes)) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".pdf";
        }

        if (Str::endsWith($file->name, ".ipynb")) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".html";
        }

//        if (array_key_exists($this->file->mime_type, $this->excelTypes)) {
//            $dirPath = $dirPath."/.conversion";
//            $fileName = $fileName.".pdf";
//        }

        return "{$dirPath}/{$fileName}";
    }
}
