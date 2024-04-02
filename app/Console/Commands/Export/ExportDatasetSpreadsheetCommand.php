<?php

namespace App\Console\Commands\Export;

use App\Exports\DatasetExporter;
use App\Models\Dataset;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportDatasetSpreadsheetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-export:export-dataset-spreadsheet {datasetId : Dataset to export}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $datasetId = $this->argument("datasetId");
        $dataset = Dataset::findOrFail($datasetId);
        $exporter = new DatasetExporter($dataset);
        Excel::store($exporter, 'ds.xlsx');
    }
}
