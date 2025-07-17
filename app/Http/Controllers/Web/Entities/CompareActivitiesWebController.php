<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;

class CompareActivitiesWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $activity1Id = $request->input('activity1');
        $activity2Id = $request->input('activity2');

        if (!$activity1Id || !$activity2Id) {
            return redirect()->back()->with('error', 'Please select two activities to compare.');
        }

        $activity1 = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files'])
                            ->findOrFail($activity1Id);
        $activity2 = Activity::with(['attributes.values', 'entityStates.attributes.values', 'files'])
                            ->findOrFail($activity2Id);

        // Compare attributes
        $activity1Attributes = collect($activity1->attributes)->keyBy('name');
        $activity2Attributes = collect($activity2->attributes)->keyBy('name');

        // Attributes in both activities
        $commonAttributes = $activity1Attributes->keys()->intersect($activity2Attributes->keys());
        
        // Attributes only in activity1
        $activity1OnlyAttributes = $activity1Attributes->keys()->diff($activity2Attributes->keys());
        
        // Attributes only in activity2
        $activity2OnlyAttributes = $activity2Attributes->keys()->diff($activity1Attributes->keys());
        
        // Attributes with different values
        $differentValueAttributes = collect();
        foreach ($commonAttributes as $attributeName) {
            $attr1 = $activity1Attributes[$attributeName];
            $attr2 = $activity2Attributes[$attributeName];
            
            $value1 = $attr1->values[0]->val["value"] ?? null;
            $value2 = $attr2->values[0]->val["value"] ?? null;
            
            if ($value1 != $value2) {
                $differentValueAttributes->push($attributeName);
            }
        }

        return view('app.projects.activities.compare', [
            'project' => $project,
            'activity1' => $activity1,
            'activity2' => $activity2,
            'commonAttributes' => $commonAttributes,
            'activity1OnlyAttributes' => $activity1OnlyAttributes,
            'activity2OnlyAttributes' => $activity2OnlyAttributes,
            'differentValueAttributes' => $differentValueAttributes,
        ]);
    }
}