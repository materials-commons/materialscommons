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
                                                     {--ignore-projects=* : projects to ignore loading} 
                                                     {--load-projects=* : only projects to load}';

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
        $migrateRethinkdbDataAction = new MigrateRethinkdbDataAction($dbDir, $ignoreExisting);
        $projectsToIgnore = $this->option('ignore-projects');
        $projectsToLoad = $this->option('load-projects');
        $migrateRethinkdbDataAction($projectsToIgnore, $projectsToLoad);
    }
}
