@extends('layouts.app')

@section('pageTitle', 'Experiments')

@section('nav')
    @include('layouts.navs.project')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Experiment {{$experiment->name}}
        @endslot

        @slot('body')
            @component('components.experiment-tabs', ['project' => $project, 'experiment' => $experiment])
            @endcomponent

            <div class="ml-2">
                <div id="workflow"></div>
            </div>
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            $(document).ready(() => {
                let count = {!! $experiment->workflows()->count() !!};
                if (count !== 0) {
                    let code = `{!! $experiment->workflows[0]->workflow !!}`;
                    mcfl.drawFlowchart('workflow', code);
                }
                $('[id^=sub1]').click(function () {
                    alert('info here');
                });
            });

            function myFunction(event, node) {
                console.log("You just clicked this node:", node);
            }

        </script>
    @endpush
@stop
