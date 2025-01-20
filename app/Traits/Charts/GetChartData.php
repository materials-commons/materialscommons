<?php

namespace App\Traits\Charts;

use App\Models\Experiment;
use App\Models\Project;
use function collect;
use function is_null;
use function json_decode;

trait GetChartData
{
    private function getAttributeDataForProject($attrType, $attrName, Project $project)
    {
        if ($attrType == 'process') {
            $attrValues = $this->getActivityAttributeForProject($project->id, $attrName);
        } elseif ($attrType == 'sample') {
            $attrValues = $this->getEntityAttributeForProject($project->id, $attrName);
        } else {
            $attrValues = collect();
        }

        return $attrValues;
    }

    private function getAttributeDataForExperiment($attrType, $attrName, Experiment $experiment)
    {
        if ($attrType == 'process') {
            $attrValues = $this->getActivityAttributeForExperiment($experiment->id, $attrName);
        } elseif ($attrType == 'sample') {
            $attrValues = $this->getEntityAttributeForExperiment($experiment->id, $attrName);
        } else {
            $attrValues = collect();
        }

        return $attrValues;
    }

    private function createXYMatches($xattrValues, $yattrValues)
    {
        $xKeyName = "object_name";
        if (isset($xattrValues[0]->entity_name)) {
            $xKeyName = "entity_name";
        }

        $yKeyName = "object_name";
        if (isset($yattrValues[0]->entity_name)) {
            $yKeyName = "entity_name";
        }

        $xattrsByEntity = $xattrValues->keyBy($xKeyName);
        $yattrsByEntity = $yattrValues->keyBy($yKeyName);
        $xy = $xattrsByEntity->map(function ($xattr, $xattrKey) use ($yattrsByEntity, $xKeyName) {
            $yattr = $yattrsByEntity->get($xattrKey);
            if ($yattr) {
                $entry = new \stdClass();
                $entry->x = json_decode($xattr->val)->value;
                $entry->y = json_decode($yattr->val)->value;
                $entry->entity = $xattr->{$xKeyName};
                $entry->experiment_id = $xattr->experiment_id;
                return $entry;
            }
            return null;
        });
        return $xy->values()->filter(function ($entry) {
            return !is_null($entry);
        })->sortBy("x")->toArray();
    }
}