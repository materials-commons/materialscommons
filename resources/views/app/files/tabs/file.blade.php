@isset($project)
    {{--                <a class="float-end action-link" href="#">--}}
    {{--                    <i class="fas fa-edit me-2"></i>Edit--}}
    {{--                </a>--}}

    {{--                <a class="float-end action-link me-4" href="#">--}}
    {{--                    <i class="fas fa-trash-alt me-2"></i>Delete--}}
    {{--                </a>--}}

    @if ($file->mime_type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
        <a class="float-end action-link me-4"
           href="{{route('projects.files.create-experiment', [$project, $file])}}">
            <i class="fas fa-file-import me-2"></i>Create Study From Spreadsheet
        </a>
    @endif
    <a class="action-link float-end me-4"
       href="{{route('projects.files.download', [$project, $file])}}">
        <i class="fas fa-download me-2"></i>Download File
    </a>

    <a class="action-link float-end me-4" href="{{route('projects.files.delete', [$project, $file])}}">
        <i class="fas fa-fw fa-trash me-2"></i>Delete
    </a>
@endisset
<br/>
@include('partials.files._file-header-controls', [
    'displayRoute' => route('projects.files.display', [$project, $file]),
    'editRoute' => route('projects.files.edit', [$project, $file]),
])

<hr>
<br>

@include('partials.files._display-file', [ 'displayRoute' => route('projects.files.display', [$project, $file]) ])

