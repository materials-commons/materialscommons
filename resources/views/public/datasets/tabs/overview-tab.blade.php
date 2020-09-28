<form>
    <x-datasets.show-overview :dataset="$dataset"/>

    <x-datasets.show-authors :authors="$dataset->authors"/>

    @if(!blank($dataset->description))
        <x-show-description :description="$dataset->description"/>
    @elseif (!blank($dataset->summary))
        <x-show-summary :summary="$dataset->summary"/>
    @endif

    <x-datasets.show-papers-list :papers="$dataset->papers"/>

    <x-datasets.show-tags :tags="$dataset->tags"/>
</form>
<hr>
<br>

@include('partials.overview._overview')