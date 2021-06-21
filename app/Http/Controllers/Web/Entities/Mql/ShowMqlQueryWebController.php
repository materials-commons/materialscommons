<?php

namespace App\Http\Controllers\Web\Entities\Mql;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlSelectionRequest;
use App\Models\Project;

class ShowMqlQueryWebController extends Controller
{
    public function __invoke(MqlSelectionRequest $request, Project $project)
    {
        $validated = $request->validated();
        $filters = "";
        if (isset($validated["activities"])) {
            $filters = $this->buildProcessFilters($validated["activities"]);
        }
        $processFilters = $this->buildAttributeFilters($validated["process_attrs"], 'process-attr');
        if ($processFilters != "") {
            if ($filters != "") {
                $filters = "{$filters}\nAND\n{$processFilters}";
            } else {
                $filters = $processFilters;
            }
        }
        $sampleFilters = $this->buildAttributeFilters($validated["sample_attrs"], 'sample-attr');
        if ($sampleFilters != "") {
            if ($filters != "") {
                $filters = "{$filters}\nAND\n{$sampleFilters}";
            } else {
                $filters = $sampleFilters;
            }
        }
        return view('partials.entities.mql._mql-textbox', [
            'project' => $project,
            'filters' => $filters,
        ]);
    }

    private function buildProcessFilters($activities): string
    {
        if (sizeof($activities) == 0) {
            return "";
        }

        $processFilters = "(";
        for ($i = 0; $i < sizeof($activities); $i++) {
            if ($i !== 0) {
                $processFilters .= " AND ";
            }
            $processFilters .= "has-process:'{$activities[$i]}'";
        }
        $processFilters .= ")";

        return $processFilters;
    }

    private function buildAttributeFilters($attrs, $attrType): string
    {
        $attrFilters = "";
        for ($i = 0; $i < sizeof($attrs); $i++) {
            $p = $attrs[$i];
            if (isset($p["name"])) {
                if ($attrFilters != "") {
                    $attrFilters .= " AND ";
                }
                $attrFilters .= "{$attrType}:'{$p['name']}' {$p['operator']} {$p['value']}";
            }
        }

        if ($attrFilters != "") {
            $attrFilters = "(".$attrFilters.")";
        }

        return $attrFilters;
    }
}
