<br>
<table id="communities" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Name</th>
        <th>Summary</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dataset->communities as $community)
        <tr>
            <td>
                <a href="{{route('communities.show', [$community])}}">
                    {{$community->name}}
                </a>
            </td>
            <td>{{$community->summary}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).ready(() => {
                $('#communities').DataTable({
                    stateSave: true,
                });
            });
        });
    </script>
@endpush