<?php

namespace App\Traits;

trait FileType
{
    protected $officeTypes = [
        // Powerpoint documents
        "application/vnd.ms-powerpoint"                                             => true,
        "application/vnd.openxmlformats-officedocument.presentationml.presentation" => true,

        // Word documents
        "application/msword"                                                        => true,
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document"   => true,
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

    public function fileType($file): string
    {
        if (array_key_exists($file->mime_type, $this->imageTypes)) {
            return "image";
        }

        if (array_key_exists($file->mime_type, $this->pdfTypes)) {
            return "pdf";
        }

        if (array_key_exists($file->mime_type, $this->excelTypes)) {
            return "excel";
        }

        if (array_key_exists($file->mime_type, $this->officeTypes)) {
            return "office";
        }

        if (array_key_exists($file->mime_type, $this->binaryTypes)) {
            return "binary";
        }

        return "text";
    }
}
