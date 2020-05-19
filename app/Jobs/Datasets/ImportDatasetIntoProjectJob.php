<?php

namespace App\Jobs\Datasets;

use App\Actions\Datasets\ImportPublishedDatasetIntoProjectAction;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportDatasetIntoProjectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\Dataset
     */
    public $dataset;

    /**
     * @var \App\Models\Project
     */
    public $project;

    /**
     * Root dir to import into
     * @var string
     */
    public $dirName;

    public function __construct(Dataset $dataset, Project $project, string $dirName)
    {
        $this->dataset = $dataset;
        $this->project = $project;
        $this->dirName = $dirName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $importPublishedDatasetIntoProjectAction = new ImportPublishedDatasetIntoProjectAction();
        $importPublishedDatasetIntoProjectAction->execute($this->dataset, $this->project, $this->dirName);
    }
}
