@include('partials.communities.show-tabs._show-overview')
<table id="datasets" class="table table-hover">
    <thead>
    <tr>
        <th>Dataset</th>
        <th>Description</th>
        <th>Owner</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($community->datasetsWaitingForApproval as $dataset)
        @if(!is_null($dataset->published_at))
            <tr>
                <td>
                    <a href="{{route($datasetRouteName, [$dataset])}}">{{$dataset->name}}</a>
                </td>
                <td>{{$dataset->description}}</td>
                <td>{{$dataset->owner->name}}</td>
                <td>
                    <a href="{{route('communities.waiting-approval.approve', [$community, $dataset])}}">
                        Approve
                    </a>
                    <a href="{{route('communities.waiting-approval.reject', [$community, $dataset])}}">
                        Reject
                    </a>
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#datasets').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });
    </script>
@endpush
