<div>
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
        <a class="btn btn-success"
           href="{{ route('projects.datasets.show', [$project, $dataset]) }}">
            <i class="fas fa-check me-1"></i> Save &amp; Exit
        </a>
    </div>

    @include('app.projects.datasets.ce-tabs._short-overview')

    <h5>
        Files will be added or removed automatically as you select them.
    </h5>

    <br>

    <div class="mb-5">
        @if(sizeof($dirPaths) == 1)
            <h3>{{ $directory->name }}</h3>
        @else
            @foreach($dirPaths as $dirpath)
                <a class="action-link"
                   href="{{ route($directoryPathRouteName, ['project' => $project, 'dataset' => $dataset, 'path' => $dirpath['path']]) }}">
                    {{ $dirpath['name'] }}/
                </a>
            @endforeach
        @endif

        <a class="float-end action-link me-4" href="{{ route($addFilesRouteName, [$project, $dataset, $directory]) }}">
            <i class="fas fa-fw fa-plus me-2"></i>Add Files
        </a>

        <a class="float-end action-link me-4" href="{{ route($createDirectoryRouteName, [$project, $dataset, $directory]) }}">
            <i class="fas fa-fw fa-folder-plus me-2"></i>Create Directory
        </a>

        @if ($directory->path !== '/')
            <br/>
            <a href="{{ route($directoryPathRouteName, [$project, $dataset, $directory->directory_id]) }}" class="mb-3">
                <i class="fa-fw fas fa-arrow-alt-circle-up me-2"></i>Go up one level
            </a>
            <br>
            <br>
        @endif
    </div>

    <table id="files" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Size</th>
            <th>Selected</th>
        </tr>
        </thead>
        <tbody>
        @foreach($files as $file)
            <tr wire:key="dataset-file-selection-{{ $file->id }}">
                <td>
                    @if ($file->mime_type === 'directory')
                        <a href="{{ route($directoryPathRouteName, [$project, $dataset, $file]) }}">
                            <i class="fa-fw fas me-2 fa-folder"></i> {{ $file->name }}
                        </a>
                    @else
                        <a href="{{ route('projects.files.show', [$project, $file]) }}">
                            <i class="fa-fw fas me-2 fa-file"></i>{{ $file->name }}
                        </a>
                    @endif
                </td>
                <td>{{ $file->mime_type }}</td>
                @if ($file->mime_type === 'directory')
                    <td>N/A</td>
                @else
                    <td>{{ $file->toHumanBytes() }}</td>
                @endif
                <td>
                    <div class="mb-3 form-check-inline">
                        <input type="checkbox"
                               class="form-check-input"
                               id="{{ $file->uuid }}"
                               wire:click="toggleFile({{ $file->id }})"
                            @checked($file->selected)>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

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
</div>
