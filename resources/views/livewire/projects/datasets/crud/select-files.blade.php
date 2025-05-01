<div>
    <h3>
        @if(sizeof($dirPaths) == 1)
            <a class="no-underline"
               wire:click.prevent="gotoDirectoryByPath('/')"
               href="#">/</a>
        @else
            @foreach($dirPaths as $dirpath)
                <a class="no-underline"
                   wire:click.prevent="gotoDirectoryByPath('{{$dirpath['path']}}')"
                   href="#">
                    {{$dirpath['name']}}/
                </a>
            @endforeach
        @endif
    </h3>
    <x-table.table-search/>
    <table id="files" class="table table-hover mt-2" style="width:100%">
        <thead>
        <tr>
            <th></th>
            <th>
                <x-table.col-sortable :column="'name'" :sort-col="$sortCol" :sort-asc="$sortAsc">
                    Name
                </x-table.col-sortable>
            </th>
            <th>Type</th>
            <th>Size</th>
        </tr>
        </thead>
        <tbody>
        @foreach($files as $file)
            <tr>
                <td>
                    <div class="form-group form-check-inline">
                        <input type="checkbox" class="form-check-input" id="{{$file->uuid}}"
                               {{$file->selected ? 'checked' : ''}}
                               wire:click="toggleSelected('{{$file->toPath($currentDir->path)}}', '{{$file->mime_type}}', {{json_encode($file->selected)}})">
                    </div>
                </td>
                <td>
                    @if ($file->mime_type === 'directory')
                        <a href="#" wire:click.prevent="gotoDirectory({{$file->id}})">
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
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
