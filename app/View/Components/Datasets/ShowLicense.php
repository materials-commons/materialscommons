<?php

namespace App\View\Components\Datasets;

use Illuminate\View\Component;

class ShowLicense extends Component
{

    public $dataset;

    public function __construct($dataset)
    {
        $this->dataset = $dataset;
    }

    public function render()
    {
        return view('components.datasets.show-license');
    }

    public function licenseUrl()
    {
        return blank($this->dataset->license_link) ? $this->license2Url() : $this->dataset->license_link;
    }

    private function license2Url()
    {
        switch ($this->dataset->license) {
            case "Public Domain Dedication and License (PDDL)":
                return "https://opendatacommons.org/licenses/pddl/summary";
            case "Attribution License (ODC-By)":
                return "https://opendatacommons.org/licenses/by/summary";
            case "Open Database License (ODC-ODbL)":
                return "https://opendatacommons.org/licenses/odbl/summary";
            default:
                return "https://opendatacommons.org";
        }
    }
}
