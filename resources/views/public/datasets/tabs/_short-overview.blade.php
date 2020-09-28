<form>
    <x-datasets.show-overview :dataset="$dataset"/>
    <x-datasets.show-authors :authors="$dataset->authors"/>
    <x-show-summary :summary="$dataset->summary"/>
</form>
<hr/>
<br>