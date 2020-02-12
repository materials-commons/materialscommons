<?php

namespace App\ViewModels\Files;

use App\Models\File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Spatie\ViewModels\ViewModel;

class ShowFileViewModel extends ViewModel
{
    private $officeTypes;
    private $imageTypes;
    private $binaryTypes;
    private $pdfTypes;

    /**
     * @var \App\Models\File
     */
    private $file;

    /**
     * @var \App\Models\Project
     */
    private $project;

    /**
     * @var \App\Models\Dataset | null
     */
    private $dataset;

    public function __construct(File $file, $project, $dataset = null)
    {
        $this->file = $file;
        $this->project = $project;
        $this->dataset = $dataset;

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

        $this->binaryTypes = [
            "application/octet-stream" => true,
        ];

        $this->pdfTypes = [
            "application/pdf" => true,
        ];
    }

    public function project()
    {
        return $this->project;
    }

    public function file(): File
    {
        return $this->file;
    }

    public function dataset()
    {
        return $this->dataset;
    }

    public function fileType(): string
    {
        if (array_key_exists($this->file->mime_type, $this->imageTypes)) {
            return "image";
        }

        if (array_key_exists($this->file->mime_type, $this->pdfTypes)) {
            return "pdf";
        }

        if (array_key_exists($this->file->mime_type, $this->officeTypes)) {
            return "office";
        }

        if (array_key_exists($this->file->mime_type, $this->binaryTypes)) {
            return "binary";
        }

        return "text";
    }

    public function fileContents()
    {
        $uuid = $this->file->uuid;
        if ($this->file->uses_uuid !== null) {
            $uuid = $this->file->uses_uuid;
        }

        $entries = explode('-', $uuid);
        $entry1 = $entries[1];
        try {
            return Storage::disk('local')->get("{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}/{$uuid}");
        } catch (FileNotFoundException $e) {
            return 'No file';
        }
    }

    public function fileExtension()
    {
        $pathinfo = pathinfo($this->file->name);
        return $pathinfo["extension"];
    }
}
