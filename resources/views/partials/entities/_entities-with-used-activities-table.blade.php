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
            <form class="ml-4">
                <div class="form-group">
                    <label for="mql">Current Filters</label>
                    <textarea class="form-control" id="mql" placeholder="Filters...">{{$filters}}</textarea>
                </div>
                <div class="float-right">
                    <button class="btn btn-danger">Clear Filters</button>
                    <button class="btn btn-success">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
<br>
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
                stateSave: true,
                scrollX: true,
            });
        });
    </script>
@endpush

@include('app.dialogs.mql._processes-dialog')
@include('app.dialogs.mql._process-attributes-dialog')
@include('app.dialogs.mql._sample-attributes-dialog')