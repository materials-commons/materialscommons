<?php

namespace App\Jobs\Datasets\Unpublish;

use App\Models\Dataset;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DeletePublishedFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 10;

    public Dataset $dataset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $directories = [];
            $this->dataset->load(['files.directory']);
            $this->dataset->files->each(function (File $file) use (&$directories) {
                if (!array_key_exists($file->directory->path, $directories)) {
                    $directories[$file->directory->path] = $file->directory;
                }
                $file->delete();
            });
            foreach ($directories as $path => $dir) {
                $dir->delete();
            }
        });
    }
}
