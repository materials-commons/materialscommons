<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Str;
use const PATHINFO_EXTENSION;

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

    protected $videoTypes = [
        'video/webm' => true,
        'video/mp4'  => true,
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
        "text/plain"       => true,
        "text/csv"         => true,
        "application/json" => true,
    ];

    protected $htmlTypes = [
        "text/html" => true,
    ];

    public function fileType($file): string
    {
        $type = $this->fileTypeFromMime($file->mime_type);

        // Hack to work around the check below for startsWith(..., "text/")
        if ($type == "html") {
            if (Str::endsWith($file->name, ".php")) {
                return "text";
            }
            return "html";
        }

        if (!is_null($file->dataset_id)) {
            if ($type == "text") {
                if (Str::endsWith($file->name, ".idx")) {
                    return "open-visus";
                }
            }
        }

        if (Str::endsWith($file->name, ".ipynb")) {
            return "jupyter-notebook";
        }

        if (Str::endsWith($file->name, ".md")) {
            return "markdown";
        }

        if (Str::startsWith($file->mime_type, "text/")) {
            return "text";
        }

        // Handle .hdr files from
        if (Str::endsWith($file->name, ".hdr")) {
            return "text";
        }

        if (Str::endsWith($file->name, ".txt")) {
            return "text";
        }

        return $type;
    }

    private function fileTypeShouldReturnContents($file)
    {
        $fileType = $this->fileType($file);
        switch ($fileType) {
            case "image":
            case "pdf":
            case "jupyter-notebook":
            case "office":
            case "video":
                return true;
            default:
                return false;
        }
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

        if ($this->inTable($mime, $this->htmlTypes)) {
            return "html";
        }

        if ($this->inTable($mime, $this->textTypes)) {
            return "text";
        }

        if ($this->inTable($mime, $this->videoTypes)) {
            return "video";
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

    function extensionToDescription($extension)
    {
        if (blank($extension)) {
            return "Unknown";
        }

        if ($extension == "pdf") {
            return "PDF";
        }

        if ($extension == "py") {
            return "Python";
        }

        return $extension;
    }

    function mimeTypeToDescriptionForDisplay(File $file): string
    {
        if ($file->mime_type == "directory") {
            return "Directory";
        }

        if ($file->mime_type == "url") {
            return "URL";
        }

        $type = $this->mimeTypeToDescription($file->mime_type);
        if ($type != "Unknown") {
            return $type;
        }

        // If the type is Unknown then attempt to determine the type from the extension.
        $ext = pathinfo($file->name, PATHINFO_EXTENSION);
        return $this->extensionToDescription($ext);
    }

    protected function inTable($type, $lookupTable)
    {
        return array_key_exists($type, $lookupTable);
    }
}
