<div>
    (Not Implemented Yet) Processes Explorer
    @if(!is_null($project))
        Project: {{$project->id}}
    @endif

    @if(!is_null($experiment))
        Experiment: {{$experiment->id}}
    @endif
</div>
