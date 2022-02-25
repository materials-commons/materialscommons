@include('partials.communities.show-tabs._show-overview')
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
        @if(!is_null($dataset->published_at))
            <tr>
                <td>
                    <a href="{{route($datasetRouteName, [$dataset])}}">{{$dataset->name}}</a>
                </td>
                <td>{{$dataset->description}}</td>
                <td>{{$dataset->owner->name}}</td>
                <td>{{$dataset->updated_at->diffForHumans()}}</td>
            </tr>
        @endif
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