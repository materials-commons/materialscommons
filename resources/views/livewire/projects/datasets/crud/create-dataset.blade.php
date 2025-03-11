<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a wire:click.prevent="setActiveTab('details')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "details"])>
                Details
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setActiveTab('authors')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "authors"])>
                Authors
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setActiveTab('files')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "files"])>
                Files
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setActiveTab('samples')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "samples"])>
                Samples
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setActiveTab('computations')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "computations"])>
                Computations
            </a>
        </li>
        <li class="nav-item">
            <a wire:click.prevent="setActiveTab('other')" href="#"
                @class(["nav-link", "no-underline", "active" => $activeTab == "other"])>
                Other
            </a>
        </li>
    </ul>

    <br/>
    @if($activeTab == "details")
        <livewire:projects.datasets.crud.details :dataset="$dataset"/>
    @elseif ($activeTab == "authors")
        <livewire:projects.datasets.crud.authors :dataset="$dataset" wire:key="{{$key}}"/>
    @elseif ($activeTab == "files")
        files
    @elseif ($activeTab == "samples")
        <livewire:projects.datasets.crud.select-samples :dataset="$dataset" :project="$project" wire:key="{{$key}}"/>
    @elseif ($activeTab == "computations")
        computations
    @else
        other
    @endif
</div>
