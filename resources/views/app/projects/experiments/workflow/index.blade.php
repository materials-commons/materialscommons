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
                Workflow here
                <div id="workflow"></div>
            </div>
        @endslot
    @endcomponent

    @push('scripts')
        <script>

            window.onload = function () {
                let chart;
                let code = `
        st=>start: Receive Samples
        e=>end: Finished
        sand_sample=>operation: Sand Sample
        ht?=>condition: Heat Treat?
        sem=>operation: SEM
        ht_op=>operation: Heat Treatment 400c/2h
        ht_op2=>operation: Heat Treatment 400c/2h
        ht_op3=>operation: Heat Treatment 600c/1h
        ht_op4=>operation: Heat Treatment 600c/4h
        ht_op5=>operation: Heat Treatment 200c/3h
        ht_op6=>operation: Heat Treatment
        ht_op7=>operation: Heat Treatment
        ht_op8=>operation: Heat Treatment
        ht_op9=>operation: Heat Treatment
        ht_op10=>operation: Heat Treatment

        st->sand_sample(right)->ht?
        ht?(yes, right)->ht_op(right)->ht_op2(right)->ht_op3(right)->ht_op4(right)->ht_op5
        ht_op5->sem
        ht?(no)->sem
        sem->e
        `;
                chart = flowchart.parse(code);
                chart.drawSVG('workflow', {
                    // 'x': 30,
                    // 'y': 50,
                    'line-width': 3,
                    'maxWidth': 3,//ensures the flowcharts fits within a certian width
                    'line-length': 50,
                    'text-margin': 10,
                    'font-size': 14,
                    'font': 'normal',
                    'font-family': 'Helvetica',
                    'font-weight': 'normal',
                    'font-color': 'black',
                    'line-color': 'black',
                    'element-color': 'black',
                    'fill': 'white',
                    'yes-text': 'yes',
                    'no-text': 'no',
                    'arrow-end': 'block',
                    'scale': 1,
                    'symbols': {
                        'start': {
                            'font-color': 'red',
                            'element-color': 'green',
                            'fill': 'yellow'
                        },
                        'end': {
                            'class': 'end-element'
                        }
                    },
                    'flowstate': {
                        'past': {'fill': '#CCCCCC', 'font-size': 12},
                        'current': {'fill': 'yellow', 'font-color': 'red', 'font-weight': 'bold'},
                        'future': {'fill': '#FFFF99'},
                        'request': {'fill': 'blue'},
                        'invalid': {'fill': '#444444'},
                        'approved': {'fill': '#58C4A3', 'font-size': 12, 'yes-text': 'APPROVED', 'no-text': 'n/a'},
                        'rejected': {'fill': '#C45879', 'font-size': 12, 'yes-text': 'n/a', 'no-text': 'REJECTED'}
                    }
                });
                $('[id^=sub1]').click(function () {
                    alert('info here');
                });
            };

            function myFunction(event, node) {
                console.log("You just clicked this node:", node);
            }

        </script>
    @endpush
@stop
