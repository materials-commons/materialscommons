@if(is_null($dataset->published_at) && !blank($dataset->description) && $dataset->hasSelectedFiles())
    <a class="btn btn-success ml-3" href="{{route('projects.datasets.publish', [$project, $dataset])}}">
        Done and Publish
    </a>
@endif