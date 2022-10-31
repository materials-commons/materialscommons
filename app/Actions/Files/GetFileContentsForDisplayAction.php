<?php

namespace App\Actions\Files;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

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

    public function execute(File $file)
    {
        $filePathPartial = $this->fileContentsPathPartial($file);
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

        return $file->mime_type;
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

        if (array_key_exists($file->mime_type, $this->convertibleImageTypes)) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".jpg";
        }

        if (array_key_exists($file->mime_type, $this->officeTypes)) {
            $dirPath = $dirPath."/.conversion";
            $fileName = $fileName.".pdf";
        }

        return "{$dirPath}/{$fileName}";
    }
}