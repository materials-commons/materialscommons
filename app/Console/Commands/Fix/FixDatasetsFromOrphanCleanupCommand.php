<?php

namespace App\Console\Commands\Fix;

use App\Actions\Datasets\UnpublishDatasetAction;
use App\Models\Dataset;
use Illuminate\Console\Command;

class FixDatasetsFromOrphanCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-fix:fix-datasets-from-orphan-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Republishes datasets that were messed up during orphan cleanup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Not needed 119
        // 155 Had 13 samples, now has 15
        // 193 Had 28 samples, now has 14, looks correct now
        // 2481 had 421 files, now has 11980
        // 2564 had 157 samples, now has 132
        $idsToFix = [6, 119, 155, 193, 220, 237, 2495, 2543, 2554, 2564, 2565, 2579];
//        $datasets = Dataset::with('owner')
//                           ->whereIn('id', function ($query) {
//                               $query->select('item_id')
//                                     ->distinct()
//                                     ->from('item2entity_selection');
//                           })
//                           ->whereNotNull('published_at')
//                           ->get();
        $datasets = Dataset::with('owner')
                           ->whereIn('id', $idsToFix)
                           ->whereNotNull('published_at')
                           ->get();

        $unpublishDatasetAction = new UnpublishDatasetAction();
        foreach ($datasets as $dataset) {
            $user = $dataset->owner;
            $publishedAt = $dataset->published_at;
            echo "Republishing dataset {$dataset->name} ({$dataset->id}) for user {$user->name} at {$publishedAt}\n";
            $unpublishDatasetAction($dataset, $user, true);
            $dataset->refresh();
            $dataset->update(['published_at' => $publishedAt]);
        }

    }
}
