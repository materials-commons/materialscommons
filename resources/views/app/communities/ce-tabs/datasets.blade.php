@component('components.card')
    @slot('header')
        Datasets In Community
        <a class="float-right action-link mr-2" href="">
            <i class="fas fa-fw fa-plus mr-2"></i>Add Datasets
        </a>
    @endslot

    @slot('body')
        <table id="files" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Selected</th>
            </tr>
            </thead>
            <tbody>
            @foreach($community->datasets as $dataset)
                <tr>
                    <td>
                        <a href="#">
                            <i class="fa-fw fas mr-2 fa-file"></i>{{$dataset->name}}
                        </a>
                    </td>
                    <td>{{$dataset->summary}}</td>
                    <td>
                        <div class="form-group form-check-inline">
                            <input type="checkbox" class="form-check-input" id="{{$dataset->uuid}}"
                                   {{$dataset->selected ? 'checked' : ''}}
                                   onclick="updateSelection({{$dataset}}, this)">
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