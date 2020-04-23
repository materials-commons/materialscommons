<?php

namespace App\Console\Commands;

use App\Actions\Files\ConvertFileAction;
use App\Models\File;
use Illuminate\Console\Command;

class ConvertProjectFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:convert-project-files {projectId : Project Id for project files to convert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert project image and office documents to format that can be viewed on web';

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
     * @return mixed
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        $projectId = $this->argument("projectId");
        $convertFileAction = new ConvertFileAction();
        File::where('project_id', $projectId)->where('mime_type', '<>', 'directory')
            ->chunk(100, function ($files) use ($convertFileAction) {
                foreach ($files as $file) {
                    if ($file->shouldBeConverted()) {
                        echo "Converting file {$file->name} with uuid {$file->uuid}\n";
                        $convertFileAction->execute($file);
                    }
                }
            });
    }
}
