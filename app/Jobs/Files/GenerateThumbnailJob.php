<?php

namespace App\Jobs\Files;

use App\Actions\Files\GenerateThumbnailAction;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        $generateThumbnailAction = new GenerateThumbnailAction();
        $generateThumbnailAction->execute($this->file);
    }
}