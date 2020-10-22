@include('app.projects.datasets.ce-tabs._other-tabs-done')
@include('app.projects.datasets.ce-tabs._short-overview')
<h5>
    Files will be added or removed automatically as you select them.
</h5>
<br>
@component('components.card')
    @slot('header')
        @if(sizeof($dirPaths) == 1)
            {{$directory->name}}
        @else
            @foreach($dirPaths as $dirpath)
                <a class="action-link"
                   href="{{route($directoryPathRouteName, ['project' => $project, 'dataset' => $dataset, 'path' => $dirpath["path"]])}}">
                    {{$dirpath['name']}}/
                </a>
            @endforeach
        @endif

        <a class="float-right action-link mr-4" href="{{route($addFilesRouteName, [$project, $dataset, $directory])}}">
            <i class="fas fa-fw fa-plus mr-2"></i>Add Files
        </a>

        <a class="float-right action-link mr-4"
           href="{{route($createDirectoryRouteName, [$project, $dataset, $directory])}}">
            <i class="fas fa-fw fa-folder-plus mr-2"></i>Create Directory
        </a>
    @endslot

    @slot('body')
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
                <tr>
                    <td>
                        @if ($file->mime_type === 'directory')
                            <a href="{{route($directoryPathRouteName, [$project, $dataset, $file])}}">
                                <i class="fa-fw fas mr-2 fa-folder"></i> {{$file->name}}
                            </a>
                        @else
                            <a href="{{route('projects.files.show', [$project, $file])}}">
                                <i class="fa-fw fas mr-2 fa-file"></i>{{$file->name}}
                            </a>
                        @endif
                    </td>
                    <td>{{$file->mime_type}}</td>
                    @if ($file->mime_type === 'directory')
                        <td>N/A</td>
                    @else
                        <td>{{$file->toHumanBytes()}}</td>
                    @endif
                    <td>
                        <div class="form-group form-check-inline">
                            <input type="checkbox" class="form-check-input" id="{{$file->uuid}}"
                                   {{$file->selected ? 'checked' : ''}}
                                   onclick="updateSelection({{$file}}, this)">
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        let projectId = "{{$project->id}}";
        let datasetId = "{{$dataset->id}}";
        let directoryPath = "{{$directory->path == '/' ? '' : $directory->path}}";
        let route = "{{route('projects.datasets.selection', [$dataset])}}";
        let apiToken = "{{$user->api_token}}";

        let filesSelectedCount = 0;
        @if(isset($dataset->file_selection['include_files']))
            filesSelectedCount = {{sizeof($dataset->file_selection['include_files'])}};
        @endif

        let dirsSelectedCount = 0;
        @if(isset($dataset->file_selection['include_dirs']))
            dirsSelectedCount = {{sizeof($dataset->file_selection['include_dirs'])}};
        @endif


        let totalSelectedCount = filesSelectedCount + dirsSelectedCount;

        $(document).ready(() => {
            $('#files').DataTable({
                stateSave: true,
            });
        });

        function updateSelection(file, checkbox) {
            if (checkbox.checked) {
                totalSelectedCount++;
                if (file.mime_type === 'directory') {
                    addDirectory(file);
                } else {
                    addFile(file);
                }

            } else {
                totalSelectedCount--;
                if (file.mime_type === 'directory') {
                    removeDirectory(file);
                } else {
                    removeFile(file);
                }
            }
            if (totalSelectedCount > 0) {
                setFileStatusPositive();
            } else {
                setFileStatusError();
            }
        }

        function addDirectory(dir) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                include_dir: `${dir.path}`
            }).then(
                () => null
            );
        }

        function addFile(file) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                include_file: `${directoryPath}/${file.name}`
            }).then(
                () => null
            );
        }

        function removeDirectory(dir) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                remove_include_dir: `${dir.path}`
            }).then(
                () => null
            );
        }

        function removeFile(file) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                remove_include_file: `${directoryPath}/${file.name}`
            }).then(
                () => null
            );
        }

        function setFileStatusPositive() {
            // first clear classes, then add the classes we want
            $('#files-step').removeClass('step-error step-success')
                .addClass('step-success');
            $('#files-circle').removeClass('fa-check fa-exclamation')
                .addClass('fa-check');
            let doneAndPublishButton = $('#done-and-publish');
            if (doneAndPublishButton) {
                doneAndPublishButton.show('fast');
            }
            $('#error-files').hide('fast');
        }

        function setFileStatusError() {
            // first clear classes, then add the classes we want
            $('#files-step').removeClass('step-success step-error')
                .addClass('step-error');
            $('#files-circle').removeClass('fa-check fa-exclamation')
                .addClass('fa-exclamation');
            let doneAndPublishButton = $('#done-and-publish');
            if (doneAndPublishButton) {
                doneAndPublishButton.hide('fast');
            }
            $('#error-files').show('fast');
        }

    </script>
@endpush