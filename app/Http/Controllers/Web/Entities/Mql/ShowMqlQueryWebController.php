<?php

namespace App\Http\Controllers\Web\Entities\Mql;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlSelectionRequest;
use App\Models\Project;

class ShowMqlQueryWebController extends Controller
{
    public function __invoke(MqlSelectionRequest $request, Project $project)
    {
        ray("ShowMqlQueryWebController");
        $validated = $request->validated();
        ray("  request = ", $validated);
        $filters = "";
        if (isset($validated["activities"])) {
            $filters = $this->buildProcessFilters($validated["activities"]);
        }
        ray("sample_attrs[0] = ", $validated["sample_attrs"][0]);
        $filters .= $this->buildProcessAttributesFilters($validated["process_attrs"], $filters == "");
        $filters .= $this->buildSampleAtttributesFilters($validated["sample_attrs"]);
        return view('partials.entities.mql._mql-textbox', [
            'filters' => $filters,
        ]);
    }

    private function buildProcessFilters($activities)
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

    private function buildProcessAttributesFilters($processAttrs, $hasProcessFilters)
    {
        $processAttrFilters = "";
        for ($i = 0; $i < sizeof($processAttrs); $i++) {
            $p = $processAttrs[$i];
            if (isset($p["name"])) {
                if ($processAttrFilters != "") {
                    $processAttrFilters .= " AND ";
                }
                $processAttrFilters .= " process-attr:'{$p['name']}' {$p['operator']} {$p['value']}";
            }
        }

        if ($processAttrFilters != "") {
            $processAttrFilters = "(".$processAttrFilters." )";
            if ($hasProcessFilters) {
                $processAttrFilters = "\n AND\n".$processAttrFilters;
            }
        }

        return $processAttrFilters;
    }

    private function buildSampleAtttributesFilters($sampleAttrs)
    {
        return " Sample Attrs here ";
    }
}
