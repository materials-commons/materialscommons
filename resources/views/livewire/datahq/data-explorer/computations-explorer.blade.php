<div>
    (Not Implemented Yet) Computations Explorer component
    @if(!is_null($project))
        Project: {{$project->id}}
    @endif

    @if(!is_null($experiment))
        Experiment: {{$experiment->id}}
    @endif
</div>
