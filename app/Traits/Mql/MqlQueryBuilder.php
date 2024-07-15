<?php

namespace App\Traits\Mql;

use Illuminate\Support\Str;
use function blank;

trait MqlQueryBuilder
{
    public function buildMqlQueryText($data)
    {
        $query = "";
        if (isset($data["activities"])) {
            $query = $this->buildProcessQueryPieces($data["activities"]);
        }

        if (isset($data["process_attrs"])) {
            $processAttributesQueryPart = $this->buildAttributeQueryPieces($data["process_attrs"], 'process-attr');
            if ($processAttributesQueryPart != "") {
                if ($query != "") {
                    $query = "{$query}\nAND\n{$processAttributesQueryPart}";
                } else {
                    $query = $processAttributesQueryPart;
                }
            }
        }

        if (isset($data["sample_attrs"])) {
            $sampleAttributesQueryPart = $this->buildAttributeQueryPieces($data["sample_attrs"], 'sample-attr');
            if ($sampleAttributesQueryPart != "") {
                if ($query != "") {
                    $query = "{$query}\nAND\n{$sampleAttributesQueryPart}";
                } else {
                    $query = $sampleAttributesQueryPart;
                }
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
            $activityName = $activities[$i];
            $isNot = Str::startsWith($activityName, '!');
            if ($isNot) {
                $activityName = substr($activityName, 1);
                $processQuery .= "not-has-process:'{$activityName}'";
            } else {
                $processQuery .= "has-process:'{$activityName}'";
            }
        }
        $processQuery .= ")";

        return $processQuery;
    }

    private function buildAttributeQueryPieces($attrs, $attrType): string
    {
        // $attrQuery is the full query
        $attrQuery = "";

        // $lastLine is the query line currently being constructed and is used
        // to determine if the line is too long. When this happens $attrQuery
        // gets a new line appended and $lastLine is set back to "".
        $lastLine = "";
        // Remove all invalid queries
        $validAttrs = collect($attrs)->filter(function ($attr) {
            return $this->isValidQuery($attr);
        })->toArray();

        if (count($validAttrs) != 0) {
            // After the collection the array is a hash table with indices that are not sequential. Use
            // array combine to create a correctly sequenced array.
            $validAttrs = array_combine(range(0, count($validAttrs) - 1), array_values($validAttrs));
        }

        // For each valid query construct the query line. We want the query line to
        // be a reasonable length so add a new line when the query line gets too long.
        for ($i = 0; $i < sizeof($validAttrs); $i++) {
            $p = $validAttrs[$i];
            if ($attrQuery != "") {
                $attrQuery .= " AND ";
                $lastLine .= " AND ";
            }
            $queryPart = "{$attrType}:'{$p['name']}' {$p['operator']} {$p['value']}";
            $attrQuery .= $queryPart;
            $lastLine .= $queryPart;
            if (Str::length($lastLine) > 50) {
                // The line being constructed is longer than 50, lets add a
                // \n to $attrQuery and reset $lastLine to "" only if we aren't
                // operating on the last attribute.
                if ($i == (sizeof($validAttrs) - 1)) {
                    // last attribute, so continue loop which will then exit the loop.
                    continue;
                }
                $attrQuery .= "\n";
                $lastLine = "";
            }
        }

        if ($attrQuery != "") {
            $attrQuery = "(".$attrQuery.")";
        }

        return $attrQuery;
    }

    private function isValidQuery($attrQuery)
    {
        if (!isset($attrQuery['name'])) {
            return false;
        }

        if (Str::of($attrQuery['operator'])->lower() == "select") {
            return false;
        }

        if (blank($attrQuery['value'])) {
            return false;
        }

        return true;
    }
}