<?php

namespace App\Livewire\Projects\Datasets\Crud;

use Livewire\Component;

class UpdateLicense extends Component
{
    public $license;

    public function setLicense($license)
    {
        $this->license = $license;
        $this->dispatch("update-license", $license);
    }

    public function render()
    {
        return view('livewire.projects.datasets.crud.update-license');
    }
}
