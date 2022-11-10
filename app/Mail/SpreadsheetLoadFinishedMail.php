<?php

namespace App\Mail;

use App\Models\EtlRun;
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

    /** @var \App\Models\EtlRun */
    public $etlRun;

    public ?string $sheetUrl;

    public function __construct(File $file, $sheetUrl, Project $project, Experiment $experiment, EtlRun $etlRun)
    {
        $this->file = $file;
        $this->project = $project;
        $this->experiment = $experiment;
        $this->etlRun = $etlRun;
        $this->sheetUrl = $sheetUrl;
    }

    public function build()
    {
        $this->file->load('directory');
        $this->etlRun->load('owner');
        $viewModel = (new ShowExperimentDataDictionaryViewModel())
            ->withProject($this->project)
            ->withExperiment($this->experiment)
            ->withEntityAttributes($this->getUniqueEntityAttributesForExperiment($this->experiment->id))
            ->withActivityAttributes($this->getUniqueActivityAttributesForExperiment($this->experiment->id));

        if (!is_null($this->file)) {
            $subject = "Finished Loading {$this->file->name}";
        } else {
            $subject = "Finished Loading Google Sheet";
        }

        return $this->subject($subject)->view('email.etl.finished', compact('viewModel'));
    }
}
