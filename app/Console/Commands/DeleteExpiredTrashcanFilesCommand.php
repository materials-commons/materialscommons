<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\TbdFile;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteExpiredTrashcanFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:delete-expired-trashcan-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete files in trashcan';

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
        $files = File::where('deleted_at', '<', $days)
                     ->where('mime_type', '<>', 'directory')
                     ->limit(1000)
                     ->cursor();
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
        return 0;
    }

    private function deleteFile(File $file)
    {
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
}
