<?php

namespace App\Livewire\Projects\Entities;

use App\Models\EntityState;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\Table\BaseLivewireTable;
use App\ViewModels\DataDictionary\AttributeStatistics;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use function route;
use function view;

class EntityAttributesTable extends Component
{
    use AttributeStatistics;
    use WithPagination;
    use BaseLivewireTable;

    public Project $project;
    public ?Experiment $experiment = null;
    public $category = 'experimental';

    public function render()
    {
        if (is_null($this->experiment)) {
            return $this->createProjectEntityAttributesView();
        }

        return $this->createExperimentEntityAttributesView();
    }

    public function entityAttributeRoute($attrName)
    {
        // Need to update to work for computations, and experiments
        return route('projects.entity-attributes.show', [$this->project, 'attribute' => $attrName]);
    }

    public function createProjectEntityAttributesView()
    {
        $attrs = $this->getUniqueEntityAttributesForProject();
        $attrs = $this->applySearch($attrs);

        return view('livewire.projects.entities.entity-attributes-table', [
            'entityAttributes' => $attrs->paginate(100),
        ]);
    }

    private function getUniqueEntityAttributesForProject(): Collection
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $this->project->id)
                       ->where('entities.category', $this->category)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->distinct()
                 ->orderBy('name')
                 ->get()
                 ->groupBy('name');
    }

    public function createExperimentEntityAttributesView()
    {
        $attrs = $this->getUniqueEntityAttributesForExperiment();
        $attrs = $this->applySearch($attrs);

        return view('livewire.projects.entities.entity-attributes-table', [
            'entityAttributes' => $attrs->paginate(100),
        ]);
    }

    private function getUniqueEntityAttributesForExperiment(): Collection
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $this->project->id)
                       ->where('entities.category', $this->category)
                       ->whereIn('entities.id',
                           DB::table('experiment2entity')->select('entity_id')->where('experiment_id',
                               $this->experiment->id))
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')
                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->distinct()
                 ->orderBy('name')
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
