<?php

namespace App\Actions\Files;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GetFileContentsForDisplayAction
{
    private $officeTypes;
    private $imageTypes;

    private $convertibleImageTypes;

    public function __construct()
    {
        $this->officeTypes = [
            "application/vnd.ms-excel"                                                  => true,
            "application/vnd.ms-powerpoint"                                             => true,
            "application/msword"                                                        => true,
            "application/vnd.openxmlformats-officedocument.presentationml.presentation" => true,
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"         => true,
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document"   => true,
        ];

        $this->imageTypes = [
            "image/gif"      => true,
            "image/jpeg"     => true,
            "image/png"      => true,
            "image/tiff"     => true,
            "image/x-ms-bmp" => true,
            "image/bmp"      => true,
        ];

        $this->convertibleImageTypes = [
            'image/tiff'     => true,
            'image/x-ms-bmp' => true,
            'image/bmp'      => true,
        ];
    }

    public function execute(File $file, $useThumbnail = false)
    {
        $filePathPartial = $this->fileContentsPathPartial($file, $useThumbnail);
        if (!Storage::disk('mcfs')->exists($filePathPartial)) {
            return null;
        }

        try {
            return Storage::disk('mcfs')->get($filePathPartial);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getMimeTypeTakingIntoAccountConversion(File $file)
    {
        if (array_key_exists($file->mime_type, $this->convertibleImageTypes)) {
            return "image/jpeg";
        }

        if (array_key_exists($file->mime_type, $this->officeTypes)) {
            return "application/pdf";
        }

        if (Str::endsWith($file->name, ".ipynb")) {
            return "text/html";
        }

        return $file->mime_type;
    }

    private function fileContentsPathPartial(File $file, $useThumbnail = false)
    {
        $uuid = $file->uuid;
        if (!blank($file->uses_uuid)) {
            $uuid = $file->uses_uuid;
        }

        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        $dirPath = "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
        $fileName = $uuid;

        if ($useThumbnail && $file->isImage()) {
            $dirPath = $dirPath."/.thumbnails";
            $fileName = $fileName.".thumb.jpg";
            return "{$dirPath}/{$fileName}";
        }

        if (array_key_exists($file->mime_type, $this->convertibleImageTypes)) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".jpg";
        }

        if (array_key_exists($file->mime_type, $this->officeTypes)) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".pdf";
        }

        if (Str::endsWith($file->name, ".ipynb")) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".html";
        }

        return "{$dirPath}/{$fileName}";
    }
}