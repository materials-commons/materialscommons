<?php

namespace App\Http\Controllers\Web\DataDictionary;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\EntityState;
use App\Models\Project;
use App\ViewModels\DataDictionary\ShowDataDictionaryViewModel;
use Illuminate\Support\Facades\DB;

class IndexDataDictionaryForProjectWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $viewModel = (new ShowDataDictionaryViewModel())
            ->withProject($project)
            ->withActivityAttributes($this->getUniqueActivityAttributes($project->id))
            ->withEntityAttributes($this->getUniqueEntityAttributes($project->id));
        return view('app.projects.data-dictionary.index', $viewModel);
    }

    private function getUniqueActivityAttributes($projectId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('activities')
                       ->select('id')
                       ->where('project_id', $projectId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    private function getUniqueEntityAttributes($projectId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $projectId)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }
}
