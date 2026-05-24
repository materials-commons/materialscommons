<?php

namespace App\Http\Controllers\Web\Entities;

use App\DTO\Activities\AttributesComparerState;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use function collect;

class CompareActivitiesWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $activity1Id = $request->input('activity1');
        $activity2Id = $request->input('activity2');

        if (!$activity1Id || !$activity2Id) {
            return redirect()->back()->with('error', 'Please select two activities to compare.');
        }

        $activity1 = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files', 'entities'])
                             ->findOrFail($activity1Id);
        $activity2 = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files', 'entities'])
                             ->findOrFail($activity2Id);

        $activityAttributesState = $this->buildAttributesCompareState($activity1->attributes, $activity2->attributes);

        $activity1EntityStateOutAttributes = $this->collectOutAttributesForEntityStates($activity1->entityStates);
        $activity2EntityStateOutAttributes = $this->collectOutAttributesForEntityStates($activity2->entityStates);
        $entityAttributesState = $this->buildAttributesCompareState($activity1EntityStateOutAttributes,
            $activity2EntityStateOutAttributes);

        return view('app.projects.activities.compare', [
            'project'                           => $project,
            'activity1'                         => $activity1,
            'activity2'                         => $activity2,
            'activityAttributesState'           => $activityAttributesState,
            'entityAttributesState'             => $entityAttributesState,
            'activity1EntityStateOutAttributes' => $activity1EntityStateOutAttributes,
            'activity2EntityStateOutAttributes' => $activity2EntityStateOutAttributes,
        ]);
    }

    private function collectOutAttributesForEntityStates($entityStates): Collection
    {
        return $entityStates
            ->filter(function ($entityState) {
                return $entityState->pivot->direction === "out";
            })
            ->flatMap(function ($entityState) {
                return $entityState->attributes;
            });
    }

    private function buildAttributesCompareState($attributes1, $attributes2): AttributesComparerState
    {
        $state = new AttributesComparerState();

        // Compare attributes
        $attributes1ByName = collect($attributes1)->keyBy('name');
        $attributes2ByName = collect($attributes2)->keyBy('name');

        // Attributes in both activities
        $commonAttributes = $attributes1ByName->keys()->intersect($attributes2ByName->keys());

        // Attributes only in activity1
        $state->activity1OnlyAttributes = $attributes1ByName->keys()->diff($attributes2ByName->keys());

        // Attributes only in activity2
        $state->activity2OnlyAttributes = $attributes2ByName->keys()->diff($attributes1ByName->keys());

        // Attributes with different values and same values
        foreach ($commonAttributes as $attributeName) {
            $attr1 = $attributes1ByName[$attributeName];
            $attr2 = $attributes2ByName[$attributeName];

            $value1 = $attr1->values[0]->val["value"] ?? null;
            $value2 = $attr2->values[0]->val["value"] ?? null;
            $unit1 = $attr1->values[0]->unit ?? "";
            $unit2 = $attr2->values[0]->unit ?? "";

            if ($value1 != $value2) {
                $state->differentValueAttributes->push($attributeName);
                $state->changedAttributeValues->put($attributeName, [
                    'old' => ['value' => $value1, 'unit' => $unit1],
                    'new' => ['value' => $value2, 'unit' => $unit2],
                ]);
            } else {
                $state->sameAttributes->put($attributeName, [
                    'value' => $value1,
                    'unit' => $unit1,
                ]);
            }
        }

        return $state;
    }
}