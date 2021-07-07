<?php

namespace App\Traits\Attributes;

use App\Models\Activity;
use App\Models\AttributeValue;
use App\Models\EntityState;
use Illuminate\Support\Facades\DB;

trait AttributeDetails
{
    public function getProcessAttributeDetails($projectId, $name): \stdClass
    {
        $values = AttributeValue::select('val')
                                ->distinct()
                                ->whereIn(
                                    'attribute_id',
                                    DB::table('attributes')
                                      ->select('id')
                                      ->where('name', $name)
                                      ->where("attributable_type", Activity::class)
                                      ->whereIn(
                                          "attributable_id",
                                          DB::table('activities')
                                            ->select('id')
                                            ->where('project_id', $projectId)
                                      )
                                )
                                ->get()
                                ->map(function ($av) {
                                    return $av->val['value'];
                                })
                                ->sort();

        return $this->createDetails($values);
    }

    public function getSampleAttributeDetails($projectId, $name): \stdClass
    {
        $values = AttributeValue::select('val')
                                ->distinct()
                                ->whereIn(
                                    'attribute_id',
                                    DB::table('attributes')
                                      ->select('id')
                                      ->where('name', $name)
                                      ->where("attributable_type", EntityState::class)
                                      ->whereIn(
                                          "attributable_id",
                                          DB::table('entity_states')
                                            ->select('id')
                                            ->whereIn('entity_id',
                                                DB::table('entities')
                                                  ->select('id')
                                                  ->where('project_id', $projectId))
                                      )
                                )
                                ->get()
                                ->map(function ($av) {
                                    return $av->val['value'];
                                })
                                ->sort();

        return $this->createDetails($values);
    }

    private function createDetails($values)
    {
        $details = new \stdClass();
        $details->isNumeric = $this->isNumeric($values);

        if ($details->isNumeric) {
            $details->min = $values->min();
            $details->max = $values->max();
        }

        $details->uniqueCount = $values->count();
        $details->values = $values;

        if ($details->uniqueCount > 10) {
            $details->values = $values->take(10);
        }

        return $details;
    }

    private function isNumeric($values)
    {
        return $values->every(function ($value) {
            return is_numeric($value);
        });
    }
}