@if(isInBeta())
    <h4>Filters</h4>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <a class="btn btn-success" data-toggle="modal" href="#mql-processes-dialog">
                    Processes
                </a>
                <a class="btn btn-success" data-toggle="modal" href="#mql-process-attributes-dialog">
                    Process Attributes
                </a>
                <a class="btn btn-success" data-toggle="modal" href="#mql-sample-attributes-dialog">
                    Sample Attributes
                </a>
            </div>
            <div class="col-sm-6">
                @include('partials.entities.mql._mql-textbox')
            </div>
        </div>
    </div>
    <br>
    <br>
@endif
<table id="entities-with-used-activities" class="table table-hover" style="width: 100%">
    <thead>
    <th>Name</th>
    @if(isset($showExperiment))
        <th>Experiment</th>
    @endif
    @foreach($activities as $activity)
        <th>{{$activity->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($entities as $entity)
        <tr>
            <td>
                <a href="{{route('projects.entities.show', [$project, $entity])}}">
                    {{$entity->name}}
                </a>
            </td>
            @if(isset($showExperiment))
                <td>
                    @if(isset($entity->experiments))
                        @if($entity->experiments->count() > 0)
                            {{$entity->experiments[0]->name}}
                        @endif
                    @endif
                </td>
            @endif
            @if(isset($usedActivities[$entity->id]))
                @foreach($usedActivities[$entity->id] as $used)
                    @if($used)
                        <td>X</td>
                        {{--                    <td>{{$entity->name}}</td>--}}
                        {{--                    <td>{{$entity->name}} ({{$used}})</td>--}}
                    @else
                        <td></td>
                    @endif
                @endforeach
            @endif
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#entities-with-used-activities').DataTable({
                scrollX: true,
            });
        });
        htmx.on('htmx:after-settle', () => {
            mcutil.autosizeTextareas();
            $('#entities-with-used-activities').DataTable({
                scrollX: true,
            });
        });
    </script>
@endpush
@if(isInBeta())
    <form id="mql-selection" action="{{route('projects.entities.mql.run', [$project])}}" method="POST">
        @csrf
        @include('app.dialogs.mql._processes-dialog')
        @include('app.dialogs.mql._process-attributes-dialog')
        @include('app.dialogs.mql._sample-attributes-dialog')
    </form>
@endif