<?php

namespace App\Console\Commands\Disk;

use App\Models\File;
use Illuminate\Console\Command;

class ReplicateMCFSFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-disk:replicate-mcfs-files {--limit=1000}';

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
     * @return int
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");

        $replicationRoot = config('filesystems.disks.mcfs_replica.root');
        if (is_null($replicationRoot)) {
            return 0;
        }

        $limit = $this->option("limit");
        File::whereNull('uses_uuid')
            ->whereNull("replicated_at")
            ->limit($limit)
            ->cursor()
            ->each(function (File $file) {
                $file->mcfsReplicate();
            });
        return 0;
    }
}
