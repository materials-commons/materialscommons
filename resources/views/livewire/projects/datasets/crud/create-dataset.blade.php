<div>
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link no-underline active">Details</a></li>
        <li class="nav-item"><a class="nav-link no-underline">Authors</a></li>
        <li class="nav-item"><a class="nav-link no-underline">Files</a></li>
        <li class="nav-item"><a class="nav-link no-underline">Samples</a></li>
        <li class="nav-item"><a class="nav-link no-underline">Computations</a></li>
        <li class="nav-item"><a class="nav-link no-underline">Other</a></li>
    </ul>

    <br/>
    @if($activeTab == "details")
        <livewire:projects.datasets.crud.tabs.details/>
    @elseif ($activeTab == "authors")
    @elseif ($activeTab == "files")
    @elseif ($activeTab == "samples")
    @elseif ($activeTab == "computations")
    @else
    @endif
</div>
