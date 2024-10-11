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

        return response()->json([
            'xattrValues' => $xattrValues,
            'yattrValues' => $yattrValues,
        ]);
    }
}
