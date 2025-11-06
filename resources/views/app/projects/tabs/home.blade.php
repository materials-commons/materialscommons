<div class="row align-items-stretch">
    <div class="col-md-4 d-flex">
        @include('app.projects.tabs.home._files-card')
    </div>
    <div class="col-md-4 d-flex">
        @include('app.projects.tabs.home._studies-card')
    </div>
    <div class="col-md-4 d-flex">
        @include('app.projects.tabs.home._datasets-card')
    </div>
</div>

<x-display-markdown-file :file="$readme"></x-display-markdown-file>
