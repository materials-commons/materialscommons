<?php

namespace App\Console\Commands;

use App\Actions\Migration\MigrateRethinkdbDataAction;
use Illuminate\Console\Command;

class ImportRethinkdbDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:import-rethinkdb-data {dbDir : The path for the directory containing the JSON files} 
                                                     {--ignore-existing : do not load if already in database} 
                                                     {--ignore-project=* : projects to ignore loading} 
                                                     {--load-file=* : files to load}
                                                     {--load-project=* : only projects to load}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        ini_set("memory_limit", "4096M");
        $dbDir = $this->argument('dbDir');
        $ignoreExisting = $this->option("ignore-existing");
        $filesToLoad = $this->option('load-file');
        $migrateRethinkdbDataAction = new MigrateRethinkdbDataAction($dbDir, $ignoreExisting, $filesToLoad);

        $projectsToIgnore = $this->option('ignore-project');
        $projectsToLoad = $this->option('load-project');
        $migrateRethinkdbDataAction($projectsToIgnore, $projectsToLoad);
    }
}
