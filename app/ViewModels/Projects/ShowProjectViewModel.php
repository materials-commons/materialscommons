<?php

namespace App\ViewModels\Projects;

use App\Models\Project;
use App\Traits\FileType;
use Spatie\ViewModels\ViewModel;

class ShowProjectViewModel extends ViewModel
{
    use FileType;

    /** @var \App\Models\Project */
    private $project;

    /** @var array */
    private $activitiesGroup = [];

    /** @var array */
    private $fileTypes = [];

    /** @var array */
    private $fileDescriptionTypes = [];

    private $objectCounts = [];
    private $projectSize = 0;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

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

    public function withProjectSize($projectSize)
    {
        $this->projectSize = $projectSize;
        return $this;
    }

    public function project()
    {
        return $this->project;
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

    public function projectSize()
    {
        return $this->projectSize;
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
