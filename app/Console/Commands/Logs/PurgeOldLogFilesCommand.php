<?php

namespace App\Console\Commands\Logs;

use App\Models\EtlRun;
use App\Models\TransferRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeOldLogFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-logs:purge-old-log-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges old log files';

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
        $this->purgeEtlLogs();
        $this->purgeMCBridgeLogs();
        return 0;
    }

    private function purgeEtlLogs()
    {
        EtlRun::getOldEtlLogs()->each(function(EtlRun $etlRun) {
            $etlRun->deleteLog();
            $etlRun->delete();
        });
    }

    private function purgeMCBridgeLogs()
    {
        Storage::disk('mcfs')->path("bridge_logs/");
        collect(Storage::disk('mcfs')->allFiles('bridge_logs'))->each(function ($file) {
            $info = pathinfo(Storage::disk('mcfs')->path($file));

            // Log file is stored with the uuid as the filename (part without the extension).
            $uuid = $info['filename'];
            $transferRequest = TransferRequest::where('uuid', $uuid)->first();

            // Only delete log file if there is not a transfer request associated with it.
            if (is_null($transferRequest)) {
                Storage::disk('mcfs')->delete($file);
            }
        });
    }
}
