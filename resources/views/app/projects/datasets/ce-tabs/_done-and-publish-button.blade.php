@if(is_null($dataset->published_at) && !blank($dataset->description) && $dataset->hasSelectedFiles())
    <a class="btn btn-success ms-3" href="{{route('projects.datasets.publish', [$project, $dataset])}}"
       id="done-and-publish">
        Done and Publish
    </a>
@elseif(is_null($dataset->published_at) && !blank($dataset->description))
    <a class="btn btn-success ms-3" href="{{route('projects.datasets.publish', [$project, $dataset])}}"
       id="done-and-publish" style="display: none">
        Done and Publish
    </a>
@endif