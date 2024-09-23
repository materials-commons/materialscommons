<?php

namespace App\Traits\Attributes;

use App\Models\Activity;
use App\Models\AttributeValue;
use App\Models\EntityState;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait AttributeDetails
{
    public function getAttributeActivityNames($projectId, $attrName): Collection
    {
        return DB::table("activities")
                 ->select("name", "category")
                 ->distinct()
                 ->where("project_id", $projectId)
                 ->whereIn(
                     "id",
                     DB::table("attributes")
                       ->select("attributable_id")
                       ->where("attributable_type", Activity::class)
                       ->where("name", $attrName)
                 )
                 ->get();
    }

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

    public function getAttributeEntityNames($projectId, $attrName): Collection
    {
        return DB::table("entities")
                 ->select("name", "category", "id")
                 ->distinct()
                 ->where("project_id", $projectId)
                 ->whereIn(
                     "id",
                     DB::table("entity_states")
                       ->select("entity_id")
                       ->whereIn(
                           "id",
                           DB::table("attributes")
                             ->select("attributable_id")
                             ->where("attributable_type", EntityState::class)
                             ->where("name", $attrName)
                       )
                 )
                 ->get();
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

        $details->uniqueCount = $values->unique()->count();
        $details->values = $values;
        $details->valuesToShow = $values;

        if ($details->uniqueCount > 10) {
            $details->valuesToShow = $values->take(10);
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