<div class="float-end">
    <a class="action-link mr-3" href="{{route('projects.datasets.edit', [$project, $dataset])}}">Edit</a>
    <a class="action-link mr-3" href="{{route('projects.datasets.index', [$project])}}">Done</a>
    @if (isset($dataset->published_at))
        <a class="action-link" href="{{route('projects.datasets.unpublish', [$project, $dataset])}}">
            Done And Unpublish
        </a>
    @elseif($dataset->hasSelectedFiles())
        <a class="action-link" href="{{route('projects.datasets.publish', [$project, $dataset])}}">
            Done And Publish
        </a>
    @endif
</div>