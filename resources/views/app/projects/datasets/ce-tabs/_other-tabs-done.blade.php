<br>
<div class="float-end">
    <a class="btn btn-success" href="{{route('projects.datasets.show', [$project, $dataset])}}" id="done-button">
        Done
    </a>

    @include('app.projects.datasets.ce-tabs._done-and-publish-button')
</div>
<br>