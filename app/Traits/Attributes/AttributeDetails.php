<?php

namespace App\Traits\Attributes;

use App\Models\Activity;
use App\Models\AttributeValue;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;

trait AttributeDetails
{
    public function getAttributeDetails($projectId, $name, $table = "entities"): \stdClass
    {
        $attributableType = Entity::class;

        if ($table == "activities") {
            $attributableType = Activity::class;
        }

        $values = AttributeValue::select('val')
                                ->distinct()
                                ->whereIn(
                                    'attribute_id',
                                    DB::table('attributes')
                                      ->select('id')
                                      ->where('name', $name)
                                      ->where("attributable_type", $attributableType)
                                      ->whereIn(
                                          "attributable_id",
                                          DB::table($table)
                                            ->select('id')
                                            ->where('project_id', $projectId)
                                      )
                                )
                                ->get()
                                ->map(function ($av) {
                                    return $av->val['value'];
                                })
                                ->sort();

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