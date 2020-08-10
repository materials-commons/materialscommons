<?php

namespace App\ViewModels\Concerns;

use App\Traits\FileType;

trait HasOverviews
{

    use FileType;

    /** @var array */
    private $activitiesGroup = [];

    /** @var array */
    private $fileTypes = [];

    /** @var array */
    private $fileDescriptionTypes = null;

    private $objectCounts = [];
    private $totalFilesSize = 0;

    public function withFileTypes($fileTypes)
    {
        $this->fileTypes = $fileTypes;
        $this->buildFileDescriptionsFromfileTypes();
        return $this;
    }

    public function withActivitiesGroup($activitiesGroup)
    {
        $this->activitiesGroup = $activitiesGroup;
        return $this;
    }

    public function withObjectCounts($objectCounts)
    {
        $this->objectCounts = $objectCounts;
        return $this;
    }

    public function withTotalFilesSize($size)
    {
        $this->totalFilesSize = $size;
        return $this;
    }


    public function fileTypes()
    {
        return $this->fileTypes;
    }

    public function fileDescriptionTypes()
    {
        return $this->fileDescriptionTypes;
    }

    public function activitiesGroup()
    {
        return $this->activitiesGroup;
    }

    public function objectCounts()
    {
        return $this->objectCounts;
    }

    public function totalFilesSize()
    {
        return $this->totalFilesSize;
    }

    private function buildFileDescriptionsFromfileTypes()
    {
        $fileDescriptionTypes = [];
        foreach ($this->fileTypes as $fileType) {
            $description = $this->mimeTypeToDescription($fileType->mime_type);
            $descriptionCount = 0;
            if (array_key_exists($description, $fileDescriptionTypes)) {
                $descriptionCount = $fileDescriptionTypes[$description];
            }
            $fileDescriptionTypes[$description] = $descriptionCount + $fileType->count;
        }

        $this->fileDescriptionTypes = collect($fileDescriptionTypes)->sortKeys()->all();
    }
}

