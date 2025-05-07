<?php

namespace App\Console\Commands;

use App\Actions\Directories\ChildDirs;
use App\Models\File;
use App\Models\TbdFile;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteExpiredTrashcanDirectoriesCommand extends Command
{
    use ChildDirs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:delete-expired-trashcan-directories';

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
        $days = Carbon::now()->subDays(config('trash.expires_in_days'));
        $rootDir = File::where('deleted_at', '<', $days)
                       ->where('mime_type', 'directory')
                       ->first();
        if (is_null($rootDir)) {
            return 0;
        }
        $dirs = $this->recursivelyRetrieveAllSubdirs($rootDir->id);
        foreach ($dirs as $dir) {
            $this->deleteFilesAndDir($dir);
        }

        $this->deleteFilesAndDir($rootDir);
        return 0;
    }

    private function deleteFilesAndDir(File $dir)
    {
        $files = File::where('directory_id', $dir->id)
                     ->where('mime_type', '<>', 'directory')
                     ->cursor();
        foreach ($files as $file) {
            TbdFile::create([
                'uuid'       => $file->uuid,
                'disk'       => $file->disk,
                'project_id' => $file->project_id,
            ]);
            if (!blank($file->uses_uuid)) {
                TbdFile::create([
                    'uuid'       => $file->uses_uuid,
                    'disk'       => $file->disk,
                    'project_id' => $file->project_id,
                ]);
            }
            $file->delete();
        }
        $dir->delete();
    }
}
