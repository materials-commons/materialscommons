@extends('layouts.email')

@section('content')
    <p>
        Dear {{$run->owner->name}},
    </p>
    <p>
        Your run of {{$run->script->scriptFile->name}} in project {{$run->project->name}}
        @if(is_null($run->failed_at))
            completed at {{$run->finished_at}}.
        @else
            failed at {{$run->failed_at}}.
        @endif

    </p>
    <p>
        You can find the details and log for your run <a
                href="{{route('projects.runs.show', [$run->project_id, $run->id])}}">here</a>. Any files that your job
        created
        have been imported into the project.
    </p>
    <p>
        Thank you,
        <br/>
        The Materials Commons Team
    </p>
@endsection
