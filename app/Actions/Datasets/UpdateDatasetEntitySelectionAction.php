<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;

class UpdateDatasetEntitySelectionAction
{
    public function update(Entity $entity, Dataset $dataset)
    {
        $experiment = $entity->experiments()->first();
        $count = $this->getCount($dataset, $entity, $experiment);
        $entity->load('files.directory');
        if ($count === 0) {
            $this->addEntityToSelection($dataset, $entity, $experiment);
        } else {
            $this->removeEntityFromSelection($dataset, $entity, $experiment);
        }

        return $dataset;
    }

    public function datasetHasEntity($dataset, $entity)
    {
        $experiment = $entity->experiments()->first();
        return $this->getCount($dataset, $entity, $experiment) > 0;
    }

    private function addEntityToSelection($dataset, $entity, $experiment)
    {
        DB::table('item2entity_selection')->insert([
            'item_type'     => Dataset::class,
            'item_id'       => $dataset->id,
            'entity_name'   => $entity->name,
            'experiment_id' => $experiment->id,
        ]);
        $this->addEntityFilesToDataset($entity, $dataset);
    }

    private function addEntityFilesToDataset($entity, $dataset)
    {
        $updateDatasetFileSelectionAction = new UpdateDatasetFileSelectionAction();
        $entity->files->each(function ($file) use ($updateDatasetFileSelectionAction, $dataset) {
            if ($file->isDir()) {
                $updateDatasetFileSelectionAction(["include_dir" => $file->path], $dataset);
            } else {
                $updateDatasetFileSelectionAction(["include_file" => $file->toPath($file->directory->path)],
                    $dataset);
            }
        });
    }

    private function removeEntityFromSelection($dataset, $entity, $experiment)
    {
        $this->createQuery($dataset, $entity, $experiment)->delete();
        $this->removeEntityFilesFromDataset($entity, $dataset);
    }

    private function removeEntityFilesFromDataset($entity, $dataset)
    {
        $updateDatasetFileSelectionAction = new UpdateDatasetFileSelectionAction();
        $entity->files->each(function ($file) use ($updateDatasetFileSelectionAction, $dataset) {
            if ($file->isDir()) {
                $updateDatasetFileSelectionAction(["remove_include_dir" => $file->path], $dataset);
            } else {
                $updateDatasetFileSelectionAction(["remove_include_file" => $file->toPath($file->directory->path)],
                    $dataset);
            }
        });
    }

    private function getCount($dataset, $entity, $experiment)
    {
        return $this->createQuery($dataset, $entity, $experiment)->count();
    }

    private function createQuery($dataset, $entity, $experiment)
    {
        return DB::table('item2entity_selection')
                 ->where('item_type', Dataset::class)
                 ->where('item_id', $dataset->id)
                 ->where('entity_name', $entity->name)
                 ->where('experiment_id', $experiment->id);
    }
}