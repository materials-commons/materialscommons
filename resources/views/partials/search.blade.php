<table class="bootstrap-table bootstrap-table-hover" id="dt-table">
    <thead>
    <tr>
        <th>Name</th>
        <th>Summary</th>
        <th>Type</th>
    </tr>
    </thead>
    <tbody>
    @foreach($searchResults->groupByType() as $type => $modelSearchResults)
        @foreach($modelSearchResults as $searchResult)
            <tr>
                <td>
                    <a href="{{$searchResult->url}}">{{$searchResult->title}}</a>
                </td>
                <td>{{$searchResult->searchable->summary}}</td>
                <td>{{$searchResult->searchable->type}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#dt-table').DataTable({
                language: {
                    search: "Filter:"
                }
            });
        });
    </script>
@endpush