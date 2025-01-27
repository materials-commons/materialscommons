<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a wire:click.prevent="setTab('details')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "details"])>
                Details
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setTab('authors')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "authors"])>
                Authors
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setTab('files')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "files"])>
                Files
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setTab('samples')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "samples"])>
                Samples
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setTab('computations')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "computations"])>
                Computations
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setTab('other')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "other"])>
                Other
            </a>
        </li>
    </ul>

    <br/>
    @if($activeTab == "details")
        <livewire:projects.datasets.crud.tabs.details/>
    @elseif ($activeTab == "authors")
        authors
        <livewire:projects.datasets.crud.authors/>
    @elseif ($activeTab == "files")
        files
    @elseif ($activeTab == "samples")
        samples
    @elseif ($activeTab == "computations")
        computations
    @else
        other
    @endif
</div>
