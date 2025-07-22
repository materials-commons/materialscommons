<?php

namespace App\Console\Commands\Conversion;

use App\Actions\Files\GenerateThumbnailAction;
use App\Models\File;
use App\Models\Project;
use Illuminate\Console\Command;
use function ini_set;

class CreateThumbnailsForProjectImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-conv:create-thumbnails-for-project-images-command {projectId : Project Id for project to create thumbnails for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates thumbnails for existing image files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        $projectId = $this->argument("projectId");
        $this->createThumbnails(Project::findOrFail($projectId));
    }

    private function createThumbnails(Project $project)
    {
        $generateThumbnail = new GenerateThumbnailAction();
        File::where('project_id', $project->id)
            ->cursor()
            ->each(function (File $file) use ($generateThumbnail) {
                if ($file->isImage() && !$file->thumbnailExists()) {
                    echo "Creating thumbnail for file {$file->name}\n";
                    $generateThumbnail->execute($file);
                }
            });
    }
}
