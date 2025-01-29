<?php

namespace App\Livewire\Projects\Activities;

use App\Models\Activity;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Table\BaseLivewireTable;
use App\ViewModels\DataDictionary\AttributeStatistics;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use function preg_match;
use function route;
use function view;

class ActivityAttributesTable extends Component
{
    use AttributeStatistics;
    use WithPagination;
    use BaseLivewireTable;

    public Project $project;
    public ?Experiment $experiment = null;
    public $category = 'experimental';

    public function mount()
    {
        $this->sortAsc = true;
    }

    public function render()
    {
        if (is_null($this->experiment)) {
            return $this->renderProjectActivities();
        }

        return $this->renderExperimentActivities();
    }

    public function activityAttributeRoute($attrName)
    {
        return route('projects.activity-attributes.show', [$this->project, 'attribute' => $attrName]);
    }

    private function renderProjectActivities()
    {
        $attrs = $this->getUniqueActivityAttributesForProject();
        $attrs = $this->applySearch($attrs);
        return view('livewire.projects.activities.activity-attributes-table', [
            'activityAttributes' => $attrs->paginate(100),
        ]);
    }

    private function renderExperimentActivities()
    {
        $attrs = $this->getUniqueActivityAttributesForExperiment();
        $attrs = $this->applySearch($attrs);
        return view('livewire.projects.activities.activity-attributes-table', [
            'activityAttributes' => $attrs->paginate(100),
        ]);
    }

    private function getUniqueActivityAttributesForProject()
    {
        $order = 'asc';
        if (!$this->sortAsc) {
            $order = 'desc';
        }

        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('activities')
                       ->select('id')
                       ->where('project_id', $this->project->id)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('name', $order)
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    private function getUniqueActivityAttributesForExperiment()
    {
        $order = 'asc';
        if (!$this->sortAsc) {
            $order = 'desc';
        }

        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2activity')
                       ->select('activity_id')
                       ->where('experiment_id', $this->experiment->id)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('name', $order)
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    private function applySearch($collection)
    {
        if ($this->search != '') {
            return $collection->filter(function ($item, $key) {
                return preg_match('/'.$this->search.'/i', $key);
            });
        }

        return $collection;
    }
}
