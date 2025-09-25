<br>
@component('components.card-white')
    @slot('header')
        {{$directory->path}}
    @endslot

    @slot('body')
        @if ($directory->path !== '/')
            <a href="{{route('projects.datasets.show.next', [$project, $dataset, $directory->directory_id])}}"
               class="mb-3">
                <i class="fa-fw fas fa-arrow-alt-circle-up mr-2"></i>Go up one level
            </a>
            <br>
            <br>
        @endif

        <table id="files" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Size</th>
                <th>Hidden Size</th>
                <th>Selected</th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $file)
                @if($file->selected)
                    <tr>
                        <td>
                            @if ($file->mime_type === 'directory')
                                <a href="{{route('projects.datasets.show.next', [$project, $dataset, $file])}}">
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
                        <td>{{$file->size}}</td>
                        <td>
                            <div class="form-group form-check-inline">
                                <input type="checkbox" class="form-check-input" id="{{$file->uuid}}"
                                       {{$file->selected ? 'checked' : ''}} readonly onclick="return false;">
                            </div>
                        </td>
                    </tr>
                @endif
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
                columnDefs: [
                    {orderData: [3], targets: [2]},
                    {targets: [3], visible: false},
                ]
            });
        });
    </script>
@endpush
