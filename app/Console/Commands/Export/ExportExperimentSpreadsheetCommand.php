<?php

namespace App\Console\Commands\Export;

use App\Exports\ExperimentExporter;
use App\Models\Experiment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ExportExperimentSpreadsheetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-export:export-experiment-spreadsheet {experimentId : Experiment to export}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export an experiments metadata into a spreadsheet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $experimentId = $this->argument('experimentId');
        $experiment = Experiment::with(['activities'])->findOrFail($experimentId);
        $exporter = new ExperimentExporter($experiment);
        $name = Str::replace(" ", "_", $experiment->name);
        Excel::store($exporter, "ss.xlsx");
    }
}
