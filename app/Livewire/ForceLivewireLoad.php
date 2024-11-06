<?php

namespace App\Livewire;

use Livewire\Component;

// ForceLivewireLoad is a component that does nothing, but exists to load livewire on every page. This
// is done to force the load of livewire.js which contains alpinejs, which many non livewire components
// and pages depend on.
class ForceLivewireLoad extends Component
{
    public function render()
    {
        return <<<'HTML'
        <span style="display:none">
            {{-- Force Livewire to load on every page --}}
        </span>
        HTML;
    }
}
