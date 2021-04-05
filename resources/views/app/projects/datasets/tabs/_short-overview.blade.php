<form>
    <x-datasets.show-overview :dataset="$dataset"/>
    <x-datasets.show-authors :authors="$dataset->ds_authors"/>
    <x-show-summary :summary="$dataset->summary"/>
{{--    @include('app.projects.datasets._edit-controls')--}}
</form>
<br>
<hr>
