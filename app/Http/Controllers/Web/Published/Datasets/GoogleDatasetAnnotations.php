<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Support\Str;

trait GoogleDatasetAnnotations
{
    public function jsonLDAnnotations(Dataset $dataset)
    {
        $jsonld = [
            "@context"    => "https://schema.org/",
            "@type"       => "Dataset",
            "name"        => $dataset->name,
            "description" => $this->getDescription($dataset),
            "url"         => config('app.url')."/public/datasets/{$dataset->id}",
        ];

        if (!$this->isBlank($dataset->license)) {
            $jsonld["license"] = $this->getLicenseUrl($dataset->license);
        }

        if (!$this->isBlank($dataset->doi)) {
            $jsonld["identifier"] = $this->getDoiUrl($dataset->doi);
        }

        if (!is_null($dataset->tags)) {
            $jsonld["keywords"] = [];
            foreach ($dataset->tags as $tag) {
                array_push($jsonld["keywords"], $tag->name);
            }
        }

        $jsonld["creator"] = [$this->createAuthor($dataset->owner)];

        return $jsonld;
    }

    private function getDescription(Dataset $dataset)
    {
        if ($this->isBlank($dataset->summary)) {
            return $dataset->description;
        }

        return $dataset->summary;
    }

    private function isBlank($what)
    {
        if (is_null($what)) {
            return true;
        }

        if ($what == "") {
            return true;
        }

        return false;
    }

    private function getLicenseUrl($license)
    {
        switch ($license) {
            case "Public Domain Dedication and License (PDDL)":
                return "https://opendatacommons.org/licenses/pddl/index.html";
            case "Attribution License (ODC-By)":
                return "https://opendatacommons.org/licenses/by/index.html";
            case "Open Database License (ODC-ODbL)":
                return "https://opendatacommons.org/licenses/odbl/index.html";
            default:
                return "unknown";
        }
    }

    private function getDoiUrl($doi)
    {
        return [
            Str::of($doi)
               ->replace("doi:", "")
               ->prepend("https://doi.org/")
               ->__toString(),
        ];
    }

    private function createAuthor(User $user)
    {
        return [
            "@type"       => "Person",
            "name"        => $user->name,
            "affiliation" => [
                "@type" => "Organization",
                "name"  => $user->affiliations,
            ],
            "email"       => $user->email,
        ];
    }
}
