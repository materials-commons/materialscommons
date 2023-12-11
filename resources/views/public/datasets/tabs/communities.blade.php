@include('public.datasets.tabs._short-overview')
<table id="communities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dataset->publishedCommunities as $community)
        <tr>
            <td>
                <a href="{{route('public.communities.datasets.index', [$community])}}">
                    {{$community->name}}
                </a>
            </td>
            <td>{{$community->description}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).ready(() => {
                $('#communities').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        });
    </script>
@endpush
