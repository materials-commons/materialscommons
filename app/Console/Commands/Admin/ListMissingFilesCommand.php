<?php

namespace App\Console\Commands\Admin;

use App\Models\File;
use Illuminate\Console\Command;

class ListMissingFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:list-missing-files';

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
        ini_set("memory_limit", "4096M");
        foreach (File::cursor() as $f) {
            if (!$f->realFileExists()) {
                echo "{$f->name}:{$f->id}:{$f->project_id}:{$f->mcfsPath()}\n";
            }
        }
    }
}
