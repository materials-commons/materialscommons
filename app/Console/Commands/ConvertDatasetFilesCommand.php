<?php

namespace App\Console\Commands;

use App\Models\Dataset;
use App\Models\File;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class ConvertDatasetFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:convert-dataset-files';

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
        foreach (Dataset::whereNotNull('published_at')->get() as $dataset) {
            echo "Converting dataset {$dataset->name}...\n";
            foreach ($dataset->files()->where('project_id', $dataset->project_id)->cursor() as $file) {
                $f = $this->duplicateFile($file);
                $dataset->files()->attach($f->id);
                $dataset->files()->detach($file->id);
            }
        }
        echo "Done.\n";
    }

    private function duplicateFile(File $file)
    {
        $f = $file->replicate(['project_id'])->fill([
            'uuid'      => Uuid::uuid4()->toString(),
            'uses_uuid' => blank($file->uses_uuid) ? $file->uuid : $file->uses_uuid,
        ]);
        $f->save();
        return $f->refresh();
    }
}
