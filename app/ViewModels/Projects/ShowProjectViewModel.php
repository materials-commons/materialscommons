<?php

namespace App\ViewModels\Projects;

use App\Models\Project;
use App\ViewModels\Concerns\HasOverviews;
use Spatie\ViewModels\ViewModel;

class ShowProjectViewModel extends ViewModel
{
    use HasOverviews;

    /** @var \App\Models\Project */
    private $project;

    private int $entityAttributesCount;
    private int $activityAttributesCount;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function project()
    {
        return $this->project;
    }

    public function withEntityAttributesCount(int $count)
    {
        $this->entityAttributesCount = $count;
        return $this;
    }

    public function withActivityAttributesCount(int $count)
    {
        $this->activityAttributesCount = $count;
        return $this;
    }

    public function entityAttributesCount(): int
    {
        return $this->entityAttributesCount;
    }

    public function activityAttributesCount(): int
    {
        return $this->activityAttributesCount;
    }
}
