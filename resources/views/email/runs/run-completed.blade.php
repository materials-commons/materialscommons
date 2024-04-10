@extends('layouts.email')

@section('content')
    <p>
        Dear {{$run->owner->name}},
    </p>
    <p>
        Your run of {{$run->script->scriptFile->name}} in project {{$run->project->name}}
        @if(is_null($run->aborted))
            completed at {{$run->finished_at}}.
        @else
            aborted at {{$run->aborted_at}}.
        @endif

    </p>
    <p>
        You can find the logs for your run at __link__here__. Any files that your job created
        have been imported into the project.
    </p>
    <p>
        Thank you,
        <br/>
        The Materials Commons Team
    </p>
@endsection
