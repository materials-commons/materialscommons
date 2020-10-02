<?php

namespace App\Console\Commands\Conversion;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use Illuminate\Console\Command;

class FixFileRelationshipsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:fix-file-relationships';

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
        $this->fixActivities();
        $this->fixEntities();
        $this->fixExperiments();
        echo "Done!\n";
        return 0;
    }

    private function fixActivities()
    {
        foreach (Activity::cursor() as $activity) {
            $this->fixFileRelationshipsForItem($activity);
        }
    }

    private function fixEntities()
    {
        foreach (Entity::cursor() as $entity) {
            $this->fixFileRelationshipsForItem($entity);
        }
    }

    private function fixExperiments()
    {
        foreach (Experiment::cursor() as $experiment) {
            $this->fixFileRelationshipsForItem($experiment);
        }
    }

    private function fixFileRelationshipsForItem($item)
    {
        $cls = get_class($item);
        echo "Fixing {$cls} {$item->name}/{$item->id}\n";
        $item->load('files');
        $item->files->each(function (File $file) use ($item, $cls) {
            if (is_null($file->project_id)) {
                // Replicated files in datasets may have null project_id, skip them.
                return;
            }

            if ($file->project_id == $item->project_id) {
                // If the projects match then there is nothing to fix.
                return;
            }

            // If we are here then the wrong file was attached to the item.
            echo "...{$cls} {$item->name}/{$item->id} has wrong file {$file->name}/{$file->id}\n";

            $correctFile = File::where('project_id', $item->project_id)
                               ->where('uses_uuid', $file->uses_uuid)
                               ->first();

            if (!is_null($correctFile)) {
                echo "......Corrected to file {$correctFile->name}/{$correctFile->id}\n";
                $item->files()->attach($correctFile);
            }

            $item->files()->detach($file);
        });
    }
}
