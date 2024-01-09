<?php

namespace App\View\Components\Mql;

use App\Models\SavedQuery;
use App\Traits\Entities\EntityAndAttributeQueries;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use function auth;

class QueryBuilder extends Component
{
    use EntityAndAttributeQueries;

    public $category;
    public $activities;
    public $project;
    public $processAttributes;
    public $sampleAttributes;
    public $processAttributeDetails;
    public $sampleAttributeDetails;

    public function __construct($category, $activities, $project)
    {
        $this->category = $category;
        $this->activities = $activities;
        $this->project = $project;
    }

    public function render(): View|Closure|string
    {
        $this->processAttributes = $this->getProcessAttributes($this->project->id);
        $this->sampleAttributes = $this->getSampleAttributes($this->project->id);
        $this->processAttributeDetails = $this->getProcessAttrDetails($this->project->id);
        $this->sampleAttributeDetails = $this->getSampleAttrDetails($this->project->id);
        $query = "";
        return view('components.mql.query-builder', [
            'query'   => $query,
            'queries' => SavedQuery::where('owner_id', auth()->id())
                                   ->where('project_id', $this->project->id)
                                   ->get(),
        ]);
    }

    private function getProcessAttrDetails($projectId)
    {
        return $this->getAttrDetails("activity_attrs_by_proj_exp", $projectId);
    }

    private function getSampleAttrDetails($projectId)
    {
        return $this->getAttrDetails("entity_attrs_by_proj_exp", $projectId);
    }

    private function getAttrDetails($table, $projectId)
    {
        $selectRaw = "attribute_name as name, min(cast(attribute_value as real)) as min,".
            "max(cast(attribute_value as real)) as max,".
            "count(distinct attribute_value) as count";

        return DB::table($table)
                 ->selectRaw($selectRaw)
                 ->where("project_id", $projectId)
                 ->whereNotNull("attribute_name")
                 ->groupBy("attribute_name")
                 ->get();
    }
}
