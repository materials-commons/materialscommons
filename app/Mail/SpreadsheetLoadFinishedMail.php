<?php

namespace App\Mail;

use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowExperimentDataDictionaryViewModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SpreadsheetLoadFinishedMail extends Mailable
{
    use Queueable, SerializesModels, DataDictionaryQueries;

    /** @var \App\Models\File */
    public $file;

    /** @var \App\Models\Experiment */
    private $experiment;

    /** @var \App\Models\Project */
    private $project;

    public function __construct(File $file, Project $project, Experiment $experiment)
    {
        $this->file = $file;
        $this->project = $project;
        $this->experiment = $experiment;
    }

    public function build()
    {
        $viewModel = (new ShowExperimentDataDictionaryViewModel())
            ->withProject($this->project)
            ->withExperiment($this->experiment)
            ->withEntityAttributes($this->getUniqueEntityAttributesForExperiment($this->experiment->id))
            ->withActivityAttributes($this->getUniqueActivityAttributesForExperiment($this->experiment->id));
        return $this->subject('Spreadsheet Load')->view('email.etl.finished', compact('viewModel'));
    }
}
