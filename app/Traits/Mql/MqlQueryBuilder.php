<?php

namespace App\Traits\Mql;

trait MqlQueryBuilder
{
    public function buildMqlQueryText($data)
    {
        $query = "";
        if (isset($data["activities"])) {
            $query = $this->buildProcessQueryPieces($data["activities"]);
        }

        $processAttributesQueryPart = $this->buildAttributeQueryPieces($data["process_attrs"], 'process-attr');
        if ($processAttributesQueryPart != "") {
            if ($query != "") {
                $query = "{$query}\nAND\n{$processAttributesQueryPart}";
            } else {
                $query = $processAttributesQueryPart;
            }
        }

        $sampleAttributesQueryPart = $this->buildAttributeQueryPieces($data["sample_attrs"], 'sample-attr');
        if ($sampleAttributesQueryPart != "") {
            if ($query != "") {
                $query = "{$query}\nAND\n{$sampleAttributesQueryPart}";
            } else {
                $query = $sampleAttributesQueryPart;
            }
        }

        return $query;
    }

    private function buildProcessQueryPieces($activities): string
    {
        if (sizeof($activities) == 0) {
            return "";
        }

        $processQuery = "(";
        for ($i = 0; $i < sizeof($activities); $i++) {
            if ($i !== 0) {
                $processQuery .= " AND ";
            }
            $processQuery .= "has-process:'{$activities[$i]}'";
        }
        $processQuery .= ")";

        return $processQuery;
    }

    private function buildAttributeQueryPieces($attrs, $attrType): string
    {
        $attrQuery = "";
        for ($i = 0; $i < sizeof($attrs); $i++) {
            $p = $attrs[$i];
            if (isset($p["name"])) {
                if ($attrQuery != "") {
                    $attrQuery .= " AND ";
                }
                $attrQuery .= "{$attrType}:'{$p['name']}' {$p['operator']} {$p['value']}";
            }
        }

        if ($attrQuery != "") {
            $attrQuery = "(".$attrQuery.")";
        }

        return $attrQuery;
    }
}