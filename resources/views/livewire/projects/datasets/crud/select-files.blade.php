<div>
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
                               wire:click="toggleSelection({{$file}})">
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
