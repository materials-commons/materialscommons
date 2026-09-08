<?php

namespace App\Console\Commands;

use App\Jobs\Files\ConvertFileJob;
use App\Models\Conversion;
use App\Models\File;
use Illuminate\Console\Command;

class RunConversionOnFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:run-conversion-on-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Iterates through the conversions table dispatching files to be converted and deleting the entry from the conversions table';

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
        $conversions = Conversion::with(['file'])
                                 ->whereNull('conversion_started_at')
                                 ->limit(1000)
                                 ->cursor();
        foreach ($conversions as $conversion) {
            $successfulUpdate = $this->updateSearchIndexForFileAndVersions($conversion->file->id);
            if ($conversion->file->shouldBeConverted() && $successfulUpdate) {
                ConvertFileJob::dispatch($conversion->file)->onQueue('globus');
            }
            if ($successfulUpdate) {
                $conversion->delete();
            }
        }
        return 0;
    }

    private function updateSearchIndexForFileAndVersions($fileId): bool {
        try {
            $file = File::find($fileId);
            if ($file) {
                $file->searchable();
                $file->previousVersions()->searchable();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
