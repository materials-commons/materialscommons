<?php

namespace App\Console\Commands;

use App\Jobs\Files\ConvertFileJob;
use App\Models\Conversion;
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
            if ($conversion->file->shouldBeConverted()) {
                ConvertFileJob::dispatch($conversion->file)->onQueue('globus');
            }
            $conversion->delete();
        }
        return 0;
    }
}
