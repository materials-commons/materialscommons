<div>
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link no-underline active">Details</a></li>
        <li class="nav-item"><a class="nav-link no-underline">Files</a></li>
        <li class="nav-item"><a class="nav-link no-underline">Optional</a></li>
    </ul>

    <br/>
    @if($activeTab == "required")
        <livewire:projects.datasets.fields.required/>
    @elseif ($activeTab == "files")
    @else
    @endif
</div>
