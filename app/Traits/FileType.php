<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FileType
{
    protected $wordTypes = [
        "application/msword"                                                      => true,
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => true,
    ];

    protected $powerpointTypes = [
        "application/vnd.ms-powerpoint"                                             => true,
        "application/vnd.openxmlformats-officedocument.presentationml.presentation" => true,
    ];

    protected $excelTypes = [
        "application/vnd.ms-excel"                                          => true,
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => true,
    ];
    protected $imageTypes = [
        "image/gif"      => true,
        "image/jpeg"     => true,
        "image/png"      => true,
        "image/tiff"     => true,
        "image/x-ms-bmp" => true,
        "image/bmp"      => true,
    ];

    protected $convertibleImageTypes = [
        'image/tiff'     => true,
        'image/x-ms-bmp' => true,
        'image/bmp'      => true,
    ];

    protected $binaryTypes = [
        "application/octet-stream" => true,
    ];


    protected $pdfTypes = [
        "application/pdf" => true,
    ];

    protected $textTypes = [
        "text/plain" => true,
        "text/csv"   => true,
    ];

    public function fileType($file): string
    {
        return $this->fileTypeFromMime($file->mime_type);
    }

    public function fileTypeFromMime($mime)
    {
        if ($this->inTable($mime, $this->imageTypes)) {
            return "image";
        }

        if ($this->inTable($mime, $this->pdfTypes)) {
            return "pdf";
        }

        if ($this->inTable($mime, $this->excelTypes)) {
            return "excel";
        }

        if ($this->inTable($mime, $this->wordTypes)) {
            return "office";
        }

        if ($this->inTable($mime, $this->powerpointTypes)) {
            return "office";
        }

        if ($this->inTable($mime, $this->binaryTypes)) {
            return "binary";
        }

        if ($this->inTable($mime, $this->textTypes)) {
            return "text";
        }

        return "unknown";
    }

    public function mimeTypeToDescription($mime)
    {
        if ($this->inTable($mime, $this->imageTypes)) {
            return "Image";
        }

        if ($this->inTable($mime, $this->powerpointTypes)) {
            return "PowerPoint";
        }

        if ($this->inTable($mime, $this->wordTypes)) {
            return "MS-Word";
        }

        if ($this->inTable($mime, $this->excelTypes)) {
            return "Excel";
        }

        if ($this->inTable($mime, $this->binaryTypes)) {
            return "Binary";
        }

        if ($this->inTable($mime, $this->textTypes)) {
            return "Text";
        }

        if (Str::contains($mime, "video")) {
            return "Video";
        }

        if (Str::contains($mime, "zip")) {
            return "Zipfile";
        }

        if (Str::contains($mime, "latex")) {
            return "Latex";
        }

        return "Unknown";
    }

    protected function inTable($type, $lookupTable)
    {
        return array_key_exists($type, $lookupTable);
    }
}
