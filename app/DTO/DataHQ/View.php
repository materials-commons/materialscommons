<?php

namespace App\DTO\DataHQ;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;
use JsonSerializable;

class DatahqInstance // This will become the DatahqInstance Model
{
    public integer $id;
    public string $uuid;
    public $currentAt; // Date when this became the current instance, used in projects because
    // they have experiments and so there could be multiple instances.
    public User $user; // User the Instance belongs to
    public ?Project $project; // Instance is associated with a project
    public ?Dataset $dataset; // Instance is associated with a published dataset
    public ?Experiment $experiment; // Instance is associated with an experiment
    public string $currentExplorer; // Which explorer is current

    public ?Explorer $samplesExplorer;
    public ?Explorer $computationsExplorer;
    public ?Explorer $processesExplorer;
    public ?Explorer $overviewExplorer;
}

class View implements JsonSerializable
{
    public string $name;  // Name of the view
    public string $description; // Description of the view
    public string $mql; // Any mql that is used to filter this views attributes, samples, computations or processes
    public string $currentSubview; // The current subview
    public Collection $subviews; // List of subviews

    public function __construct(string     $name, string $description, string $mql, string $currentSubview,
                                Collection $subviews)
    {
        $this->name = $name;
        $this->description = $description;
        $this->mql = $mql;
        $this->currentSubview = $currentSubview;
        $this->subviews = $subviews;
    }

    public function jsonSerialize(): array
    {
        return [
            'name'           => $this->name,
            'description'    => $this->description,
            'mql'            => $this->mql,
            'currentSubview' => $this->currentSubview,
            'subviews'       => $this->subviews->map(function ($subview, $key) {
                return $subview->jsonSerialize();
            })->toArray(),
        ];
    }
}
