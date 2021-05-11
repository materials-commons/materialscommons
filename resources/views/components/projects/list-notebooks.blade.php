<div>
    @if($notebooks->isNotEmpty())
        <h3>Notebooks and Spreadsheets</h3>
        <br>
        <table id="notebooks" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>File</th>
                <th>Last Modified</th>
                <th>By</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($notebooks as $notebook)
                <tr>
                    <td>
                        <a href="{{route('projects.files.show', [$project, $notebook])}}">
                            @if ($notebook->directory->path == '/')
                                /{{$notebook->name}}
                            @else
                                {{$notebook->directory->path}}/{{$notebook->name}}
                            @endif
                        </a>
                    </td>
                    <td>{{$notebook->updated_at->diffForHumans()}}</td>
                    <td>{{$notebook->owner->name}}</td>
                    <td>
                        <a href="{{route('projects.files.download', [$project, $notebook])}}">
                            Download
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#notebooks').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush