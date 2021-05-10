<?php

namespace App\Console\Commands\Disk;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadUsingS3Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-disk:upload-using-s3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test uploading a file using S3';

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
        Storage::disk('s3')->putFileAs('d1', "/tmp/file.txt", "file.txt");
        return 0;
    }
}
