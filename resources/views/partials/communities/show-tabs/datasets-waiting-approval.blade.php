@include('partials.communities.show-tabs._show-overview')
<table id="datasets" class="table table-hover">
    <thead>
    <tr>
        <th>Dataset</th>
        <th>Description</th>
        <th>Owner</th>
        <th style="width:20%"></th>
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
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{route('communities.waiting-approval.approve', [$community, $dataset])}}">
                                <i class="fa fas fa-thumbs-up mr-2"></i>Approve
                            </a>
                        </li>
                        <li>
                            <a href="{{route('communities.waiting-approval.reject', [$community, $dataset])}}">
                                <i class="fa fas fa-thumbs-down mr-2"></i>Reject
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{$dataset->owner->email}}?subject={{$dataset->name}}">
                                <i class="fa fas fa-envelope mr-2"></i>Email Owner
                            </a>
                        </li>
                    </ul>
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
