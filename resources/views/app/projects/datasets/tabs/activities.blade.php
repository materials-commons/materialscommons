@include('app.projects.datasets.tabs._short-overview')
<table id="activities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dataset->activities as $activity)
        <tr>
            <td>
                <a href="{{route('projects.activities.show', [$project, $activity])}}">
                    {{$activity->name}}
                </a>
            </td>
            <td>{{$activity->description}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).ready(() => {
                $('#activities').DataTable({
                    stateSave: true,
                });
            });
        });
    </script>
@endpush