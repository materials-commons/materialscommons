@component('components.card')
    @slot('header')
        Recommended Practices Files
        <a class="float-right action-link mr-2" href="">
            <i class="fas fa-fw fa-plus mr-2"></i>Add Files
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
            @foreach($community->files as $file)
                <tr>
                    <td>
                        <a href="#">
                            <i class="fa-fw fas mr-2 fa-file"></i>{{$file->name}}
                        </a>
                    </td>
                    <td>{{$file->mime_type}}</td>
                    <td>{{$file->toHumanBytes()}}</td>
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
        let projectId = "";
        let datasetId = "";
        let route = "";
        let apiToken = "{{$user->api_token}}";

        $(document).ready(() => {
            $('#files').DataTable({
                stateSave: true,
            });
        });

        function updateSelection(file, checkbox) {
            if (checkbox.checked) {
                if (file.mime_type === 'directory') {
                    addDirectory(file);
                } else {
                    addFile(file);
                }

            } else {
                if (file.mime_type === 'directory') {
                    removeDirectory(file);
                } else {
                    removeFile(file);
                }
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

    </script>
@endpush