<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\TbdFile;
use App\Traits\PathForFile;
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

        foreach (TbdFile::limit($limit)->cursor() as $tbdFile) {
            $uuid = $tbdFile->uuid;

            // Always delete the entry in the table, then determine whether or not to delete
            // the file on disk.
            $tbdFile->delete();

            // If the file doesn't exist on disk then there is nothing to do.
            if (!$this->fileExists($uuid)) {
                continue;
            }

            // Check if anything references this uuid: If it does then we can't delete the
            // on disk file.
            $count = File::where('uses_uuid', $uuid)->count();
            if ($count == 0) {
                // If we are here then nothing points at this file, so we can delete it.
                Storage::disk('mcfs')->delete($this->getFilePathPartialFromUid($uuid));
            }
        }

        return 0;
    }

    private function fileExists($uuid)
    {
        return Storage::disk('mcfs')->exists($this->getFilePathPartialFromUid($uuid));
    }
}
