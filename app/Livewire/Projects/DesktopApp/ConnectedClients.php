<?php

namespace App\Livewire\Projects\DesktopApp;

use App\Services\MCDesktopAppService;
use Livewire\Attributes\On;
use Livewire\Component;

class ConnectedClients extends Component
{
    public $project;

    #[On('refresh-clients')]
    public function render()
    {
        return view('livewire.projects.desktop-app.connected-clients', [
            'clients' => MCDesktopAppService::getActiveDesktopClientsForUser(auth()->id()),
        ]);
    }
}
