<?php

namespace App\Livewire\Projects\DesktopApp;

use App\Services\MCDesktopAppService;
use Livewire\Component;

class ConnectedClients extends Component
{
    public $project;

    public function render()
    {
        return view('livewire.projects.desktop-app.connected-clients', [
            'clients' => MCDesktopAppService::getActiveDesktopClientsForUserProject(auth()->id(), $this->project->id),
        ]);
    }
}
