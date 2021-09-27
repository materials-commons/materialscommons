<?php

namespace App\Jobs\Datasets\Unpublish;

use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteDatasetGlobusAndZipfilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 10;
    public Dataset $dataset;

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
        @Storage::disk('mcfs')->deleteDirectory($this->dataset->publishedGlobusPathPartial());
        @Storage::disk('mcfs')->deleteDirectory($this->dataset->zipfileDirPartial());
    }
}
