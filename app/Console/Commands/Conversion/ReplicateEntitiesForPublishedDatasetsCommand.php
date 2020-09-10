<?php

namespace App\Console\Commands\Conversion;

use App\Actions\Datasets\ReplicateDatasetEntitiesAndRelationshipsForPublishingAction;
use App\Models\Dataset;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReplicateEntitiesForPublishedDatasetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:replicate-entities-for-published-datasets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var ReplicateDatasetEntitiesAndRelationshipsForPublishingAction */
    private $replicateAction;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->replicateAction = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // For all datasets create an entity template for it
        Dataset::with('entities.experiments')->cursor()->each(function (Dataset $dataset) {
            $this->info("Creating entity template for dataset {$dataset->name}/{$dataset->id}\n");
            $this->createEntityTemplate($dataset);
            $dataset->entities()->detach();
            $dataset->activities()->detach();
        });

        // For published datasets clean out existing entity and activity relationships and then sync
        Dataset::whereNotNull('published_at')->get()->each(function (Dataset $dataset) {
            $this->info("Replicating entities and activites for published dataset {$dataset->name}/{$dataset->id}\n");
            $this->replicateAction->execute($dataset);
        });
    }

    private function createEntityTemplate(Dataset $dataset)
    {
        $dataset->entities()->whereNull('copied_id')->each(function (Entity $entity) use ($dataset) {
            $experiment = $entity->experiments()->first();
            if (!is_null($experiment)) {
                DB::table('item2entity_selection')->insert([
                    'item_type'     => Dataset::class,
                    'item_id'       => $dataset->id,
                    'entity_name'   => $entity->name,
                    'experiment_id' => $experiment->id,
                ]);
            } else {
                DB::table('item2entity_selection')->insert([
                    'item_type' => Dataset::class,
                    'item_id'   => $dataset->id,
                    'entity_id' => $entity->id,
                ]);
            }
        });
    }
}
