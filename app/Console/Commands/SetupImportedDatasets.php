<?php

namespace App\Console\Commands;

use App\Actions\Globus\GlobusApi;
use App\Actions\Migration\Datasets\SetupMigratedPublishedDatasetsAction;
use Illuminate\Console\Command;

class SetupImportedDatasets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:setup-imported-datasets {--globus : do not run globus} 
                                                       {--zip : do not run zip file linker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup published datasets that were imported from rethinkdb';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $runZipLinker = $this->option('zip');
        $runGlobus = $this->option('globus');
        $setupMigratedPublishedDatasetsAction = new SetupMigratedPublishedDatasetsAction(GlobusApi::createGlobusApiWithEcho());
        $setupMigratedPublishedDatasetsAction($runZipLinker, $runGlobus);
    }
}