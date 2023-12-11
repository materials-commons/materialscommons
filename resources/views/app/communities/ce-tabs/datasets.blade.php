@component('components.card')
    @slot('header')
        Datasets In Community
        <a class="float-right action-link mr-2" href="{{route('communities.datasets.update', [$community])}}">
            <i class="fas fa-fw fa-edit mr-2"></i>Add/Remove Datasets
        </a>
    @endslot

    @slot('body')
        <table id="datasets" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($community->datasets as $dataset)
                <tr>
                    <td>
                        <a href="{{route('public.datasets.show', [$dataset])}}">{{$dataset->name}}</a>
                    </td>
                    <td>{{$dataset->summary}}</td>
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
                pageLength: 100,
                stateSave: true,
            });
        });
    </script>
@endpush
