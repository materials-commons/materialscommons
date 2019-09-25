<?php

namespace App\Actions\Etl;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use Illuminate\Support\Facades\DB;

class AddEntityAndFilesToActivityAction
{
    public function __invoke($data)
    {
        $getFileByPathAction = new GetFileByPathAction();
        $fileEntries = collect();
        $projectId = $data["project_id"];
        foreach ($data["files_by_name"] as $f) {
            $file = $getFileByPathAction($projectId, $f["path"]);
            if ($file !== null) {
                $fileEntries->put($file->id, ['direction' => $f["direction"]]);
            }
        }

        $transform = $data['transform'];
        $entityId = $data['sample_id'];
        $entityStateId = $data['property_set_id'];
        $entity = Entity::find($entityId);
        $activity = Activity::find($data["process_id"]);
        DB::transaction(function() use ($activity, $fileEntries, $entity, $entityStateId, $transform) {
            $activity->entityStates()->attach([$entityStateId => ['direction' => 'in']]);
            $activity->files()->attach($fileEntries);
            $activity->entities()->attach($entity);
            $fileIds = $fileEntries->keys();
            $entity->files()->attach($fileIds);
            if ($transform) {
                $es = EntityState::create(['entity_id' => $entity->id, 'owner_id' => auth()->id()]);
                $activity->entityStates()->attach([$es->id => ['direction' => 'out']]);
            }
        });

        return $entity;
    }
}