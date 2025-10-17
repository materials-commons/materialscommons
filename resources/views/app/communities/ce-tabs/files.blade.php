@component('components.card-white')
    @slot('header')
        Community Files
        <a class="float-right action-link me-2" href="{{route('communities.files.upload', [$community])}}">
            <i class="fas fa-fw fa-plus me-2"></i>Add Files
        </a>
    @endslot

    @slot('body')
        <table id="files" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Size</th>
            </tr>
            </thead>
            <tbody>
            @foreach($community->files as $file)
                <tr>
                    <td>
                        <a href="{{route('communities.files.show', [$community, $file])}}">
                            <i class="fa-fw fas me-2 fa-file"></i>{{$file->name}}
                        </a>
                    </td>
                    <td>{{$file->mime_type}}</td>
                    <td>{{$file->toHumanBytes()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#files').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });
    </script>
@endpush
