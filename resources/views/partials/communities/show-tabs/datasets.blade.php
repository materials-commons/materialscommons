<table id="datasets" class="table table-hover">
    <thead>
    <tr>
        <th>Dataset</th>
        <th>Description</th>
        <th>Owner</th>
        <th>Updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($community->datasets as $dataset)
        <tr>
            <td>
                @isset($datasetRouteName)
                    <a href="{{route($datasetRouteName, [$dataset])}}">{{$dataset->name}}</a>
                @else
                    {{$dataset->name}}
                @endisset
            </td>
            <td>{{$dataset->description}}</td>
            <td>{{$dataset->owner->name}}</td>
            <td>{{$dataset->updated_at->diffForHumans()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#datasets').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush