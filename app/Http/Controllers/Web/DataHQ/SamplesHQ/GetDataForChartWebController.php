<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use Illuminate\Http\Request;

class GetDataForChartWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'xattr'      => 'required|string',
            'xattr_type' => 'required|in:process,sample,computation',
            'yattr'      => 'required|string',
            'yattr_type' => 'required|in:process,sample,computation',
        ]);

        if ($validatedData['xattr_type'] == 'process') {
            $xattrValues = $this->getActivityAttributeForProject($project->id, $validatedData['xattr']);
        } elseif ($validatedData['xattr_type'] == 'sample') {
            $xattrValues = $this->getEntityAttributeForProject($project->id, $validatedData['xattr']);
        } else {
            $xattrValues = collect();
        }

        if ($validatedData['yattr_type'] == 'process') {
            $yattrValues = $this->getActivityAttributeForProject($project->id, $validatedData['yattr']);
        } elseif ($validatedData['yattr_type'] == 'sample') {
            $yattrValues = $this->getEntityAttributeForProject($project->id, $validatedData['yattr']);
        } else {
            $yattrValues = collect();
        }

        $xyMatches = $this->createXYMatches($xattrValues->get($validatedData['xattr']),
            $yattrValues->get($validatedData['yattr']));
        return response()->json(array_values($xyMatches));
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
                return $entry;
            }
            return null;
        });
        return $xy->values()->filter(function ($entry) {
            return !is_null($entry);
        })->sortBy("x")->toArray();
    }

}
