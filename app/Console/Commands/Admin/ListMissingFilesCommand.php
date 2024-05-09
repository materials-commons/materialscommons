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
        $missingCount = 0;
        foreach (File::where('mime_type', '<>', 'directory')->cursor() as $f) {
            if (!$f->realFileExists()) {
                $usesUuid = "no-uses-uuid";
                if (!blank($f->uses_uuid)) {
                    $usesUuid = $f->uses_uuid;
                }

                echo "{$f->name},{$f->id},{$f->directory_id},{$f->project_id},{$f->dataset_id},{$f->mcfsPath()},{$usesUuid},{$f->checksum},{$f->owner_id},{$f->created_at}\n";
                $missingCount++;
            }
        }
        echo "\n\nTotal Missing: {$missingCount}\n";
    }
}
