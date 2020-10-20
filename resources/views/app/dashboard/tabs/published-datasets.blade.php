@component('components.card')
    @slot('header')
        Published Datasets
        <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
           title="Publish data"
           class="action-link float-right">
            <i class="fas fa-plus mr-2"></i> Upload and Publish Data
        </a>
    @endslot
    @slot('body')
        <h4>Published Datasets</h4>
        <br>
        <table id="datasets" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Dataset</th>
                <th>Summary</th>
                <th>Published</th>
                <th>Date</th>
                <th>Views</th>
                <th>Downloads</th>
                <th>Comments</th>
            </tr>
            </thead>
            <tbody>
            @foreach($publishedDatasets as $ds)
                <tr>
                    <td>
                        <a class="action-link" href="{{route('public.datasets.show', [$ds])}}">
                            {{$ds->name}}
                        </a>
                    </td>
                    <td>{{$ds->summary}}</td>
                    <td>{{$ds->published_at->diffForHumans()}}</td>
                    <td>{{$ds->published_at}}</td>
                    <td>{{$ds->views_count}}</td>
                    <td>{{$ds->downloads_count}}</td>
                    <td>{{$ds->comments_count}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#datasets').DataTable({
                stateSave: true,
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
