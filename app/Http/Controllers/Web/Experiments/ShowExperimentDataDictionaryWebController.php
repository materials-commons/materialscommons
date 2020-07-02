<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Models\Activity;
use App\Models\EntityState;
use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\DataDictionary\ShowExperimentDataDictionaryViewModel;
use Illuminate\Support\Facades\DB;

class ShowExperimentDataDictionaryWebController
{
    use ExcelFilesCount;

    public function __invoke(Project $project, Experiment $experiment)
    {
        $viewModel = (new ShowExperimentDataDictionaryViewModel())
            ->withProject($project)
            ->withExperiment($experiment)
            ->withExcelFilesCount($this->getExcelFilesCount($project))
            ->withActivityAttributes($this->getUniqueActivityAttributes($experiment->id))
            ->withEntityAttributes($this->getUniqueEntityAttributes($experiment->id));
        return view('app.projects.experiments.show', $viewModel);
    }

    private function getUniqueActivityAttributes($experimentId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2activity')
                       ->select('activity_id')
                       ->where('experiment_id', $experimentId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    private function getUniqueEntityAttributes($experimentId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2entity')
                       ->select('entity_states.id')
                       ->where('experiment_id', $experimentId)
                       ->join('entity_states', 'experiment2entity.entity_id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }
}