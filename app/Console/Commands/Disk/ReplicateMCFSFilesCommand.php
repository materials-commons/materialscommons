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

        // Only select files that haven't been replicated *AND* are not currently being used in transfer_request_files.
        // The reason to exclude files in transfer_request_files is that any entry in that table has a file in an
        // unknown state. Files in the transfer_request_files are files that are used by the mcbridgefs. As such those
        // files could be having bytes written to them. So we exclude them as they aren't candidates for replication
        // at this time.
        File::select('files.*')
            ->whereNull('files.uses_uuid')
            ->whereNull("files.replicated_at")
            ->leftJoin('transfer_request_files', function ($q) {
                $q->on('files.id', '=', 'transfer_request_files.file_id');
            })
            ->whereNull('transfer_request_files.file_id')
            ->limit($limit)
            ->cursor()
            ->each(function (File $file) {
                $file->mcfsReplicate();
            });
        return 0;
    }
}
