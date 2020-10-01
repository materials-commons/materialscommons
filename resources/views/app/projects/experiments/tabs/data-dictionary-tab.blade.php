<x-show-standard-details :item="$experiment"/>
<hr>
<br>
@include('partials.datadictionary._show', ['name' => $experiment->name])