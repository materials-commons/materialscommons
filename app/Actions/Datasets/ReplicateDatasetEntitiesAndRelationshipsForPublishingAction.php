<?php

namespace App\Actions\Datasets;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\EntityState;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ReplicateDatasetEntitiesAndRelationshipsForPublishingAction
{
    public function execute(Dataset $dataset)
    {
        $this->replicateEntitiesAndRelatedItems($dataset);
        $syncAction = new SyncActivitiesToPublishedDatasetAction();
        $syncAction->execute($dataset);
    }

    private function replicateEntitiesAndRelatedItems(Dataset $dataset)
    {
        $dataset->entitiesFromTemplate()->each(function (Entity $entity) use ($dataset) {
            echo "replicating entity {$entity->id}\n";
            $entity->load('entityStates.attributes.values', 'activities.attributes.values', 'files');
            $e = $entity->replicate()->fill([
                'uuid'      => $this->uuid(),
                'copied_at' => Carbon::now(),
                'copied_id' => $entity->id,
            ]);
            $e->save();
            $dataset->entities()->attach($e);
            echo "  replicating its files\n";
            $this->attachReplicatedFilesToEntity($entity, $e, $dataset);
            echo "   replicating its states\n";
            $this->replicateEntityStatesAndRelationshipsForEntity($entity, $e);
            echo "   replicating its activities and their files\n";
            $this->replicateActivitiesAndRelationshipsForEntity($entity, $e, $dataset);
        });
    }

    private function attachReplicatedFilesToEntity(Entity $entity, Entity $e, Dataset $dataset)
    {
        $numberOfFiles = $entity->files->count();
        echo "         Adding entity {$entity->id} files ({$numberOfFiles})\n";
        $checksums = $entity->files->pluck('checksum')->toArray();
        DB::table('files')
          ->select('id', 'checksum')
          ->whereNull('project_id')
          ->whereIn('checksum', $checksums)
          ->distinct('checksum')
          ->get()
          ->pluck('id')
          ->chunk(1000)
          ->each(function ($ids) use ($e) {
              $e->files()->syncWithoutDetaching($ids);
          });
        echo "       Done adding entity files\n";
//        $entity->files->each(function (File $file) use ($e, $dataset) {
//            $f = File::where('checksum', $file->checksum)->whereIn('id', function ($query) use ($dataset) {
//                $query->select('file_id')->from('dataset2file')->where('dataset_id', $dataset->id);
//            })->first();
//            if (!is_null($f)) {
//                $e->files()->attach($f);
//            }
//        });
    }

    private function replicateEntityStatesAndRelationshipsForEntity(Entity $entity, Entity $e)
    {
        $entity->entityStates->each(function (EntityState $entityState) use ($e) {
            $es = $entityState->replicate()->fill([
                'uuid'      => $this->uuid(),
                'entity_id' => $e->id,
            ]);
            $es->save();
            $entityState->attributes->each(function (Attribute $attribute) use ($es) {
                $a = $attribute->replicate()->fill([
                    'uuid'            => $this->uuid(),
                    'attributable_id' => $es->id,
                ]);
                $a->save();
                $a->values->each(function (AttributeValue $attributeValue) use ($a) {
                    $av = $attributeValue->replicate()->fill([
                        'uuid'         => $this->uuid(),
                        'attribute_id' => $a->id,
                    ]);
                    $av->save();
                });
            });
        });
    }

    private function replicateActivitiesAndRelationshipsForEntity(Entity $entity, Entity $e, Dataset $dataset)
    {
        $entity->activities->each(function (Activity $activity) use ($e, $dataset) {
            echo "      replication activity {$activity->id}\n";
            $newActivity = $activity->replicate()->fill([
                'uuid'      => $this->uuid(),
                'copied_at' => Carbon::now(),
                'copied_id' => $activity->id,
            ]);
            $newActivity->save();
            $e->activities()->attach($newActivity);
            $activity->attributes->each(function (Attribute $attribute) use ($newActivity) {
                $a = $attribute->replicate()->fill([
                    'uuid'            => $this->uuid(),
                    'attributable_id' => $newActivity->id,
                ]);
                $a->save();
                $a->values->each(function (AttributeValue $attributeValue) use ($a) {
                    $av = $attributeValue->replicate()->fill([
                        'uuid'         => $this->uuid(),
                        'attribute_id' => $a->id,
                    ]);
                    $av->save();
                });
            });
            echo "           replicating activity files\n";
            $this->attachReplicatedFilesToActivity($activity, $newActivity, $dataset);
        });
    }

    private function attachReplicatedFilesToActivity(Activity $activity, Activity $newActivity, Dataset $dataset)
    {
        $activity->load('files');
        $numberOfFiles = $activity->files->count();
        echo "         Adding activity {$activity->id} files ({$numberOfFiles})\n";
        $checksums = $activity->files->pluck('checksum')->toArray();
        DB::table('files')
          ->select('id', 'checksum')
          ->whereNull('project_id')
          ->whereIn('checksum', $checksums)
          ->distinct('checksum')
          ->get()
          ->pluck('id')
          ->chunk(1000)
          ->each(function ($ids) use ($newActivity) {
              $newActivity->files()->syncWithoutDetaching($ids);
          });
        echo "          Done adding files to activity\n";
//        $numberOfFiles = $activity->files->count();
//        $activity->files->each(function (File $file) use ($newActivity, $dataset) {
//            $f = File::where('checksum', $file->checksum)
//                     ->whereIn('id', function ($query) use ($dataset) {
//                         $query->select('file_id')->from('dataset2file')->where('dataset_id', $dataset->id);
//                     })->first();
//            if (!is_null($f)) {
//                $newActivity->files()->syncWithoutDetaching([$f]);
//            }
//        });
    }

    // $checksums = Activity::with('files')
//     ->find(494)
//     ->files->pluck('checksum')
//     ->toArray();

// DB::table('files')
//   ->select('id', 'name', 'project_id', 'checksum')
//   ->whereNull('project_id')
//   ->whereIn('checksum', $checksums)
//   ->get();

    private function uuid()
    {
        return Uuid::uuid4()->toString();
    }
}