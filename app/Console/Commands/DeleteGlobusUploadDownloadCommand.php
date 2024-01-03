<?php

namespace App\Console\Commands;

use App\Jobs\Globus\DeleteGlobusUploadDownloadJob;
use App\Models\GlobusUploadDownload;
use Illuminate\Console\Command;

class DeleteGlobusUploadDownloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:delete-globus-upload-download {globus : id of globus upload download to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $globus = GlobusUploadDownload::findOrFail($this->argument('globus'));
        DeleteGlobusUploadDownloadJob::dispatch($globus)->onQueue("globus");
        return Command::SUCCESS;
    }
}
