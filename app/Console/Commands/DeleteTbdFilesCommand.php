<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\TbdFile;
use App\Traits\PathForFile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteTbdFilesCommand extends Command
{
    use PathForFile;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:delete-tbd-files {--limit= : Number of entries to process}';

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
        $limit = $this->option("limit");
        if (is_null($limit)) {
            $limit = 100;
        }

        $twentyFourHoursAgo = Carbon::now()->subHours(24);

        foreach (TbdFile::where('created_at', '<', $twentyFourHoursAgo)->limit($limit)->cursor() as $tbdFile) {
            $uuid = $tbdFile->uuid;

            // Always delete the entry in the table, then determine whether to delete
            // the file on disk.
            $tbdFile->delete();

            // Check if anything references this uuid: If it does, then we can't delete the
            // on disk file.
            $count = File::where('uses_uuid', $uuid)->count();
            if ($count > 0) {
                // If the count is greater than zero, then something points at the file, so
                // don't delete it.
                continue;
            }

            // Double-check that this UUID isn't being used anywhere else.
            $count = File::where('uuid', $uuid)->count();
            if ($count > 0) {
                continue;
            }

            if (!$this->fileExists($uuid)) {
                // If the file doesn't exist on disk, then there is nothing to do.
                continue;
            }

            // If we are here, then nothing points at this file, so we can delete it.
//            Storage::disk('mcfs')->delete($this->getFilePathPartialFromUuid($uuid));
        }

        return 0;
    }

    private function fileExists($uuid)
    {
        return Storage::disk('mcfs')->exists($this->getFilePathPartialFromUuid($uuid));
    }
}
