<?php

namespace App\Console\Commands\Fix;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanOrphansCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-fix:clean-orphans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clean's orphans from the database.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "This is broken.... doing nothing for now...\n";
        if (true) {
            return;
        }
        // Clean out all orphaned activities and entities that aren't associated with a dataset
        // or an experiment.
        $this->cleanOrphanedActivities();
        $this->cleanOrphanedEntities();

        // Clean out all Attributes that aren't associated with an existing Activity or EntityState
//        $this->cleanOrphanedAttributes('activities', Activity::class);
//        $this->cleanOrphanedAttributes('entity_states', EntityState::class);
    }

    private function cleanOrphanedAttributes($table, $modelClass): void
    {
        $baseModelName = class_basename($modelClass);

        $this->info("Calling count for {$baseModelName} attributes");
        $count = Attribute::where('attributable_type', $modelClass)
                          ->whereNotExists(function ($query) use ($table) {
                              $query->select('*')
                                    ->from($table)
                                    ->whereColumn("${table}.id", 'attributes.attributable_id');
                          })
                          ->count();

        $this->info("Found {$count} orphaned {$baseModelName} attributes to delete");

        $deleted = Attribute::where('attributable_type', $modelClass)
                            ->whereNotExists(function ($query) use ($table) {
                                $query->select('*')
                                      ->from($table)
                                      ->whereColumn("${table}.id", 'attributes.attributable_id');
                            })
                            ->chunkById(10000, function ($attributes) {
                                DB::table('attributes')->whereIn('id', $attributes->pluck('id'))->delete();
                            });

        $this->info("Deleted {$deleted} orphaned {$baseModelName} attributes");
    }

    private function cleanOrphanedActivities(): void
    {
        $this->cleanOrphanedDatasetActivities();
//        $this->cleanOrphanedExperimentActivities();
    }

    private function cleanOrphanedDatasetActivities(): void
    {
        $count = Activity::whereNotNull("dataset_id")
                         ->whereDoesntHave("datasets")
                         ->count();
        $this->info("Found {$count} orphaned dataset activities to delete");
        $deleted = Activity::whereNotNull("dataset_id")
                           ->whereDoesntHave("datasets")
                           ->delete();
        $this->info("Deleted {$deleted} orphaned dataset activities");
    }

    private function cleanOrphanedExperimentActivities(): void
    {
        $count = Activity::whereDoesntHave("experiments")
                         ->count();
        $this->info("Found {$count} orphaned experiment activities to delete");
        $deleted = Activity::whereDoesntHave("experiments")
                           ->delete();
        $this->info("Deleted {$deleted} orphaned experiment activities");
    }

    private function cleanOrphanedEntities(): void
    {
        $this->cleanOrphanedDatasetEntities();
//        $this->cleanOrphanedExperimentEntities();
    }

    private function cleanOrphanedDatasetEntities(): void
    {
        $count = Entity::whereNotNull("dataset_id")
                       ->whereDoesntHave("datasets")
                       ->count();
        $this->info("Found {$count} orphaned dataset entities to delete");
        $deleted = Entity::whereNotNull("dataset_id")
                         ->whereDoesntHave("datasets")
                         ->delete();
        $this->info("Deleted {$deleted} orphaned dataset entities");

    }

    private function cleanOrphanedExperimentEntities(): void
    {
        $count = Entity::whereDoesntHave("experiments")
                       ->count();
        $this->info("Found {$count} orphaned experiment entities to delete");
        $deleted = Entity::whereDoesntHave("experiments")
                         ->delete();
        $this->info("Deleted {$deleted} orphaned experiment entities");
    }
}
