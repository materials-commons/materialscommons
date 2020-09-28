<form>
    <x-datasets.show-overview :dataset="$dataset"/>
    <x-datasets.show-authors :authors="$dataset->authors"/>
    <x-show-summary :summary="$dataset->summary"/>
</form>
<hr/>
<br/>
<table id="entities-with-used-activities" class="table table-hover" style="width: 100%">
    <thead>
    <th>Name</th>
    @foreach($activities as $activity)
        <th>{{$activity->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($entities as $entity)
        <tr>
            <td>
                <a href="{{route('public.datasets.entities.show', [$dataset, $entity])}}">
                    {{$entity->name}}
                </a>
            </td>
            @foreach($usedActivities[$entity->id] as $used)
                @if($used)
                    <td>X</td>
                    {{--                    <td>{{$entity->name}}</td>--}}
                    {{--                    <td>{{$entity->name}} ({{$used}})</td>--}}
                @else
                    <td></td>
                @endif
            @endforeach
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